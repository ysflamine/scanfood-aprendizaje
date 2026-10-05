<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../lib/validar.php';
require_once __DIR__ . '/../lib/db.php';
$action = $_GET['action'] ?? 'eco';//hacemos uso del operador Null Coalescing para tener un valor por defecto si no tiene nada

match ($action) {
    'eco' => (function () {
        $resultado = validar_ean($_GET['ean'] ?? '');

        if ($resultado !== null) {
            echo json_encode(["exito" => true]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ean_invalido"]);
        }
    })(),

    'ping' => print json_encode(["hora" => date('c')]),

    'buscar' => (function () {
        $resultado = validar_ean($_GET['ean'] ?? '');

        if ($resultado !== null) {
            $pdo = db();
            $stmt = $pdo->prepare("SELECT * FROM productos WHERE ean = ?");
            $stmt->execute([$resultado]); //evitamos inyecciones
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($rows)) {
                http_response_code(404);
                echo json_encode(["error" => "No encontrado"]);
            } else {
                echo json_encode(["exito" => true, "productos" => $rows]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ean_invalido"]);
        }
    })(),   
    default => (function () {
        http_response_code(400);
        echo json_encode(["error" => "bad_action"]);
    })()
};