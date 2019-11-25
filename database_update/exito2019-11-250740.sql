CREATE TABLE `gerentes_dependencia` ( 
	`id` INT(11) NOT NULL AUTO_INCREMENT , 
	`usuario` VARCHAR(50) NOT NULL , 
	 `codigo_dependencia` VARCHAR(100) NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `permiso` (`id`, `nombre`) VALUES (NULL, 'ver-crear-eliminar-gerente');

INSERT INTO `permiso_rol` (`rol_id`, `permiso_id`) VALUES ('1', '49');