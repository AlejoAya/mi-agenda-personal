-- Ejecutar UNA SOLA VEZ en una base de datos de la versión anterior, después de hacer copia de seguridad.
USE agenda_personal;
CREATE TABLE IF NOT EXISTS categorias (id INT UNSIGNED PRIMARY KEY, nombre VARCHAR(50) NOT NULL UNIQUE) ENGINE=InnoDB;
INSERT IGNORE INTO categorias (id,nombre) VALUES (1,'Familia'),(2,'Amigos'),(3,'Trabajo'),(4,'Universidad'),(5,'Otro');
ALTER TABLE contactos ADD COLUMN id_categoria INT UNSIGNED NOT NULL DEFAULT 5, ADD COLUMN favorito TINYINT(1) NOT NULL DEFAULT 0, ADD COLUMN fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, ADD CONSTRAINT fk_contactos_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id), ADD INDEX idx_contactos_fecha (id_usuario,fecha_creacion);
-- Contactos anteriores reciben fecha de migración.
