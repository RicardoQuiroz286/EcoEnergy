-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `Ecoblog`;
USE `Ecoblog`;

-- Tabla del administrador
DROP TABLE IF EXISTS `administrador`;
CREATE TABLE `administrador` (
    `idadministrador` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id del administrador',
    `correo` VARCHAR(100) NOT NULL COMMENT 'Correo del administrador',
    `contraseña` VARCHAR(255) NOT NULL COMMENT 'Contraseña',
    PRIMARY KEY (`idadministrador`), 
    UNIQUE KEY `_uncorreo` (`correo`),
    CONSTRAINT `_chcontraseña` CHECK (CHAR_LENGTH(`contraseña`) >= 8)
) ENGINE=InnoDB COMMENT='Tabla del administrador';

-- Tabla de los usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
    `idusuario` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id del usuario',
    `correo` VARCHAR(100) NOT NULL COMMENT 'Correo del usuario',
    `contraseña` VARCHAR(255) NOT NULL COMMENT 'Contraseña',
    PRIMARY KEY (`idusuario`), 
    UNIQUE KEY `_uncorreo` (`correo`),
    CONSTRAINT `_chcontraseña` CHECK (CHAR_LENGTH(`contraseña`) >= 8)
) ENGINE=InnoDB COMMENT='Tabla de los usuarios';

-- Tabla de las noticias
DROP TABLE IF EXISTS `noticias`;
CREATE TABLE `noticias` (
    `idnoticia` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id de la noticia',
    `titulo` VARCHAR(255) NOT NULL COMMENT 'Título de la noticia',
    `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha en la que la noticia fue publicada',
    `imagen` VARCHAR(255) DEFAULT NULL COMMENT 'Ruta de la imagen',
    `informacion` VARCHAR(5000) NOT NULL COMMENT 'Informacion relacionada a la noticia',
    `autor` VARCHAR(255) NOT NULL COMMENT 'Autor de la noticia',
    PRIMARY KEY (`idnoticia`)
) ENGINE=InnoDB COMMENT='Tabla de las noticias';

-- Tabla de comentarios
DROP TABLE IF EXISTS `comentarios`;
CREATE TABLE `comentarios` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `idusuario` INT UNSIGNED NOT NULL,
    `idnoticia` INT UNSIGNED NOT NULL,
    `comentario` TEXT NOT NULL,
    `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_usuario_comentario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios`(`idusuario`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_noticia_comentario` FOREIGN KEY (`idnoticia`) REFERENCES `noticias`(`idnoticia`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB COMMENT='Tabla de los comentarios';
