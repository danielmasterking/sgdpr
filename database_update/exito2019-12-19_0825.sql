CREATE TABLE `lideres_dependencia` ( 
	`id` INT(11) NOT NULL AUTO_INCREMENT , 
	`usuario` VARCHAR(50) NOT NULL , 
	 `codigo_dependencia` VARCHAR(100) NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;


CREATE TABLE `coordinadores_dependencia` ( 
	`id` INT(11) NOT NULL AUTO_INCREMENT , 
	`usuario` VARCHAR(50) NOT NULL , 
	 `codigo_dependencia` VARCHAR(100) NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;


INSERT INTO `rol` (`id`, `nombre`) VALUES (NULL, 'gerente_tienda'), (NULL, 'lider_seguridad_tienda');
INSERT INTO `rol` (`id`, `nombre`) VALUES (NULL, 'coordinador_tienda');

INSERT INTO `permiso` (`id`, `nombre`) VALUES (NULL, 'ver_aprobacion_gerente'), (NULL, 'ver_aprobacion_lider');

INSERT INTO `permiso` (`id`, `nombre`) VALUES (NULL, 'ver_aprobacion_coordinador');