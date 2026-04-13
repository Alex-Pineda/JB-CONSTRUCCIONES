<?php
require_once __DIR__ . '/../models/proyecto.php';
require_once __DIR__ . '/../../config/session.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

try {

$modelo = new Proyecto();

$modelo->crear([
    "nombre_proyecto" => $data['nombre_proyecto'],
    "descripcion" => $data['descripcion'],
    "direccion" => $data['direccion'],
    "ubicacion" => $data['ubicacion'],
    "fecha_inicio" => $data['fecha_inicio'],
    "fecha_fin" => $data['fecha_fin'],
    "usuario_id" => $_SESSION['usuario']['idusuario'] ?? null,
    "cotizacion_id" => $data['cotizacion_id'] ?: null
]);

echo json_encode(["success"=>true]);

} catch(Exception $e) {
echo json_encode(["error"=>$e->getMessage()]);
}