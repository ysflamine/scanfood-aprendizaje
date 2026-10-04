<?php
header('Content-Type: application/json; charset=utf-8');

$ean = $_GET['ean'] ?? ''; // Obtiene el código EAN de la URL, si está presente
$eanLimpio = preg_replace('/[^0-9]/', '', $ean); //preg_replace ayuda a filtrar con expresiones regulares
$longitud = strlen($eanLimpio); // Calcula el largo del código EAN recibido



if (is_string($eanLimpio) && $eanLimpio !== '' && $longitud > 5 && $longitud < 15) { //uso is_string debido a que me ayuda a descartar null, false, 0, arrays, etc. Todo de una.
    echo json_encode(["ok" => true, "ean" => $eanLimpio]);

}else{
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => ['code' => 'bad_ean', 'message' => 'EAN inválido']]);
}
