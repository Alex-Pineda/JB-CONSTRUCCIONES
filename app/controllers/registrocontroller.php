<?php
require_once __DIR__ . '/../../config/data.php';

class RegistroController {

    private $conn;

    public function __construct() {
        $database = new Data();
        $this->conn = $database->getConnection();
    }

    public function registrar($datos) {

        try {

            //  Iniciar transacción
            $this->conn->beginTransaction();

            // 1️ Verificar duplicados
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

            // 2️ Encriptar contraseña
            $hashPassword = password_hash($datos['password'], PASSWORD_DEFAULT);

            // 3️ Insertar usuario
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

            // 4️ Obtener ID generado
            $idUsuario = $this->conn->lastInsertId();

            // 5️ Asignar rol visitante (2)
            $sqlRol = "INSERT INTO usuario_has_rol (rol_idrol, usuario_idusuario)
                       VALUES (2, :idusuario)";

            $stmtRol = $this->conn->prepare($sqlRol);
            $stmtRol->execute([
                ':idusuario' => $idUsuario
            ]);

            //  Confirmar transacción
            $this->conn->commit();

            return "ok";

        } catch (Exception $e) {

            //  Revertir si algo falla
            $this->conn->rollBack();
            return "error";
        }
    }
}