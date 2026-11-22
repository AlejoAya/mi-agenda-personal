# Mi Agenda Personal — Versión 2

Aplicación PHP y MySQL con autenticación y gestión privada de contactos.

## URL pública
https://mi-agenda.page.gd

## Tecnologías
PHP 8.1+, PDO, MySQL/MariaDB, HTML5, CSS3, sesiones PHP y XAMPP para desarrollo local.

## Mejoras respecto a la versión anterior
1. Búsqueda por nombre, correo o ciudad.
2. Ordenamiento por nombre, ciudad o fecha de creación.
3. Categorías: Familia, Amigos, Trabajo, Universidad y Otro.
4. Marcado y filtrado de contactos favoritos.

## Instalación local
1. Extraer la carpeta `mi-agenda-personal` en `C:\xampp\htdocs\`.
2. Iniciar Apache y MySQL desde XAMPP.
3. Para una instalación nueva, importar `sql/database.sql` desde phpMyAdmin.
4. Para actualizar una instalación anterior sin perder datos, **hacer copia de seguridad** y ejecutar una sola vez `sql/migracion_v1_a_v2.sql` en lugar de `database.sql`.
5. Si cambian las credenciales MySQL, copiar `config/local.example.php` a `config/local.php` y ajustarlas. Nunca subir `local.php` al repositorio.
6. Abrir `http://localhost/mi-agenda-personal/register.php`.

## Despliegue en hosting gratuito
1. Elegir un hosting compatible con PHP y MySQL, comprobar versión y límites.
2. Crear la base de datos MySQL desde el panel del hosting.
3. Importar `sql/database.sql` en la base asignada. Si el hosting impide `CREATE DATABASE` y `USE`, eliminar **solo esas dos instrucciones** y ejecutar el resto desde la base ya seleccionada.
4. Copiar `config/local.example.php` como `config/local.php` y colocar host, base, usuario y contraseña entregados por el proveedor.
5. Subir los archivos PHP, CSS y carpetas `config` e `includes` a la carpeta pública del hosting. No publicar los archivos SQL, copias de seguridad ni `.git`.
6. Probar la URL con HTTPS, registro, inicio de sesión, creación, edición, eliminación, búsqueda, orden, categorías, favoritos y cierre de sesión.
7. Añadir la URL pública y capturas reales a este README.

## Capturas de pantalla
Inicio de sesión, panel y formulario de contacto. `docs/capturas/` 

## Seguridad
Contraseñas con `password_hash()` y `password_verify()`; PDO con consultas preparadas; sesiones; validaciones en servidor; escape HTML; CSRF; y restricción por `id_usuario` en las consultas, actualizaciones y eliminaciones. El ordenamiento usa una lista blanca de columnas.

## Pruebas
- Crear usuarios A y B, comprobar que cada uno solo ve sus contactos.
- Intentar abrir `contact_edit.php?id=...` de un contacto de A mientras se está autenticado como B: debe mostrar «Contacto no encontrado».
- Buscar por nombre, correo y ciudad; probar todos los ordenamientos.
- Asignar categoría y favorito, filtrar, editar y eliminar.

## Entregables
URL pública, repositorio GitHub público y script SQL actualizado.
