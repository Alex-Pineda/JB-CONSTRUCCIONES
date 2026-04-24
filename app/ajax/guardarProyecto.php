<?php
require_once __DIR__ . '/../models/proyecto.php';
require_once __DIR__ . '/../../config/data.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

try {

    $db = new Data();
    $conn = $db->getConnection();

    $usuario_id = null;

    /* =========================================
       1. SI HAY COTIZACIÓN → USAR USUARIO DE LA COTIZACIÓN
    ========================================= */
    if (!empty($data['cotizacion_id'])) {

        $sql = "SELECT usuario_idusuario 
                FROM cotizacion 
                WHERE idcotizacion = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$data['cotizacion_id']]);

        $cot = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cot && $cot['usuario_idusuario']) {
            $usuario_id = $cot['usuario_idusuario'];
        }
    }

    /* =========================================
       2. SI NO HAY USUARIO → BUSCAR O CREAR
    ========================================= */
    if (!$usuario_id) {

        $correo = $data['correo'] ?? '';
        $documento = $data['numero_documento'] ?? '';

        //  Buscar usuario existente
        $sql = "SELECT idusuario 
                FROM usuario 
                WHERE correo = ? OR numero_documento = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$correo, $documento]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {

            $usuario_id = $usuario['idusuario'];

        } else {

            /* =========================================
               CREAR USUARIO AUTOMÁTICO
            ========================================= */

            $passwordPlano = substr(str_shuffle("abcdefghijkmnpqrstuvwxyz23456789"), 0, 8);
            $hash = password_hash($passwordPlano, PASSWORD_DEFAULT);

            $username = strtolower($data['nombre']) . rand(100,999);

            $sqlInsert = "INSERT INTO usuario (
                nombres,
                apellidos,
                correo,
                celular,
                numero_documento,
                nombre_usuario,
                hash_password,
                estado,
                acepta_terminos
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'activo', 1)";

            $stmtInsert = $conn->prepare($sqlInsert);

            $stmtInsert->execute([
                $data['nombre'],
                $data['apellido'],
                $correo,
                $data['contacto'],
                $documento,
                $username,
                $hash
            ]);

            $usuario_id = $conn->lastInsertId();

            // asignar rol cliente
            $sqlRol = "INSERT INTO usuario_has_rol (usuario_idusuario, rol_idrol)
                       VALUES (?, 2)";

            $stmtRol = $conn->prepare($sqlRol);
            $stmtRol->execute([$usuario_id]);

            // DEBUG (puedes quitar luego)
            error_log("Usuario creado: $correo | Password: $passwordPlano");
        }
    }

    /* =========================================
       3. CREAR PROYECTO
    ========================================= */

    if (!$usuario_id) {
        throw new Exception("No se pudo determinar el usuario del proyecto");
    }

    $modelo = new Proyecto();

    $modelo->crear([
        "nombre_proyecto" => $data['nombre_proyecto'],
        "descripcion" => $data['descripcion'],
        "direccion" => $data['direccion'],
        "ubicacion" => $data['ubicacion'],
        "fecha_inicio" => $data['fecha_inicio'],
        "fecha_fin" => $data['fecha_fin'],
        "usuario_id" => $usuario_id,
        "cotizacion_id" => $data['cotizacion_id'] ?: null
    ]);

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}