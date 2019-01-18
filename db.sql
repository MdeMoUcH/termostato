# MdeMoUcH 2018
#Script para crear la base de datos.


CREATE TABLE `em_daily_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lugar` varchar(255) NOT NULL,
  `min` float(4,2) NOT NULL DEFAULT '0.00',
  `max` float(4,2) NOT NULL DEFAULT '0.00',
  `media` float(4,2) NOT NULL DEFAULT '0.00',
  `activo` int(1) DEFAULT '1',
  `borrado` int(1) DEFAULT '0',
  `fecha` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `em_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lugar` varchar(255) NOT NULL,
  `temperatura` float(4,2) NOT NULL DEFAULT '0.00',
  `humedad` float(4,2) NOT NULL DEFAULT '0.00',
  `activo` int(1) DEFAULT '1',
  `borrado` int(1) DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `em_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  `temperatura` float(4,2) NOT NULL DEFAULT '0.00',
  `humedad` float(4,2) NOT NULL DEFAULT '0.00',
  `activo` int(1) DEFAULT '1',
  `borrado` int(1) DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
