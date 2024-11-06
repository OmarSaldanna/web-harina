-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 28, 2024 at 12:20 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `empresa`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificado`
--

CREATE TABLE `certificado` (
  `id_certificado` int(11) NOT NULL,
  `id_lote` int(11) DEFAULT NULL,
  `url` varchar(512) NOT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `f_registro` date NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `id_parametro` int(11) DEFAULT NULL,
  `id_domicilio_fiscal` int(11) DEFAULT NULL,
  `id_domicilio_entrega` int(11) DEFAULT NULL,
  `certificado` int(11) DEFAULT NULL,
  `contacto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `correo`, `f_registro`, `rfc`, `id_parametro`, `id_domicilio_fiscal`, `id_domicilio_entrega`, `certificado`, `contacto`) VALUES
(1, 'Juan Pérez', 'juan.perez@example.com', '2023-01-15', 'JUAP850216HDF', NULL, 1, 1, 1, '5551234567'),
(2, 'María López', 'maria.lopez@example.com', '2023-02-20', 'MALO890322MJT', NULL, 2, 3, 1, '5557654321'),
(3, 'Carlos Martínez', 'carlos.mtz@example.com', '2023-03-05', 'CAMM900405HPL', NULL, 3, 3, 1, '5554567890'),
(4, 'Ana Fernández', 'ana.fernandez@example.com', '2023-04-10', 'ANAF940510TMN', NULL, 4, 2, 1, '5553216548');

-- --------------------------------------------------------

--
-- Table structure for table `domicilio`
--

