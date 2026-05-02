<?php
require_once __DIR__ . '/../models/ProgresoObra.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$modelo = new ProgresoObra();

$modelo->eliminar($data['idprogreso']);

echo json_encode(["success"=>true]);