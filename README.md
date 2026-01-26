Trabajo Final - Programación Web Dinámica (PWD) <br>

Este repositorio contiene el **Proyecto Final** integrador de la asignatura Programación Web Dinámica. El sistema es una aplicación web completa desarrollada bajo el patrón de arquitectura **MVC (Modelo-Vista-Controlador)**. Este proyecto representa la integración final de conocimientos en desarrollo web dinámico, manejo de sesiones, persistencia de datos y patrones de diseño.

> Estructura del Proyecto

La organización del código garantiza una clara separación entre la lógica de datos, el procesamiento y la presentación:

* **`/Modelo`**: Contiene las clases encargadas de la gestión de datos y la interacción directa con la base de datos.
* **`/Control`**: Implementa la lógica de negocio, procesando los datos recibidos de la vista y coordinando las acciones con el modelo.
* **`/Vista`**: Incluye todas las páginas, formularios y componentes de la interfaz de usuario con los que interactúa el usuario final.
* **`/util`**: Clases y funciones de utilidad, como el manejo de sesiones y herramientas generales del sistema.
* **`configuracion.php`**: Archivo central para la configuración de rutas, carga automática de clases y parámetros de conexión.
* **`bd_especificacion.pdf`**: Documentación técnica que detalla el diseño y la estructura de la base de datos utilizada.

> Tecnologías Utilizadas

* **PHP**: Motor principal para el desarrollo de la lógica del lado del servidor.
* **Arquitectura MVC**: Implementación robusta para la separación de responsabilidades.
* **Bootstrap / CSS**: (Mencionado mínimamente en el análisis) Para el diseño y maquetación de la interfaz de usuario.
* **SQL**: Estructura relacional de datos detallada en la especificación adjunta.

> Instalación y Ejecución

1. **Entorno**: Se requiere un servidor local con soporte para PHP y MySQL (ej. XAMPP o Laragon).
2. **Base de Datos**: Configurar la base de datos siguiendo las especificaciones del archivo `bd_especificacion.pdf`.
3. **Configuración**: Revisar y ajustar el archivo `configuracion.php` para asegurar que las rutas y credenciales de acceso sean correctas.
4. **Acceso**: Navegar a la carpeta `/Vista` desde el navegador para iniciar la aplicación.
