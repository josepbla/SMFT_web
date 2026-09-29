CREATE DATABASE IF NOT EXISTS `colegio_santa_maria` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `colegio_santa_maria`;

DROP TABLE IF EXISTS `mensajes_contacto`;
DROP TABLE IF EXISTS `reclamaciones`;
DROP TABLE IF EXISTS `solicitudes_ingreso`;
DROP TABLE IF EXISTS `exalumnos`;
DROP TABLE IF EXISTS `galeria_items`;
DROP TABLE IF EXISTS `galerias`;
DROP TABLE IF EXISTS `enlaces`;
DROP TABLE IF EXISTS `personal`;
DROP TABLE IF EXISTS `documentos`;
DROP TABLE IF EXISTS `comunicados`;
DROP TABLE IF EXISTS `noticias`;
DROP TABLE IF EXISTS `categorias_noticias`;
DROP TABLE IF EXISTS `niveles`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `paginas`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `configuracion`;
DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id_usuario` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombres` varchar(80) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('administrador','editor') NOT NULL DEFAULT 'editor',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `ultimo_acceso` datetime DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuarios_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `configuracion` (
  `clave` varchar(50) NOT NULL,
  `valor` text DEFAULT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `menus` (
  `id_menu` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_padre` int UNSIGNED DEFAULT NULL,
  `titulo` varchar(60) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `icono` varchar(255) DEFAULT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_menu`),
  UNIQUE KEY `menus_slug_unique` (`slug`),
  KEY `menus_id_padre_foreign` (`id_padre`),
  CONSTRAINT `menus_id_padre_foreign` FOREIGN KEY (`id_padre`) REFERENCES `menus` (`id_menu`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `paginas` (
  `id_pagina` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_menu` int UNSIGNED DEFAULT NULL,
  `titulo` varchar(150) NOT NULL,
  `slug` varchar(160) NOT NULL,
  `contenido` mediumtext DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `publicado` tinyint(1) NOT NULL DEFAULT 1,
  `actualizado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pagina`),
  UNIQUE KEY `paginas_slug_unique` (`slug`),
  KEY `paginas_id_menu_foreign` (`id_menu`),
  CONSTRAINT `paginas_id_menu_foreign` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `banners` (
  `id_banner` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` varchar(120) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_banner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `niveles` (
  `id_nivel` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `orden` tinyint UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_nivel`),
  UNIQUE KEY `niveles_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categorias_noticias` (
  `id_categoria` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `slug` varchar(60) NOT NULL,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `categorias_noticias_nombre_unique` (`nombre`),
  UNIQUE KEY `categorias_noticias_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `noticias` (
  `id_noticia` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_categoria` smallint UNSIGNED DEFAULT NULL,
  `id_usuario` int UNSIGNED DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `resumen` varchar(400) DEFAULT NULL,
  `contenido` mediumtext NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `destacada` tinyint(1) NOT NULL DEFAULT 0,
  `publicado` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_publicacion` datetime DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_noticia`),
  UNIQUE KEY `noticias_slug_unique` (`slug`),
  KEY `noticias_id_categoria_foreign` (`id_categoria`),
  KEY `noticias_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `noticias_id_categoria_foreign` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_noticias` (`id_categoria`) ON DELETE SET NULL,
  CONSTRAINT `noticias_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comunicados` (
  `id_comunicado` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `contenido` text DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `fecha` date NOT NULL,
  `publicado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_comunicado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `documentos` (
  `id_documento` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `archivo` varchar(255) NOT NULL,
  `publicado` tinyint(1) NOT NULL DEFAULT 1,
  `subido_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personal` (
  `id_personal` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombres` varchar(80) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `cargo` varchar(80) DEFAULT NULL,
  `area` enum('directivo','docente','administrativo','apoyo') NOT NULL DEFAULT 'docente',
  `id_nivel` tinyint UNSIGNED DEFAULT NULL,
  `especialidad` varchar(80) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `orden` smallint UNSIGNED NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_personal`),
  KEY `personal_id_nivel_foreign` (`id_nivel`),
  CONSTRAINT `personal_id_nivel_foreign` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `enlaces` (
  `id_enlace` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `url` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `tipo` enum('institucional','interes','red_social') NOT NULL DEFAULT 'institucional',
  `orden` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_enlace`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `galerias` (
  `id_galeria` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` varchar(120) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `publicado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_galeria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `galeria_items` (
  `id_item` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_galeria` int UNSIGNED NOT NULL,
  `tipo` enum('imagen','video') NOT NULL DEFAULT 'imagen',
  `ruta` varchar(255) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `galeria_items_id_galeria_foreign` (`id_galeria`),
  CONSTRAINT `galeria_items_id_galeria_foreign` FOREIGN KEY (`id_galeria`) REFERENCES `galerias` (`id_galeria`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `exalumnos` (
  `id_exalumno` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombres` varchar(80) NOT NULL,
  `apellidos` varchar(80) NOT NULL,
  `promocion` year NOT NULL,
  `profesion` varchar(100) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `testimonio` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `aprobado` tinyint(1) NOT NULL DEFAULT 0,
  `registrado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_exalumno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `solicitudes_ingreso` (
  `id_solicitud` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombres_alumno` varchar(80) NOT NULL,
  `apellidos_alumno` varchar(80) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `id_nivel` tinyint UNSIGNED DEFAULT NULL,
  `grado` varchar(30) DEFAULT NULL,
  `apoderado` varchar(120) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `estado` enum('pendiente','contactado','aceptado','rechazado') NOT NULL DEFAULT 'pendiente',
  `enviado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_solicitud`),
  KEY `solicitudes_ingreso_id_nivel_foreign` (`id_nivel`),
  CONSTRAINT `solicitudes_ingreso_id_nivel_foreign` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reclamaciones` (
  `id_reclamo` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `nombres` varchar(120) NOT NULL,
  `documento_tipo` enum('DNI','CE','Pasaporte') NOT NULL DEFAULT 'DNI',
  `documento_num` varchar(15) NOT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `es_menor` tinyint(1) NOT NULL DEFAULT 0,
  `apoderado` varchar(120) DEFAULT NULL,
  `tipo` enum('reclamo','queja') NOT NULL,
  `detalle` text NOT NULL,
  `pedido` text DEFAULT NULL,
  `estado` enum('recibido','en_proceso','atendido') NOT NULL DEFAULT 'recibido',
  `respuesta` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_respuesta` datetime DEFAULT NULL,
  PRIMARY KEY (`id_reclamo`),
  UNIQUE KEY `reclamaciones_codigo_unique` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mensajes_contacto` (
  `id_mensaje` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT 0,
  `enviado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mensaje`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
