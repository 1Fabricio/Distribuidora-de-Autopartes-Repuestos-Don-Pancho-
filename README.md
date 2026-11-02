# Autopartes y Repuestos "Don Pancho"

Proyecto de la clase ISC-305 Programación Web.

## Descripción

Plataforma web para una distribuidora de autopartes con sucursales en Tegucigalpa, San Pedro Sula y La Ceiba. Está hecha en PHP con PDO y MySQL, y usa Bootstrap para el diseño.

El sistema tiene dos partes:

Portal de clientes: catálogo de repuestos desde la base de datos, kit de mantenimiento Tune-Up, carrito, facturación con RTN e ISV del 15 %, y directorio de sucursales con historial de compras.

Panel administrativo: acceso con login y roles  en este casoadmin y bodeguero. Permite manejar el inventario con ventanas modales y subir fotos de los repuestos en JPG, PNG o WEBP.

## Requisitos

- XAMPP con Apache y MySQL
- PHP 8.0 o superior
- Git
- el navegador web

## Instalación

1. Clonar el repositorio dentro de la carpeta htdocs de XAMPP:

    git clone https://github.com/1Fabricio/Distribuidora-de-Autopartes-Repuestos-Don-Pancho-.git

2. Abrir XAMPP y encender Apache y MySQL.
3. Entrar a http://localhost/phpmyadmin
4. Ir a la pestaña Importar, elegir el archivo database/donpancho.sql y presionar Continuar. Esto crea la base de datos don_pancho con sus tablas y datos iniciales.
5. Revisar que include/conexion.php tenga los datos del MySQL que se va a usar; Por defecto usa host localhost, usuario root y sin contraseña.
6. Abrir en el navegador:

    http://localhost/Distribuidora-de-Autopartes-Repuestos-Don-Pancho-/public/index.php


## Credenciales de prueba para la tarea

| Rol | Usuario | Contraseña |
| Administrador | admin | Admin123! |
| Bodeguero | bodeguero | Bodega123! |
| Cliente | cliente | Cliente123! |

Admin y bodeguero entran al panel de inventario. El cliente usa el portal de compras.

Historial de compras (página Sucursales):

- Sin iniciar sesión no se muestra el historial.
- El cliente ve únicamente sus propias compras.
- El administrador y el bodeguero ven las compras de todos los usuarios, y pueden filtrarlas por sucursal.

## Estructura del proyecto

- classes: clases PHP (usuarios, productos, marcas y sucursales)
- database: script SQL de la base de datos
- include: conexión, sesión, navbar y funciones compartidas
- public: páginas del sitio y panel administrativo (carpeta admin)
- uploads: fotografías de los repuestos

## Seguridad

- Consultas preparadas con PDO
- Contraseñas guardadas con bcrypt
- Rutas del panel protegidas por sesión y rol
- Datos escapados con htmlspecialchars al mostrarlos
- Subida de archivos con validación de error, tamaño máximo de 4 MB, extensión, tipo MIME y nombre único

## Integrantes

Edixon Fabricio Muñoz 
Yadira Elizabeth Zepeda 
Justin David Gonzalez
Mellany Dayanne Castellanos
Patricia Jasmin Blandin