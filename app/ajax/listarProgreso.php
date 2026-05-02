<?php
require_once __DIR__ . '/../models/ProgresoObra.php';

header('Content-Type: application/json');

$id = $_GET['idproyecto'] ?? 0;

$modelo = new ProgresoObra();

$datos = $modelo->listar($id);
$total = $modelo->recalcularProyecto($id);

echo json_encode([
    "success"=>true,
    "data"=>$datos,
    "avance_total"=>$total
]);