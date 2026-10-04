# ADR 0001: Uso de PHP nativo sin frameworks

## Contexto
Necesitamos construir la versión 1 de ScanFood, una API y frontend para escanear información nutricional. 
Existen frameworks potentes como Laravel.

## Decisión
Hemos decidido utilizar PHP nativo sin frameworks ni dependencias externas (sin Composer) para la v1.

## Consecuencias
* **Positivas:** Aprendizaje profundo de las bases del lenguaje, la SAPI web, PDO para bases de datos y la gestión de peticiones HTTP en crudo.
* **Negativas:** Tendremos que reinventar la rueda en temas como el enrutador (router) o la estructura de carpetas, y requerirá más disciplina mantener el código limpio.