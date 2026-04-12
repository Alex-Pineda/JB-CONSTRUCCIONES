<?php
require_once './config/data.php';

session_start();
$usuario_id = $_SESSION['usuario']['idusuario'] ?? null;


$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "error" => "JSON inválido",
        "detalle_error" => json_last_error_msg()
    ]);
    exit;
}

if (!$data) {
    echo json_encode(["error" => "Payload vacío"]);
    exit;
}

$detalle = $data['detalle'] ?? [];

$db = new Data();
$conn = $db->getConnection();

try {
    $conn->beginTransaction();


    // Insertar cotización
    $stmt = $conn->prepare("
        INSERT INTO cotizacion (
        nombres, apellidos, tipo_documento, numero_documento,
        correo, contacto, ubicacion, direccion,
        descripcion, ser_contactado, fecha_visita, total_estimado,
        usuario_idusuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

        $stmt->execute([
        $data['nombres'],
        $data['apellidos'],
        $data['tipo_documento'],
        $data['numero_documento'],
        $data['correo'],
        $data['contacto'],
        $data['ubicacion'],
        $data['direccion'],
        $data['descripcion'],
        $data['ser_contactado'],
        $data['fecha_visita'],
        $data['total_estimado'],
        $usuario_id
    ]);

    $idCotizacion = $conn->lastInsertId();

    // Insertar detalle
    $stmtDetalle = $conn->prepare("
        INSERT INTO cotizacion_servicio (
            cotizacion_idcotizacion,
            servicio_idservicio,
            metros,
            precio_unitario,
            subtotal
        ) VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($detalle as $d) {
        $stmtDetalle->execute([
            $idCotizacion,
            $d['servicio_id'],
            $d['metros'],
            $d['precio_unitario'],
            $d['subtotal']
        ]);
    }

    $conn->commit();

    echo json_encode([
        "success" => true,
        "id_cotizacion" => $idCotizacion
    ]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}