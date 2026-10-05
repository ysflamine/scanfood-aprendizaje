<?php
require_once __DIR__ . '/../lib/validar.php';
//arrays casos de prueba
$casos = [
    ['entrada' => '123456',       'esperado' => '123456'],   // Caso éxito mínimo
    ['entrada' => '12',           'esperado' => null],       // Caso error por longitud
    ['entrada' => 'abc123456!',   'esperado' => '123456'],   // Caso limpieza de letras
    ['entrada' => '',             'esperado' => null],       // String vacío
    ['entrada' => '123456789012345', 'esperado' => null],    // Longitud > 14 (15 dígitos)
    ['entrada' => '!!!@@@',       'esperado' => null],       // Solo caracteres no numéricos
];   

foreach($casos as $valor){
    if(validar_ean($valor['entrada']) === $valor['esperado']){
        echo "[OK] Prueba superada para: {$valor['entrada']}";
    }else{
        exit(1);
    }
}

echo "Todos los tests de validación en verde.";
exit(0);