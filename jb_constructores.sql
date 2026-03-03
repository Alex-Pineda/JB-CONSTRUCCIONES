-- MySQL dump 10.13  Distrib 9.0.1, for Win64 (x86_64)
--
-- Host: localhost    Database: jb_constructores
-- ------------------------------------------------------
-- Server version	9.0.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog` (
  `idblog` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `resumen` text,
  `tipo_contenido` enum('video','articulo','mixto') NOT NULL DEFAULT 'video',
  `youtube_id` varchar(20) DEFAULT NULL,
  `categoria_id` int NOT NULL,
  `estado` enum('borrador','publicado','inactivo') NOT NULL DEFAULT 'borrador',
  `publicado_en` timestamp NULL DEFAULT NULL,
  `usuario_id` int NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idblog`),
  KEY `fk_blog_categoria` (`categoria_id`),
  KEY `fk_blog_usuario` (`usuario_id`),
  CONSTRAINT `fk_blog_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`idcategoria`) ON DELETE RESTRICT,
  CONSTRAINT `fk_blog_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_contenido`
--

DROP TABLE IF EXISTS `blog_contenido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_contenido` (
  `idcontenido` int NOT NULL AUTO_INCREMENT,
  `blog_id` int NOT NULL,
  `tipo` enum('texto','imagen','video') NOT NULL,
  `contenido` text NOT NULL,
  `orden` int NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcontenido`),
  KEY `fk_contenido_blog` (`blog_id`),
  CONSTRAINT `fk_contenido_blog` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`idblog`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_contenido`
--

LOCK TABLES `blog_contenido` WRITE;
/*!40000 ALTER TABLE `blog_contenido` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_contenido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `idcategoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizacion`
--

DROP TABLE IF EXISTS `cotizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizacion` (
  `idcotizacion` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `tipo_documento` varchar(30) DEFAULT NULL,
  `numero_documento` varchar(30) DEFAULT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `contacto` varchar(30) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `ser_contactado` tinyint(1) DEFAULT '0',
  `fecha_visita` datetime DEFAULT NULL,
  `total_estimado` decimal(12,2) DEFAULT NULL,
  `fecha_cotizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_idusuario` int DEFAULT NULL,
  PRIMARY KEY (`idcotizacion`),
  KEY `fk_cot_usuario` (`usuario_idusuario`),
  CONSTRAINT `fk_cot_usuario` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizacion`
--

