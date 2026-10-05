# ADR 0002: Gestión de credenciales y configuración local

## Contexto
El acceso a la base de datos MariaDB requiere credenciales sensibles. Escribirlas directamente en el código fuente (`lib/db.php`) expone el servidor a graves brechas de seguridad al publicarse el repositorio.

## Decisión
Extraer todas las credenciales a un archivo de configuración externo (`config_local.php`) que es ignorado por el control de versiones mediante `.gitignore`. Se proporciona un archivo `config_local.example.php` como plantilla para nuevos despliegues.

## Consecuencias
* **Positivas:** Seguridad total de las credenciales, cumpliendo con el principio de separación de configuración y código (metodología 12-Factor App).
* **Negativas:** Añade un paso manual de configuración para cualquier desarrollador que clone el repositorio.