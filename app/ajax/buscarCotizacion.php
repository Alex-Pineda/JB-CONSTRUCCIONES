<?php
require_once __DIR__ . '/../../config/data.php';

header('Content-Type: application/json');
error_reporting(0);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success"=>false,"error"=>"JSON inválido"]);
    exit;
}

$busqueda = $data['busqueda'] ?? '';

try {

    $db = new Data();
    $conn = $db->getConnection();

    $sql = "SELECT * FROM cotizacion 
            WHERE idcotizacion = ? 
            OR numero_documento LIKE ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $busqueda,
        "%$busqueda%"
    ]);

    $cot = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cot) {
        echo json_encode([
            "success"=>true,
            "data"=>$cot
        ]);
    } else {
        echo json_encode([
            "success"=>false,
            "error"=>"No encontrada"
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success"=>false,
        "error"=>$e->getMessage()
    ]);
}