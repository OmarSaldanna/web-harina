-- todo aquí está en psql

insert into rol(rol, color) values ('Administrador', 'red darken-2');
insert into rol(rol, color) values ('Gerencia de calidad', 'pink darken-2');
insert into rol(rol, color) values ('Director de operaciones', 'indigo darken-2');
insert into rol(rol, color) values ('Gerente de planta', 'cyan darken-2');
insert into rol(rol, color) values ('Gerencia de laboratorio', 'grey darken-3');
insert into rol(rol, color) values ('Ventas', 'amber darken-4');

insert into usuario(nombre, contra, id_rol, correo) values ('Juan Álvarez', 'jual', 1, 'juanal@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Luis Torres', 'luto', 2, 'luis.to@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Ana Ruiz', 'anru', 3, 'ana.ru@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Pedro Gómez', 'pego', 4, 'pedro.go@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Sofía Loren', 'solo', 5, 'sofia.lo@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Carlos Paz', 'capa', 6, 'carlos.pa@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Marta Diaz', 'madi', 2, 'marta.di@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Jorge Núñez', 'jonu', 3, 'jorge.nu@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Lucía Solís', 'luso', 4, 'lucia.so@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Manuel Rojas', 'maro', 5, 'manuel.ro@harina.mx');
INSERT INTO usuario(nombre, contra, id_rol, correo) VALUES ('Fernanda Lima', 'feli', 6, 'fernanda.li@harina.mx');

-- fase 2
insert into parametro(parametros) values ('');
insert into parametro(parametros) values ('{
  "A": {
    "Tenacidad a Extensibilidad (P/L)": [0.4,1.1],
    "Tenacidad (P)": [50,70],
    "Extensibilidad (L)": [80,180],
    "Índice de Elasticidad": [0.8,1.3],
    "Energía de Deformación": [90,150]
  },
  "F": {
    "Absorción de agua": [54,64],
    "Tiempo de desarrollo": [1.5,8],
    "Estabilidad": [4,15],
    "Debilitamiento": [20,200],
    "Índice de calidad": [60,100]
  }
}');

INSERT INTO domicilio (calle, num_ext, colonia, codigo_postal, ciudad, estado, pais)
VALUES
('Av. Insurgentes Sur', '1458', 'Del Valle Centro', '03100', 'Ciudad de México', 'CDMX', 'México'),
('Eje Central', '875', 'Doctores', '06720', 'Ciudad de México', 'CDMX', 'México'),
('Paseo de la Reforma', '305', 'Cuauhtémoc', '06500', 'Ciudad de México', 'CDMX', 'México'),
('Calzada de Tlalpan', '498', 'Nativitas', '03500', 'Ciudad de México', 'CDMX', 'México');

INSERT INTO cliente (nombre, correo, f_registro, rfc, id_domicilio_fiscal, id_domicilio_entrega, certificado, contacto)
VALUES
('Juan Pérez', 'juan.perez@example.com', '2023-01-15', 'JUAP850216HDF', 1, 1, 1, '5551234567'),
('María López', 'maria.lopez@example.com', '2023-02-20', 'MALO890322MJT', 2, 3, 1, '5557654321'),
('Carlos Martínez', 'carlos.mtz@example.com', '2023-03-05', 'CAMM900405HPL', 3, 3, 1, '5554567890'),
('Ana Fernández', 'ana.fernandez@example.com', '2023-04-10', 'ANAF940510TMN', 4, 2, 1, '5553216548');

insert into tipo (tipo) values ('A'), ('F');

INSERT INTO lote (f_produccion, cantidad, notas)
VALUES
('2024-01-01', 1000, 'Producción inicial del año, harina de trigo estándar.'),
('2024-02-15', 1500, 'Producción de harina integral, con granos seleccionados.'),
('2024-03-20', 1200, 'Harina de trigo fortificada para uso en panadería y repostería.'),
('2024-04-25', 1800, 'Producción especial de harina de trigo para exportación.');


INSERT INTO proveedor (id_proveedor, nombre, correo, sitio_web, f_inicio_relacion, estado) VALUES (1, 'Martinez PLC', 'erictyler@valentine-pennington.biz', 'https://dalton-johnson.org/', '2009-08-11 03:58:56', 'Idaho');
INSERT INTO proveedor (id_proveedor, nombre, correo, sitio_web, f_inicio_relacion, estado) VALUES (2, 'Stevens, Anderson and Jacobs', 'awilliams@davis.org', 'http://simmons.com/', '2011-11-19 20:28:59', 'Idaho');
INSERT INTO proveedor (id_proveedor, nombre, correo, sitio_web, f_inicio_relacion, estado) VALUES (3, 'Acosta-Bush', 'seanfarrell@gordon-henry.com', 'http://stark-walton.com/', '2018-09-19 23:15:40', 'Wisconsin');
INSERT INTO proveedor (id_proveedor, nombre, correo, sitio_web, f_inicio_relacion, estado) VALUES (4, 'Ball, Chase and Jones', 'charlene83@harris.org', 'http://www.rose.com/', '2021-07-29 15:24:05', 'Delaware');
INSERT INTO proveedor (id_proveedor, nombre, correo, sitio_web, f_inicio_relacion, estado) VALUES (5, 'Fuentes-Clay', 'kim67@patterson-guzman.biz', 'https://hancock-bennett.net/', '2000-02-11 13:46:30', 'Missouri');


INSERT INTO equipo (des_larga, des_corta, marca, modelo, serie, id_tipo, id_proveedor, f_adquisicion, garantia_tipo, estado, ubicacion, garantia_vigencia) VALUES ('El alveógrafo es un instrumento utilizado para analizar las propiedades de extensibilidad y elasticidad de la masa de harina, inflando muestras de masa hasta su ruptura.', 'Evalúa elasticidad y extensibilidad de la masa.', 'Burgess-Smith', 'Model-SBB873', 'XQb-282-083', 1, 1, '2017-02-06', 'Extended', 'Nuevo', 'Sucursal Polanco', '2026-08-31');
INSERT INTO equipo (des_larga, des_corta, marca, modelo, serie, id_tipo, id_proveedor, f_adquisicion, garantia_tipo, estado, ubicacion, garantia_vigencia) VALUES ('El farinógrafo es un equipo clave en panadería y molinería, utilizado para evaluar la calidad de la harina al medir su absorción de agua y las características de amasado.', 'Instrumento que mide propiedades de amasado de la harina.', 'Burgess-Smith', 'Model-yYJ484', 'wNl-959-932', 2, 1, '2010-12-09', 'Standard', 'Usado', 'Sucursal Polanco', '2024-11-19');

INSERT INTO medicion (id_usuario, id_lote, id_equipo, mediciones, fecha)
VALUES
(5,1,1, '{
    "Tenacidad a Extensibilidad (P/L)": 0.5,
    "Tenacidad (P)": 60,
    "Extensibilidad (L)": 180,
    "Índice de Elasticidad": 0.8,
    "Energía de Deformación": 100
  }', '2023-02-10'),
(5,1,2, '{
    "Absorción de agua": 64,
    "Tiempo de desarrollo": 4,
    "Estabilidad": 3,
    "Debilitamiento": 100,
    "Índice de calidad": 100
}', '2023-02-10');