CREATE TABLE `domicilio` (
  `id_domicilio` int(11) NOT NULL,
  `calle` varchar(100) NOT NULL,
  `num_int` varchar(10) DEFAULT NULL,
  `num_ext` varchar(10) NOT NULL,
  `colonia` varchar(100) NOT NULL,
  `codigo_postal` varchar(20) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `estado` varchar(10) NOT NULL,
  `pais` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `domicilio`
--

INSERT INTO `domicilio` (`id_domicilio`, `calle`, `num_int`, `num_ext`, `colonia`, `codigo_postal`, `ciudad`, `estado`, `pais`) VALUES
(1, 'Av. Insurgentes Sur', NULL, '1458', 'Del Valle Centro', '03100', 'Ciudad de México', 'CDMX', 'México'),
(2, 'Eje Central', NULL, '875', 'Doctores', '06720', 'Ciudad de México', 'CDMX', 'México'),
(3, 'Paseo de la Reforma', NULL, '305', 'Cuauhtémoc', '06500', 'Ciudad de México', 'CDMX', 'México'),
(4, 'Calzada de Tlalpan', NULL, '498', 'Nativitas', '03500', 'Ciudad de México', 'CDMX', 'México');

-- --------------------------------------------------------

--
-- Table structure for table `equipo`
--

CREATE TABLE `equipo` (
  `id_equipo` int(11) NOT NULL,
  `des_larga` varchar(300) NOT NULL,
  `des_corta` varchar(100) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `f_adquisicion` varchar(100) NOT NULL,
  `garantia_tipo` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `ubicacion` varchar(100) NOT NULL,
  `garantia_vigencia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `equipo`
--

INSERT INTO `equipo` (`id_equipo`, `des_larga`, `des_corta`, `marca`, `modelo`, `serie`, `id_tipo`, `id_proveedor`, `f_adquisicion`, `garantia_tipo`, `estado`, `ubicacion`, `garantia_vigencia`) VALUES
(1, 'El alveógrafo es un instrumento utilizado para analizar las propiedades de extensibilidad y elasticidad de la masa de harina, inflando muestras de masa hasta su ruptura.', 'Evalúa elasticidad y extensibilidad de la masa.', 'Burgess-Smith', 'ALBE-102929', 'XQb-282-083', 1, 1, '2017-02-06', 'Extended', 'Nuevo', 'Sucursal Polanco', '2026-08-31'),
(2, 'El alveógrafo es un instrumento utilizado para analizar las propiedades de extensibilidad y elasticidad de la masa de harina, inflando muestras de masa hasta su ruptura.', 'Evalúa elasticidad y extensibilidad de la masa.', 'Burgess-Smith', 'FARI-10203', 'XQb-282-083', 2, 1, '2017-02-06', 'Extended', 'Nuevo', 'Sucursal Polanco', '2026-08-31'),
(4, 'El farinógrafo es un equipo clave en panadería y molinería, utilizado para evaluar la calidad de la harina al medir su absorción de agua y las características de amasado.', 'Instrumento que mide propiedades de amasado de la harina.', 'Burgess-Smith', 'Model-yYJ484', 'wNl-959-932', 2, 1, '2010-12-09', 'Standard', 'Usado', 'Sucursal Polanco', '2024-11-19');

-- --------------------------------------------------------

--
-- Table structure for table `lote`
--

CREATE TABLE `lote` (
  `id_lote` int(11) NOT NULL,
  `f_produccion` date DEFAULT NULL,
  `f_caducidad` date GENERATED ALWAYS AS ((`f_produccion` + interval 6 month)) VIRTUAL,
  `cantidad` int(11) DEFAULT NULL,
  `notas` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lote`
--

INSERT INTO `lote` (`id_lote`, `f_produccion`, `cantidad`, `notas`) VALUES
(1, '2024-01-01', 1000, 'Producción inicial del año, harina de trigo estándar.'),
(2, '2024-02-15', 1500, 'Producción de harina integral, con granos seleccionados.'),
(3, '2024-03-20', 1200, 'Harina de trigo fortificada para uso en panadería y repostería.'),
(4, '2024-04-25', 1800, 'Producción especial de harina de trigo para exportación.');

-- --------------------------------------------------------

--
-- Table structure for table `medicion`
--

CREATE TABLE `medicion` (
  `id_medicion` int(11) NOT NULL,
  `id_lote` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `mediciones` varchar(300) NOT NULL,
  `fecha` date NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medicion`
--

INSERT INTO `medicion` (`id_medicion`, `id_lote`, `id_equipo`, `mediciones`, `fecha`, `id_usuario`) VALUES
(1, 1, 1, '{\r\n    \"Tenacidad a Extensibilidad (P/L)\": 0.5,\r\n    \"Tenacidad (P)\": 60,\r\n    \"Extensibilidad (L)\": 180,\r\n    \"Índice de Elasticidad\": 0.8,\r\n    \"Energía de Deformación\": 100\r\n  }', '2023-02-10', 12),
(2, 1, 2, '{\r\n    \"Absorción de agua\": 64,\r\n    \"Tiempo de desarrollo\": 4,\r\n    \"Estabilidad\": 3,\r\n    \"Debilitamiento\": 100,\r\n    \"Índice de calidad\": 100\r\n}', '2023-02-10', 12);

-- --------------------------------------------------------

--
-- Table structure for table `parametro`
--

CREATE TABLE `parametro` (
  `id_parametro` int(11) NOT NULL,
  `parametros` varchar(600) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parametro`
--

INSERT INTO `parametro` (`id_parametro`, `parametros`) VALUES
(1, ''),
(2, '{\n  \"A\": {\n    \"Tenacidad a Extensibilidad (P/L)\": [0.4,1.1],\n    \"Tenacidad (P)\": [50,70],\n    \"Extensibilidad (L)\": [80,180],\n    \"Índice de Elasticidad\": [0.8,1.3],\n    \"Energía de Deformación\": [90,150]\n  },\n  \"F\": {\n    \"Absorción de agua\": [54,64],\n    \"Tiempo de desarrollo\": [1.5,8],\n    \"Estabilidad\": [4,15],\n    \"Debilitamiento\": [20,200],\n    \"Índice de calidad\": [60,100]\n  }\n}');

-- --------------------------------------------------------

--
-- Table structure for table `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `c_solicitada` int(11) NOT NULL,
  `c_entrega` int(11) NOT NULL,
  `factura` int(11) NOT NULL,
  `f_pedido` varchar(100) NOT NULL,
  `f_entrega` varchar(100) DEFAULT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_lote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `sitio_web` varchar(200) DEFAULT NULL,
  `f_inicio_relacion` varchar(100) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre`, `correo`, `sitio_web`, `f_inicio_relacion`, `estado`) VALUES
(1, 'Martinez PLC', 'erictyler@valentine-pennington.biz', 'https://dalton-johnson.org/', '2009-08-11', 'Querétaro'),
(2, 'Stevens, Anderson and Jacobs', 'awilliams@davis.org', 'http://simmons.com/', '2011-11-19', 'Nuevo León'),
(3, 'Acosta-Bush', 'seanfarrell@gordon-henry.com', 'http://stark-walton.com/', '2018-09-19', 'Querétaro'),
(4, 'Ball, Chase and Jones', 'charlene83@harris.org', 'http://www.rose.com/', '2021-07-29', 'Nuevo León'),
(5, 'Fuentes-Clay', 'kim67@patterson-guzman.biz', 'https://hancock-bennett.net/', '2000-02-11', 'Querétaro');

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`, `color`) VALUES
(1, 'Administrador', 'red darken-2'),
(2, 'Gerencia de calidad', 'pink darken-2'),
(3, 'Director de operaciones', 'indigo darken-2'),
(4, 'Gerente de planta', 'cyan darken-2'),
(5, 'Gerencia de laboratorio', 'grey darken-3'),
(6, 'Ventas', 'amber darken-4');

-- --------------------------------------------------------

--
-- Table structure for table `tipo`
--

CREATE TABLE `tipo` (
  `id_tipo` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tipo`
--

INSERT INTO `tipo` (`id_tipo`, `tipo`) VALUES
(1, 'A'),
(2, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contra` varchar(32) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `contra`, `id_rol`, `correo`) VALUES
(1, 'Juan Álvarez', 'jual', 1, 'juanal@harina.mx'),
(2, 'Luis Torres', 'luto', 2, 'luis.to@harina.mx'),
(3, 'Ana Ruiz', 'anru', 3, 'ana.ru@harina.mx'),
(4, 'Pedro Gómez', 'pego', 4, 'pedro.go@harina.mx'),
(5, 'Sofía Loren', 'solo', 5, 'sofia.lo@harina.mx'),
(6, 'Carlos Paz', 'capa', 6, 'carlos.pa@harina.mx'),
(7, 'Marta Diaz', 'madi', 2, 'marta.di@harina.mx'),
(8, 'Jorge Núñez', 'jonu', 3, 'jorge.nu@harina.mx'),
(9, 'Lucía Solís', 'luso', 4, 'lucia.so@harina.mx'),
(10, 'Manuel Rojas', 'maro', 5, 'manuel.ro@harina.mx'),
(11, 'Fernanda Lima', 'feli', 6, 'fernanda.li@harina.mx'),
(12, 'Omar', 'pass', 5, 'omarla@harina.mx');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificado`
--
ALTER TABLE `certificado`
  ADD PRIMARY KEY (`id_certificado`),
  ADD KEY `id_lote` (`id_lote`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_parametro` (`id_parametro`),
  ADD KEY `id_domicilio_fiscal` (`id_domicilio_fiscal`),
  ADD KEY `id_domicilio_entrega` (`id_domicilio_entrega`);

--
-- Indexes for table `domicilio`
--
ALTER TABLE `domicilio`
  ADD PRIMARY KEY (`id_domicilio`);

--
-- Indexes for table `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `id_tipo` (`id_tipo`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indexes for table `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`id_lote`);

--
-- Indexes for table `medicion`
--
ALTER TABLE `medicion`
  ADD PRIMARY KEY (`id_medicion`),
  ADD KEY `id_equipo` (`id_equipo`),
  ADD KEY `id_lote` (`id_lote`),
  ADD KEY `usuario` (`id_usuario`);

--
-- Indexes for table `parametro`
--
ALTER TABLE `parametro`
  ADD PRIMARY KEY (`id_parametro`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_lote` (`id_lote`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indexes for table `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indexes for table `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indexes for table `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificado`
--
ALTER TABLE `certificado`
  MODIFY `id_certificado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `domicilio`
--
ALTER TABLE `domicilio`
  MODIFY `id_domicilio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lote`
--
ALTER TABLE `lote`
  MODIFY `id_lote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medicion`
--
ALTER TABLE `medicion`
  MODIFY `id_medicion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `parametro`
--
ALTER TABLE `parametro`
  MODIFY `id_parametro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tipo`
--
ALTER TABLE `tipo`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificado`
--
ALTER TABLE `certificado`
  ADD CONSTRAINT `certificado_ibfk_1` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id_lote`);

--
-- Constraints for table `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_parametro`) REFERENCES `parametro` (`id_parametro`),
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_domicilio_fiscal`) REFERENCES `domicilio` (`id_domicilio`),
  ADD CONSTRAINT `cliente_ibfk_3` FOREIGN KEY (`id_domicilio_entrega`) REFERENCES `domicilio` (`id_domicilio`);

--
-- Constraints for table `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `equipo_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo` (`id_tipo`),
  ADD CONSTRAINT `equipo_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Constraints for table `medicion`
--
ALTER TABLE `medicion`
  ADD CONSTRAINT `medicion_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`),
  ADD CONSTRAINT `medicion_ibfk_2` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id_lote`),
  ADD CONSTRAINT `usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Constraints for table `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id_lote`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
