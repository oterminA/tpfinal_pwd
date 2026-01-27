SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

-- =========================
-- Base de datos
-- =========================

CREATE DATABASE IF NOT EXISTS `bdcarritocompras`;
USE `bdcarritocompras`;

-- =========================
-- TABLA USUARIO
-- =========================

CREATE TABLE `usuario` (
  `idusuario` bigint(20) NOT NULL AUTO_INCREMENT,
  `usnombre` varchar(50) NOT NULL,
  `uspass` int(11) NOT NULL,
  `usmail` varchar(50) NOT NULL,
  `usdeshabilitado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA ROL
-- =========================

CREATE TABLE `rol` (
  `idrol` bigint(20) NOT NULL AUTO_INCREMENT,
  `roldescripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA USUARIOROL (CORREGIDA)
-- =========================

CREATE TABLE `usuariorol` (
  `idusuariorol` bigint(20) NOT NULL AUTO_INCREMENT,
  `idrol` bigint(20) NOT NULL,
  `idusuario` bigint(20) NOT NULL,
  PRIMARY KEY (`idusuariorol`),
  KEY `idrol` (`idrol`),
  KEY `idusuario` (`idusuario`),
  CONSTRAINT `fk_usuariorol_rol`
    FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE,
  CONSTRAINT `fk_usuariorol_usuario`
    FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA MENU
-- =========================

CREATE TABLE `menu` (
  `idmenu` bigint(20) NOT NULL AUTO_INCREMENT,
  `menombre` varchar(50) NOT NULL,
  `medescripcion` varchar(124) NOT NULL,
  `idpadre` bigint(20) DEFAULT NULL,
  `medeshabilitado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idmenu`),
  KEY `fkmenu_1` (`idpadre`),
  CONSTRAINT `fkmenu_1`
    FOREIGN KEY (`idpadre`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA MENUROL (CORREGIDA)
-- =========================

CREATE TABLE `menurol` (
  `idmenurol` bigint(20) NOT NULL AUTO_INCREMENT,
  `idmenu` bigint(20) NOT NULL,
  `idrol` bigint(20) NOT NULL,
  PRIMARY KEY (`idmenurol`),
  KEY `idmenu` (`idmenu`),
  KEY `idrol` (`idrol`),
  CONSTRAINT `fk_menurol_menu`
    FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE,
  CONSTRAINT `fk_menurol_rol`
    FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA PRODUCTO
-- =========================

CREATE TABLE `producto` (
  `idproducto` bigint(20) NOT NULL AUTO_INCREMENT,
  `pronombre` int(11) NOT NULL,
  `prodetalle` varchar(512) NOT NULL,
  `procantstock` int(11) NOT NULL,
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA COMPRA
-- =========================

CREATE TABLE `compra` (
  `idcompra` bigint(20) NOT NULL AUTO_INCREMENT,
  `cofecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idusuario` bigint(20) NOT NULL,
  PRIMARY KEY (`idcompra`),
  KEY `fkcompra_1` (`idusuario`),
  CONSTRAINT `fkcompra_1`
    FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA COMPRAESTADOTIPO
-- =========================

CREATE TABLE `compraestadotipo` (
  `idcompraestadotipo` int(11) NOT NULL AUTO_INCREMENT,
  `cetdescripcion` varchar(50) NOT NULL,
  `cetdetalle` varchar(256) NOT NULL,
  PRIMARY KEY (`idcompraestadotipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `compraestadotipo`
(`idcompraestadotipo`, `cetdescripcion`, `cetdetalle`) VALUES
(1,'iniciada','cuando el usuario inicia la compra'),
(2,'aceptada','cuando el administrador acepta la compra'),
(3,'enviada','cuando el administrador envía la compra'),
(4,'cancelada','cuando la compra es cancelada');

-- =========================
-- TABLA COMPRAESTADO
-- =========================

CREATE TABLE `compraestado` (
  `idcompraestado` bigint(20) NOT NULL AUTO_INCREMENT,
  `idcompra` bigint(20) NOT NULL,
  `idcompraestadotipo` int(11) NOT NULL,
  `cefechaini` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cefechafin` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idcompraestado`),
  KEY `fkcompraestado_1` (`idcompra`),
  KEY `fkcompraestado_2` (`idcompraestadotipo`),
  CONSTRAINT `fkcompraestado_1`
    FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fkcompraestado_2`
    FOREIGN KEY (`idcompraestadotipo`) REFERENCES `compraestadotipo` (`idcompraestadotipo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================
-- TABLA COMPRAITEM
-- =========================

CREATE TABLE `compraitem` (
  `idcompraitem` bigint(20) NOT NULL AUTO_INCREMENT,
  `idcompra` bigint(20) NOT NULL,
  `idproducto` bigint(20) NOT NULL,
  `cicantidad` int(11) NOT NULL,
  PRIMARY KEY (`idcompraitem`),
  KEY `fkcompraitem_1` (`idcompra`),
  KEY `fkcompraitem_2` (`idproducto`),
  CONSTRAINT `fkcompraitem_1`
    FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fkcompraitem_2`
    FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

COMMIT;
