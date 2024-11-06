-- todo aquí está en psql

-- fase 1
CREATE TABLE rol (
    id_rol SERIAL PRIMARY KEY,
    rol TEXT NOT NULL,
    color TEXT NOT NULL
);

CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    contra TEXT NOT NULL,
    id_rol INT,
    correo TEXT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);

-- fase 2

CREATE TABLE parametro (
    id_parametro SERIAL PRIMARY KEY,
    parametros TEXT NOT NULL
);

CREATE TABLE domicilio (
    id_domicilio SERIAL PRIMARY KEY,
    calle TEXT NOT NULL,
    num_int TEXT,
    num_ext TEXT NOT NULL,
    colonia TEXT NOT NULL,
    codigo_postal VARCHAR(20) NOT NULL,
    ciudad TEXT NOT NULL,
    estado TEXT NOT NULL,
    pais TEXT NOT NULL
);

CREATE TABLE cliente (
    id_cliente SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    correo TEXT NOT NULL,
    f_registro DATE NOT NULL,
    rfc TEXT NOT NULL,
    id_parametro INT,
    id_domicilio_fiscal INT,
    id_domicilio_entrega INT,
    certificado INT,
    contacto TEXT NOT NULL,
    FOREIGN KEY (id_parametro) REFERENCES parametro(id_parametro),
    FOREIGN KEY (id_domicilio_fiscal) REFERENCES domicilio(id_domicilio),
    FOREIGN KEY (id_domicilio_entrega) REFERENCES domicilio(id_domicilio)
);

-- fase 3

CREATE TABLE lote (
    id_lote SERIAL PRIMARY KEY,
    f_produccion DATE,
    f_caducidad DATE GENERATED ALWAYS AS (f_produccion + INTERVAL '6 months') STORED,
    cantidad INT,
    notas TEXT
);

CREATE TABLE certificado (
    id_certificado SERIAL PRIMARY KEY,
    id_lote INT,
    url TEXT NOT NULL,
    fecha_creacion DATE,
    FOREIGN KEY (id_lote) REFERENCES lote(id_lote)
);

CREATE TABLE pedido (
    id_pedido SERIAL PRIMARY KEY,
    c_solicitada INT NOT NULL,
    c_entrega INT NOT NULL,
    factura INT NOT NULL,
    f_pedido TEXT NOT NULL,
    f_entrega TEXT,
    id_cliente INT NOT NULL,
    id_lote INT NOT NULL,
    FOREIGN KEY (id_lote) REFERENCES lote(id_lote),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);

CREATE TABLE proveedor (
    id_proveedor SERIAL PRIMARY KEY,
    nombre TEXT NOT NULL,
    correo TEXT NOT NULL,
    sitio_web TEXT,
    f_inicio_relacion TEXT NOT NULL,
    estado TEXT NOT NULL
);

CREATE TABLE tipo (
    id_tipo SERIAL PRIMARY KEY,
    tipo TEXT NOT NULL
);

CREATE TABLE equipo (
    id_equipo SERIAL PRIMARY KEY,
    des_larga VARCHAR(300) NOT NULL,
    des_corta TEXT NOT NULL,
    marca TEXT NOT NULL,
    modelo TEXT NOT NULL,
    serie TEXT NOT NULL,
    id_tipo INT,
    id_proveedor INT,
    f_adquisicion TEXT NOT NULL,
    garantia_tipo TEXT NOT NULL,
    estado TEXT NOT NULL,
    ubicacion TEXT NOT NULL,
    garantia_vigencia TEXT NOT NULL,
    FOREIGN KEY (id_tipo) REFERENCES tipo(id_tipo),
    FOREIGN KEY (id_proveedor) REFERENCES proveedor(id_proveedor)
);

CREATE TABLE medicion (
    id_medicion SERIAL PRIMARY KEY,
    id_lote INT NOT NULL,
    id_equipo INT NOT NULL,
    id_usuario INT NOT NULL,
    mediciones TEXT NOT NULL,
    fecha DATE NOT NULL,
    FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_lote) REFERENCES lote(id_lote)
);