LOCK TABLES `cotizacion` WRITE;
/*!40000 ALTER TABLE `cotizacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `cotizacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizacion_servicio`
--

DROP TABLE IF EXISTS `cotizacion_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizacion_servicio` (
  `idcotizacion_servicio` int NOT NULL AUTO_INCREMENT,
  `cotizacion_idcotizacion` int NOT NULL,
  `servicio_idservicio` int NOT NULL,
  `metros` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`idcotizacion_servicio`),
  KEY `fk_cs_cotizacion` (`cotizacion_idcotizacion`),
  KEY `fk_cs_servicio` (`servicio_idservicio`),
  CONSTRAINT `fk_cs_cotizacion` FOREIGN KEY (`cotizacion_idcotizacion`) REFERENCES `cotizacion` (`idcotizacion`),
  CONSTRAINT `fk_cs_servicio` FOREIGN KEY (`servicio_idservicio`) REFERENCES `servicio` (`idservicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizacion_servicio`
--

LOCK TABLES `cotizacion_servicio` WRITE;
/*!40000 ALTER TABLE `cotizacion_servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `cotizacion_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `iddocumento` int NOT NULL AUTO_INCREMENT,
  `proyecto_id` int DEFAULT NULL,
  `tipo_documento` enum('contrato','factura','comprobante_pago','cotizacion','otro') NOT NULL,
  `nombre_archivo` varchar(150) DEFAULT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `referencia_id` int DEFAULT NULL COMMENT 'id de pago, cotizacion u otro',
  `fecha_subida` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`iddocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagen`
--

DROP TABLE IF EXISTS `imagen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagen` (
  `idimagen` int NOT NULL AUTO_INCREMENT,
  `entidad` enum('blog','portafolio','servicio','proyecto','progreso_obra') NOT NULL,
  `entidad_id` int NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `orden` int DEFAULT '1',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idimagen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagen`
--

LOCK TABLES `imagen` WRITE;
/*!40000 ALTER TABLE `imagen` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago`
--

DROP TABLE IF EXISTS `pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago` (
  `idpago` int NOT NULL AUTO_INCREMENT,
  `proyecto_id` int NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `tipo_pago` enum('anticipo','final') NOT NULL,
  `medio_pago` enum('nequi','bancolombia','transferencia','efectivo','otro') NOT NULL,
  `referencia_pago` varchar(100) DEFAULT NULL,
  `comprobante_url` varchar(255) DEFAULT NULL,
  `estado_pago` enum('pendiente','verificado','rechazado') DEFAULT 'pendiente',
  `porcentaje_aporte` decimal(5,2) NOT NULL,
  `fecha_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idpago`),
  KEY `proyecto_id` (`proyecto_id`),
  CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyecto` (`idproyecto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portafolio`
--

DROP TABLE IF EXISTS `portafolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portafolio` (
  `idportafolio` int NOT NULL AUTO_INCREMENT,
  `proyecto_id` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('borrador','publicado','oculto') NOT NULL DEFAULT 'borrador',
  `categoria_id` int NOT NULL,
  `servicio_id` int NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idportafolio`),
  KEY `fk_portafolio_proyecto` (`proyecto_id`),
  KEY `fk_portafolio_categoria` (`categoria_id`),
  KEY `fk_portafolio_servicio` (`servicio_id`),
  CONSTRAINT `fk_portafolio_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`idcategoria`) ON DELETE RESTRICT,
  CONSTRAINT `fk_portafolio_proyecto` FOREIGN KEY (`proyecto_id`) REFERENCES `proyecto` (`idproyecto`) ON DELETE RESTRICT,
  CONSTRAINT `fk_portafolio_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicio` (`idservicio`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portafolio`
--

LOCK TABLES `portafolio` WRITE;
/*!40000 ALTER TABLE `portafolio` DISABLE KEYS */;
/*!40000 ALTER TABLE `portafolio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progreso_obra`
--

DROP TABLE IF EXISTS `progreso_obra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progreso_obra` (
  `idprogreso` int NOT NULL AUTO_INCREMENT,
  `proyecto_id` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `youtube_video_id` varchar(20) NOT NULL,
  `tipo_video` enum('grabado','transmision') NOT NULL,
  `estado` enum('publicado','oculto') DEFAULT 'publicado',
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL,
  `porcentaje_aporte` decimal(5,2) NOT NULL COMMENT 'Porcentaje que aporta este avance',
  `aprobado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Aprobado por administrador',
  `usuario_id` int NOT NULL COMMENT 'Qui├®n publica el avance',
  PRIMARY KEY (`idprogreso`),
  KEY `fk_progreso_obra_proyecto` (`proyecto_id`),
  CONSTRAINT `fk_progreso_obra_proyecto` FOREIGN KEY (`proyecto_id`) REFERENCES `proyecto` (`idproyecto`) ON DELETE CASCADE,
  CONSTRAINT `progreso_obra_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyecto` (`idproyecto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progreso_obra`
--

LOCK TABLES `progreso_obra` WRITE;
/*!40000 ALTER TABLE `progreso_obra` DISABLE KEYS */;
/*!40000 ALTER TABLE `progreso_obra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyecto`
--

DROP TABLE IF EXISTS `proyecto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyecto` (
  `idproyecto` int NOT NULL AUTO_INCREMENT,
  `nombre_proyecto` varchar(60) NOT NULL,
  `descripcion` text NOT NULL,
  `ubicacion` varchar(70) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin_estimada` date DEFAULT NULL,
  `estado_proyecto` enum('pendiente','ejecucion','pausado','finalizado','cancelado') DEFAULT 'pendiente',
  `estado_contrato` enum('pendiente_firma','firmado','cancelado') DEFAULT 'pendiente_firma',
  `usuario_id` int DEFAULT NULL,
  `cotizacion_id` int DEFAULT NULL,
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `porcentaje_avance` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Avance total de la obra (0ÔÇô100)',
  `porcentaje_pagado` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Porcentaje pagado del total',
  PRIMARY KEY (`idproyecto`),
  KEY `fk_proyecto_usuario` (`usuario_id`),
  KEY `fk_proyecto_cotizacion` (`cotizacion_id`),
  CONSTRAINT `fk_proyecto_cotizacion` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizacion` (`idcotizacion`),
  CONSTRAINT `fk_proyecto_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyecto`
--

LOCK TABLES `proyecto` WRITE;
/*!40000 ALTER TABLE `proyecto` DISABLE KEYS */;
/*!40000 ALTER TABLE `proyecto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resena`
--

DROP TABLE IF EXISTS `resena`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resena` (
  `idresena` int NOT NULL AUTO_INCREMENT,
  `idusuario` int DEFAULT NULL,
  `idproyecto` int DEFAULT NULL,
  `calificacion` tinyint NOT NULL,
  `comentario` text NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `moderado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idresena`),
  KEY `fk_resena_usuario` (`idusuario`),
  KEY `fk_resena_proyecto` (`idproyecto`),
  CONSTRAINT `fk_resena_proyecto` FOREIGN KEY (`idproyecto`) REFERENCES `proyecto` (`idproyecto`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_resena_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resena`
--

LOCK TABLES `resena` WRITE;
/*!40000 ALTER TABLE `resena` DISABLE KEYS */;
/*!40000 ALTER TABLE `resena` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `idrol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicio` (
  `idservicio` int NOT NULL AUTO_INCREMENT,
  `idcategoria` int NOT NULL,
  `nombre_servicio` varchar(80) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idservicio`),
  KEY `fk_servicio_categoria` (`idcategoria`),
  CONSTRAINT `fk_servicio_categoria` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`idcategoria`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `idusuario` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `tipo_documento` varchar(30) NOT NULL,
  `numero_documento` varchar(30) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `nombre_usuario` varchar(40) NOT NULL,
  `hash_password` varchar(255) NOT NULL,
  `acepta_terminos` tinyint(1) NOT NULL,
  `estado` enum('activo','bloqueado','inactivo') NOT NULL DEFAULT 'activo',
  `creado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `numero_documento` (`numero_documento`),
  UNIQUE KEY `correo` (`correo`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_has_rol`
--

DROP TABLE IF EXISTS `usuario_has_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_has_rol` (
  `usuario_idusuario` int NOT NULL,
  `rol_idrol` int NOT NULL,
  PRIMARY KEY (`usuario_idusuario`,`rol_idrol`),
  KEY `fk_uhr_rol` (`rol_idrol`),
  CONSTRAINT `fk_uhr_rol` FOREIGN KEY (`rol_idrol`) REFERENCES `rol` (`idrol`),
  CONSTRAINT `fk_uhr_usuario` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_has_rol`
--

LOCK TABLES `usuario_has_rol` WRITE;
/*!40000 ALTER TABLE `usuario_has_rol` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_has_rol` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-27 23:26:05
