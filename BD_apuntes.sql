CREATE TABLE `contenido` (
  `id` integer PRIMARY KEY,
  `nombre` varchar(200),
  `descripcion` text,
  `fecha_creacion` datetime,
  `fecha_actualizacion` datetime ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int
);

CREATE TABLE `apunte` (
  `id` integer PRIMARY KEY,
  `contenido_id` integer,
  `nombre` varchar(200),
  `url` varchar(500),
  `fecha_creacion` datetime,
  `fecha_actualizacion` datetime ON UPDATE CURRENT_TIMESTAMP,
  `tipoApunte` char(10)
);
 

 ALTER TABLE `apunte` ADD CONSTRAINT `contenido_apunte` FOREIGN KEY (`contenido_id`) REFERENCES `contenido` (`id`);


 CREATE TABLE `user`(
  user_id int not null primary key AUTO_INCREMENT,
  user_name char(50),
  password char(50),
);

ALTER TABLE `contenido` ADD CONSTRAINT `user_contenido` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

insert into user(user_name,password) values("omniman","thinkmarkthink!")