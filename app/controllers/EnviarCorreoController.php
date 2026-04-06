<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer-master/src/SMTP.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$correo = $data['correo'] ?? '';
$total = $data['total'] ?? 0;
$detalle = $data['detalle'] ?? [];
$cliente = $data['cliente'] ?? [];

function formatoCOP($valor) {
    return "$ " . number_format($valor, 0, ',', '.');
}

// 🔹 construir filas de la tabla
$filas = "";
foreach ($detalle as $d) {
    $filas .= "
    <tr>
        <td style='padding:8px;border:1px solid #ddd'>{$d['servicio_nombre']}</td>
        <td style='padding:8px;border:1px solid #ddd'>{$d['categoria']}</td>
        <td style='padding:8px;border:1px solid #ddd;text-align:right'>{$d['metros']}</td>
        <td style='padding:8px;border:1px solid #ddd;text-align:right'>".formatoCOP($d['precio_unitario'])."</td>
        <td style='padding:8px;border:1px solid #ddd;text-align:right;font-weight:bold'>".formatoCOP($d['subtotal'])."</td>
    </tr>";
}

// 🔥 HTML DEL CORREO (PRO)
$html = "
<div style='font-family:Arial,sans-serif;background:#f4f4f4;padding:20px'>
  <div style='max-width:700px;margin:auto;background:white;border-radius:10px;overflow:hidden'>

    <!-- HEADER -->
    <div style='background:#0f766e;color:white;padding:20px;text-align:center'>
      <h1 style='margin:0'>JB-CONSTRUCCIONES</h1>
      <p style='margin:5px 0'>Cotización de Servicios</p>
    </div>

    <!-- CLIENTE -->
    <div style='padding:20px'>
      <h3 style='color:#0f766e'>Datos del Cliente</h3>
      <p><b>Nombre:</b> {$cliente['nombreCompleto']}</p>
      <p><b>Documento:</b> {$cliente['numeroDocumento']}</p>
      <p><b>Contacto:</b> {$cliente['contacto']}</p>
      <p><b>Ubicación:</b> {$cliente['ubicacion']}</p>
      <p><b>Dirección:</b> {$cliente['direccion']}</p>
    </div>

    <!-- TABLA -->
    <div style='padding:20px'>
      <h3 style='color:#0f766e'>Detalle de Servicios</h3>

      <table style='width:100%;border-collapse:collapse;font-size:14px'>
        <thead>
          <tr style='background:#ccfbf1'>
            <th style='padding:8px;border:1px solid #ddd'>Servicio</th>
            <th style='padding:8px;border:1px solid #ddd'>Categoría</th>
            <th style='padding:8px;border:1px solid #ddd'>m²</th>
            <th style='padding:8px;border:1px solid #ddd'>Precio</th>
            <th style='padding:8px;border:1px solid #ddd'>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          $filas
        </tbody>
      </table>

      <h2 style='text-align:right;color:#dc2626;margin-top:15px'>
        Total: ".formatoCOP($total)."
      </h2>
    </div>

    <!-- FOOTER -->
    <div style='background:#0f766e;color:white;text-align:center;padding:15px'>
      <p style='margin:0'>JB-CONSTRUCCIONES</p>
      <p style='margin:0;font-size:12px'>Soluciones de calidad en obras civiles y mantenimiento</p>
    </div>

  </div>
</div>
";

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'alexkrimen@gmail.com';
    $mail->Password = 'zkapjqncddxshatv';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('alexkrimen@gmail.com', 'JB-CONSTRUCCIONES');
    $mail->addAddress($correo);

    $mail->isHTML(true);
    $mail->Subject = 'Cotización de Servicios';
    $mail->Body = $html;

    $mail->send();

    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $mail->ErrorInfo]);
}