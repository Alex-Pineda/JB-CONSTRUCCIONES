<?php
require_once __DIR__ . '/ServicioController.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $controller = new ServicioController();
    $servicios = $controller->listarPorCategoria();

    echo json_encode([
        "ok" => true,
        "data" => $servicios
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
}