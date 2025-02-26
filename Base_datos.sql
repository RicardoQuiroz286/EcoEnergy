CREATE DATABASE `Ecoblog`

-- Tabla del administrador --
DROP TABLE `administrador`
CREATE TABLE `administrador` (
    `idadministrador`   INT UNSIGNED    NOT NULL AUTO_INCREMENT COMMENT 'Id del administrador',
    `correo`            VARCHAR(100)    NOT NULL                COMMENT 'Correo del administrador',
    `contraseña`        VARCHAR(30)     NOT NULL                COMMENT 'Contraseña',
    `genero`            ENUM('H', 'M')  NOT NULL                COMMENT 'Género del administrador',
    PRIMARY KEY (`idadministrador`), 
    UNIQUE `_uncorreo` (`correo`),
    CONSTRAINT `_chcontraseña` CHECK (CHAR_LENGTH(`contraseña`) >= 8)
) COMMENT='Tabla del administrador';





-- Tabla de los usuarios --
DROP TABLE `usuarios`
CREATE TABLE `usuarios`(
    `idusuario`         INT UNSIGNED    NOT NULL AUTO_INCREMENT COMMENT 'Id del usuario    ',
    `correo`            VARCHAR(100)    NOT NULL                COMMENT 'Correo del usuario',
    `contraseña`        VARCHAR(30)     NOT NULL                COMMENT 'Contraseña',
    `genero`            ENUM('H', 'M')  NOT NULL                COMMENT 'Género del usuario',
        PRIMARY KEY(`idusuario`), 
            UNIQUE `_uncorreo` (`correo`),
                CONSTRAINT `_chcontraseña` CHECK( CHAR_LENGTH(`contraseña`) >= 8 )
) COMMENT='Tabla de los usuarios';




-- Tabla de las noticias--
DROP TABLE `noticias`
CREATE TABLE `noticias`(
        `idnoticia`           INT UNSIGNED  NOT NULL AUTO_INCREMENT  COMMENT 'Id de la noticia',
        `fecha`               DATETIME      NOT NULL DEFAULT NOW()   COMMENT 'Fecha en la que la noticia fue publicada',
        `informacion`         VARCHAR(5000) NOT NULL                 COMMENT 'Informacion relacionada a la noticia',
            PRIMARY KEY(`idnoticia`)
    ) COMMENT='Tabla de las noticias';