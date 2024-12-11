-- -----------------------------------------------------
-- Calificaciones
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `Calificaciones`;
CREATE SCHEMA IF NOT EXISTS `Calificaciones` DEFAULT CHARACTER SET utf8mb4;
USE `Calificaciones`;

-- -----------------------------------------------------
-- Tabla Roles
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Roles` (
  `idRoles` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombreRol` VARCHAR(20) NOT NULL UNIQUE,
  PRIMARY KEY (`idRoles`)
) ENGINE=InnoDB;

-- Insertar Roles
INSERT INTO `Roles` (`nombreRol`) VALUES ('alumno'), ('profesor'), ('preceptor');

-- -----------------------------------------------------
-- Tabla Usuarios
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Usuarios` (
  `idUsuarios` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(30) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellido` VARCHAR(50) NOT NULL,
  `dni` CHAR(8) UNIQUE NOT NULL,
  `idRoles` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idUsuarios`),
  CONSTRAINT `fk_Usuarios_Roles`
    FOREIGN KEY (`idRoles`) REFERENCES `Roles` (`idRoles`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla Carreras
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Carreras` (
  `idCarreras` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombreCarreras` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`idCarreras`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla Materias
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Materias` (
  `idMaterias` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombreMaterias` VARCHAR(100) NOT NULL,
  `idCarreras` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idMaterias`),
  CONSTRAINT `fk_Materias_Carreras`
    FOREIGN KEY (`idCarreras`) REFERENCES `Carreras` (`idCarreras`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla Notas
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Notas` (
  `idNotas` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parcial1` DECIMAL(5,2) NULL,
  `parcial2` DECIMAL(5,2) NULL,
  `final` DECIMAL(5,2) NULL,
  `idUsuarios` INT UNSIGNED NOT NULL,
  `idMaterias` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idNotas`),
  CONSTRAINT `fk_Notas_Usuarios`
    FOREIGN KEY (`idUsuarios`) REFERENCES `Usuarios` (`idUsuarios`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Notas_Materias`
    FOREIGN KEY (`idMaterias`) REFERENCES `Materias` (`idMaterias`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla Usuarios_Carreras (para asignar alumnos y preceptores a carreras)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Usuarios_Carreras` (
  `idUsuarios` INT UNSIGNED NOT NULL,
  `idCarreras` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idUsuarios`, `idCarreras`),
  CONSTRAINT `fk_Usuarios_Carreras_Usuarios`
    FOREIGN KEY (`idUsuarios`) REFERENCES `Usuarios` (`idUsuarios`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Usuarios_Carreras_Carreras`
    FOREIGN KEY (`idCarreras`) REFERENCES `Carreras` (`idCarreras`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Tabla Materias_Profesores (para asignar materias a profesores)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Materias_Profesores` (
  `idMaterias` INT UNSIGNED NOT NULL,
  `idUsuarios` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idMaterias`, `idUsuarios`),
  CONSTRAINT `fk_Materias_Profesores_Materias`
    FOREIGN KEY (`idMaterias`) REFERENCES `Materias` (`idMaterias`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Materias_Profesores_Usuarios`
    FOREIGN KEY (`idUsuarios`) REFERENCES `Usuarios` (`idUsuarios`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;
