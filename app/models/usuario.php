<?php

require_once __DIR__ . '/../../config/data.php';

class Usuario {

    private $conn;

    public function __construct() {
        $database = new Data();
        $this->conn = $database->getConnection();
    }

    public function login($usuario) {

        $query = "
            SELECT u.idusuario, 
                u.nombre_usuario, 
                u.hash_password,
                r.idrol,
                r.nombre AS rol
            FROM usuario u
            INNER JOIN usuario_has_rol ur ON u.idusuario = ur.usuario_idusuario
            INNER JOIN rol r ON ur.rol_idrol = r.idrol
            WHERE u.nombre_usuario = :usuario
            AND u.estado = 'activo'
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($datos) {

    try {

        $this->conn->beginTransaction();

        // Verificar duplicados
        $sqlVerificar = "SELECT idusuario 
                         FROM usuario 
                         WHERE correo = :correo 
                         OR numero_documento = :documento 
                         OR nombre_usuario = :usuario";

        $stmt = $this->conn->prepare($sqlVerificar);
        $stmt->execute([
            ':correo' => $datos['correo'],
            ':documento' => $datos['numero_documento'],
            ':usuario' => $datos['nombre_usuario']
        ]);

        if ($stmt->rowCount() > 0) {
            return "existe";
        }

        $hashPassword = password_hash($datos['password'], PASSWORD_DEFAULT);

        $sqlInsert = "INSERT INTO usuario 
            (nombres, apellidos, tipo_documento, numero_documento, celular, correo, nombre_usuario, hash_password, acepta_terminos)
            VALUES
            (:nombres, :apellidos, :tipo_documento, :numero_documento, :celular, :correo, :nombre_usuario, :hash_password, :acepta_terminos)";

        $stmt = $this->conn->prepare($sqlInsert);
        $stmt->execute([
            ':nombres' => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':tipo_documento' => $datos['tipo_documento'],
            ':numero_documento' => $datos['numero_documento'],
            ':celular' => $datos['celular'],
            ':correo' => $datos['correo'],
            ':nombre_usuario' => $datos['nombre_usuario'],
            ':hash_password' => $hashPassword,
            ':acepta_terminos' => $datos['acepta_terminos']
        ]);

        $idUsuario = $this->conn->lastInsertId();

        // Asignar rol visitante (4)
        $sqlRol = "INSERT INTO usuario_has_rol (rol_idrol, usuario_idusuario)
                   VALUES (4, :idusuario)";

        $stmtRol = $this->conn->prepare($sqlRol);
        $stmtRol->execute([
            ':idusuario' => $idUsuario
        ]);

        $this->conn->commit();
        $this->enviarCorreoBienvenida($datos['correo'], $datos['nombre_usuario']);
        return "ok";

    } catch (Exception $e) {
        $this->conn->rollBack();
        return "error";
    }
}

    private function enviarCorreoBienvenida($correoDestino, $usuario) {

    require_once __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../../PHPMailer-master/src/SMTP.php';
    require_once __DIR__ . '/../../PHPMailer-master/src/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alexkrimen@gmail.com';
        $mail->Password = 'zkapjqncddxshatv';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('alexkrime@gmail.com', 'JB Construcciones');
        $mail->addAddress($correoDestino);

        $mail->isHTML(true);
        $mail->Subject = 'Bienvenido a JB Construcciones';

        $mail->Body = "
            <h2>Bienvenido a JB-CONSTRUCCIONES</h2>
            <p>Tu cuenta ha sido creada exitosamente.</p>
            <p><strong>Usuario:</strong> {$usuario}</p>
            <p>Ya puedes iniciar sesión en la plataforma.</p>
        ";

        $mail->send();

    } catch (Exception $e) {
        // Registrar en log si deseas
    }
}

    public function procesarRecuperacion($correo)
    {
        // 1. Buscar usuario
        $sql = "SELECT idusuario, nombre_usuario 
                FROM usuario 
                WHERE correo = :correo 
                AND estado = 'activo'
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':correo' => $correo]);

        if ($stmt->rowCount() !== 1) {
            return false;
        }

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Generar token seguro
        $token = bin2hex(random_bytes(32));

        // 3. Guardar token
        $sqlUpdate = "UPDATE usuario 
                    SET token_recuperacion = :token
                    WHERE idusuario = :id";

        $stmtUpdate = $this->conn->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':token' => $token,
            ':id' => $usuario['idusuario']
        ]);

        // 4. Enviar correo
        return $this->enviarCorreoRecuperacion(
            $correo,
            $usuario['nombre_usuario'],
            $token
        );
    }

        private function enviarCorreoRecuperacion($correoDestino, $nombreUsuario, $token)
    {
        require_once __DIR__ . '/../../PHPMailer-master/src/Exception.php';
        require_once __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
        require_once __DIR__ . '/../../PHPMailer-master/src/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'alexkrimen@gmail.com';
            $mail->Password = 'zkapjqncddxshatv';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom('alexkrimen@gmail.com', 'Administración JB-CONSTRUCCIONES');
            $mail->addAddress($correoDestino, $nombreUsuario);

            $enlace = BASE_URL . "app/views/auth/restablecer.php?token=$token";

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de cuenta';

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 10px;'>
                    <h2 style='color:#1e795a;'>Recuperación de cuenta</h2>
                    <p>Hola <b>$nombreUsuario</b>,</p>
                    <p>Has solicitado restablecer tu contraseña.</p>
                    <p>Haz clic en el siguiente botón:</p>
                    <br>
                    <a href='$enlace' style='display:inline-block; padding:10px 20px; background-color:#1e795a; color:white; border-radius:6px; text-decoration:none;'>
                        Restablecer contraseña
                    </a>
                    <br><br>
                    <p>Este enlace es temporal.</p>
                    <hr>
                    <p><b>Administración JB-CONSTRUCCIONES</b></p>
                </div>
            ";

            $mail->send();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }


        public function validarToken($token)
    {
        $sql = "SELECT idusuario, nombre_usuario 
                FROM usuario 
                WHERE token_recuperacion = :token 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':token' => $token]);

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

        public function actualizarContrasenaPorToken($token, $hash)
    {
        $sql = "UPDATE usuario 
                SET hash_password = :hash,
                    token_recuperacion = NULL
                WHERE token_recuperacion = :token";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':hash' => $hash,
            ':token' => $token
        ]);
    }


    public function buscarPorCorreo($correo) {

        $query = "SELECT idusuario, correo FROM usuario WHERE correo = :correo LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}