from utils import main_function

from flask_cors import CORS # flask middlewares
# flask utils
from flask import Flask, request, jsonify
# instance the flask app
app = Flask(__name__)
# and the middlewares
CORS(app)

@app.route('/certificados', methods=['POST'])
async def certificados():
	# get the json content
	data = request.json
	# extract the message from the request
	lote = data["params"]["id_lote"]
	tipo = data["params"]["tipo_medicion"]
	cliente = data["params"]["id_cliente"]
	cantidad = data["params"]["cantidad"]
	print("Recibido: ")
	print("\tlote:",lote)
	print("\ttipo:",tipo)
	print("\tcliente:",cliente)
	print("\tcantidad:",cantidad)
	# get is to read data
	if request.method == 'POST':
		return jsonify({'answer': main_function(lote, tipo, cliente, cantidad)})

# run the app, on localhost only
app.run(port=3142, host="0.0.0.0", debug=True)


