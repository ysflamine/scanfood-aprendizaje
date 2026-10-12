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
    'alta' => (function () {
        $ean = validar_ean($_GET['ean'] ?? '');
        $nombre = trim($_GET['nombre'] ?? '');
        $marca = trim($_GET['marca'] ?? '');

        if ($ean === null || $nombre === '' || $marca === '') {
            http_response_code(400);
            echo json_encode([
                "error" => "datos_invalidos"
            ]);
            return;
        }

        try {
            $pdo = db();

            $stmt = $pdo->prepare(
                'INSERT INTO productos (ean, nombre, marca) VALUES (:ean, :nombre, :marca)'
            );

            $stmt->execute([
                'ean' => $ean,
                'nombre' => $nombre,
                'marca' => $marca,
            ]);
            http_response_code(201);            
            echo json_encode([ "exito" => true]);

        } catch (PDOException $e) {
            if($e->getCode() == 23000){
            http_response_code(409);
            echo json_encode([ "error" => "El producto ya existe" ]);
            }else{
            http_response_code(500);
            echo json_encode([ "error" => "ERROR GENERAL" ]);                
            }
        }
    })(),
    default => (function () {
        http_response_code(400);
        echo json_encode(["error" => "bad_action"]);
    })()
};