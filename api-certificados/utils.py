import json
from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
from pdfrw import PdfReader, PdfWriter, PageMerge
from datetime import datetime
import requests


# funcion simplemente para ver la fecha
def get_date ():
    fecha_actual = datetime.now()
    return fecha_actual.strftime("%d/%m/%Y")

# esto basicamente obtiene los datos de la db en un json, para pasarlo al pdf
def get_json (id_lote, tipo, cliente, cantidad):
    # En caso de que esté vacio
    if not id_lote:
        raise ValueError("El ID del lote no puede estar vacío.")

    # query para obtener las mediciones
    res_medicion = requests.get("http://localhost:3141/db", json={
        "query": f"SELECT * FROM medicion WHERE id_lote={id_lote} AND tipo=\"{tipo}\";"
    })
    # query para obtener los datos del cliente y el domicilio
    res_cliente = requests.get("http://localhost:3141/db", json={
        "query": f"SELECT c.rfc, d.calle, d.num_int, d.num_ext, d.colonia, d.codigo_postal, d.ciudad, d.estado, d.pais FROM cliente c JOIN domicilio d ON c.id_domicilio_entrega = d.id_domicilio WHERE c.id_cliente = {cliente};"}).json()['answer']
    # query para obtener los datos del lote
    res_lote = requests.get("http://localhost:3141/db", json={
        "query": f"SELECT * FROM lote WHERE id_lote={id_lote};"
    }).json()['answer']

    #### Para checar si está vacío:
    # necesitamos que haya dos registros
    data = res_medicion.json()['answer']
    if len(data) != 2:
        raise ValueError("Filas detectadas", len(data))
    
    # este es el cuerpo del diccionario con el que se forma el PDF
    resultado = {
        "mediciones": {},
        "limites": {},
        "lote": id_lote,
        "fecha": get_date(),
        "folio": abs(hash(str(id_lote))), # cambia lote por "numero de pedido" o "pedido" en el PDF
        "rfc": res_cliente[0][0],
        # orden de res_cliente[0]: rfc, calle, num_int, num_ext, colonia, cp, ciudad, estado, pais
        #                          0    1      2        3        4        5   6       7       8
        "ubicacion": f"{res_cliente[0][6]}, {res_cliente[0][7]}, {res_cliente[0][8]}",
        "cp": res_cliente[0][5],
        "direccion": f"{res_cliente[0][1]} #{res_cliente[0][3]}, colonia {res_cliente[0][4]}",
        "cantidad": cantidad + " Kg",
        "fecha_produccion": res_lote[0][1].replace('-','/'),
        "fecha_caducidad": res_lote[0][2].replace('-','/')
    }
    
    # esto sirve para leer las mediciones en jsons
    for row in res_medicion.json()['answer']:
        medidas = json.loads(row[3]) # esto cambio a 3
        for medida in medidas:
            # y agrearlas al diccionario
            resultado["mediciones"][medida] = medidas[medida]

    # esto es básicamente para obtener los limites del cliente
    res = requests.get("http://173.255.197.59:3141/db", json={
        "query": f"select * from parametro where id_parametro=2;"
    }).json()['answer']
    # las cargamos
    metricas = json.loads(res[0][1])
    # y las almacenamos en el diccionario
    for tipo in ['A', 'F']:
        for metrica in metricas[tipo].keys():
            resultado["limites"][metrica] = metricas[tipo][metrica]

    return resultado


def create_pdf (data): 
    # leer el json que conseguimo
    # data = json.loads(json_data)
    #### En caso de que no exista los datos ####
    if not data:
        raise ValueError("No se pudieron obtener datos válidos para crear el PDF.")

    # Crear un nuevo PDF con reportlab
    c = canvas.Canvas("tmp.pdf", pagesize=letter)
    width, height = letter  # Tamaño de la página en puntos

    # Ajustar las coordenadas para alinear el texto correctamente
    c.drawString(130, 609, f"{data['fecha']}              {data['ubicacion']}    CP:{data['cp']}")   # Ajuste de altura para fecha, direccion,y cantidad
    c.drawString(130, 580, f"{data['lote']}                                                                                       PROD:{data['fecha_produccion'][:-13]}")     # Ajuste de altura para lote, ubicación, y fecha de produccion
    c.drawString(130, 550, f"{data['folio']}              Cantidad:{data['cantidad']}           CAD:{data['fecha_caducidad'][:-13]}")  # Ajuste de altura para folio, cp, y fecha de caducidad
    c.drawRightString(575, 632, f"Dirección: {data['direccion']}") #Direccion con datos


    # Añadir mediciones en una tabla o directamente en el PDF
    y_position = 460  # Comienza más abajo para alinear con la sección de mediciones
    count = 0  # Contador para seguir la fila actual
    for medida, valor in data['mediciones'].items():
        count += 1
        #Cambiamos el nombre de la primera medida porque es muy largo
        if count == 1:
            texto_medida = "Tenacidad a Extensibilidad"
        else:
            texto_medida = f"{medida}"

        #Se quitó para que se viera mejor el PDF
        #texto_medida = f"{medida}"

        # limite = data['limites'][medida]
        #### Cambios por errores al correr ####
        limite = [float(data['limites'][medida][0]), float(data['limites'][medida][1])]
        valor = float(valor)

        texto_valor = f"{valor}"
        texto_limite = f"{limite[0]}-{limite[1]}"

        # Establecer color basado en el valor
        if limite[0] <= valor <= limite[1]:
            if valor in (limite[0], limite[1]):
                c.setFillColorRGB(0, 0, 1)  # Azul para valores en el límite
            else:
                c.setFillColorRGB(0, 0, 0)  # Negro para valores dentro de los límites
        else:
            c.setFillColorRGB(1, 0, 0)  # Rojo para valores fuera de los límites

        # Ajustes condicionales para la posición y
        if 4 <= count <= 6:
            y_adjustment = 6  # Subir ligeramente las filas 4 a 7
        elif 1 <= count <= 2:
            y_adjustment = -1
        elif count == 3:
            y_adjustment = 1
        elif count == 7:
            y_adjustment = 10
        elif count == 8:
            y_adjustment = 12  # Bajar ligeramente la fila 8
        else:
            y_adjustment = 15

        # Dibujar textos con ajustes de y
        c.drawRightString(220, y_position + y_adjustment, texto_medida)
        c.drawRightString(330, y_position + y_adjustment, texto_valor)
        c.drawRightString(480, y_position + y_adjustment, texto_limite)
        y_position -= 29  # Mayor espacio vertical entre mediciones
        c.setFillColorRGB(0, 0, 0)  # Restablecer a negro después de cada línea

    c.save()

    # Fusionar este nuevo PDF con la plantilla existente
    template = PdfReader('frame.pdf')
    overlay = PdfReader('tmp.pdf')
    page = PageMerge(template.pages[0])
    page.add(overlay.pages[0]).render()

    # Guardar el PDF modificado
    pdf_path = f"../certificados/{data['folio']}.pdf"
    writer = PdfWriter(pdf_path)
    writer.addpage(template.pages[0])
    writer.write()

    print("PDF creado", pdf_path)
    return f"certificados/{data['folio']}.pdf"

def main_function (lote, tipo, cliente, cantidad):
    try:
        # Se le pasará el json y se probarán las funciones
        json_data = get_json(lote, tipo, cliente, cantidad)
        # print(json_data)
        # return json_data
        return create_pdf(json_data)
    except ValueError as e:
        return "error"

main_function ('1', 'B', '1', '300')