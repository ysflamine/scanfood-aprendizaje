<?php
function validar_ean(string $s): ?string {
$eanLimpio = preg_replace('/[^0-9]/', '', $s); //usamos el parámetro $s ya que estoy recibiendo el parametro desde la funcion
$longitud = strlen($eanLimpio); 



if (is_string($eanLimpio) && $eanLimpio !== '' && $longitud > 5 && $longitud < 15) { //uso is_string debido a que me ayuda a descartar null, false, 0, arrays, etc. Todo de una.
    return $eanLimpio;

}else{
    return null;
}

}