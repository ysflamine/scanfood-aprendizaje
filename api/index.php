<?php
$ean = $_GET['ean'] ?? ''; // Obtiene el código EAN de la URL, si está presente
$longitud = strlen($ean); // Calcula el largo del código EAN recibido
echo "Recibido: $ean ($longitud caracteres)"; // Muestra el código EAN recibido y su longitud en caracteres

