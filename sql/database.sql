-- Instalación nueva
CREATE DATABASE IF NOT EXISTS agenda_personal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agenda_personal;
CREATE TABLE IF NOT EXISTS usuarios (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, correo VARCHAR(190) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS categorias (id INT UNSIGNED PRIMARY KEY, nombre VARCHAR(50) NOT NULL UNIQUE) ENGINE=InnoDB;
INSERT IGNORE INTO categorias (id,nombre) VALUES (1,'Familia'),(2,'Amigos'),(3,'Trabajo'),(4,'Universidad'),(5,'Otro');
CREATE TABLE IF NOT EXISTS contactos (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, id_usuario INT UNSIGNED NOT NULL, nombre VARCHAR(120) NOT NULL, telefono VARCHAR(30) NOT NULL, correo VARCHAR(190) NOT NULL, ciudad VARCHAR(100) NOT NULL, id_categoria INT UNSIGNED NOT NULL DEFAULT 5, favorito TINYINT(1) NOT NULL DEFAULT 0, fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_contactos_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE, CONSTRAINT fk_contactos_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id), INDEX idx_contactos_usuario (id_usuario), INDEX idx_contactos_fecha (id_usuario,fecha_creacion)) ENGINE=InnoDB;
