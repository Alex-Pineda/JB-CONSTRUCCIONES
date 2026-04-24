<?php

require_once __DIR__ . '/../../config/data.php';

session_start();

require_once __DIR__ . '/../models/usuario.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $modelo = new Usuario();

    /* =====================================================
       1️ REGISTRO
    ===================================================== */
    if (isset($_POST['accion']) && $_POST['accion'] === 'registro') {

        $resultado = $modelo->registrar($_POST);

        if ($resultado === "ok") {

            header("Location: " . BASE_URL . "app/views/auth/login.php?success=registro");
            exit();

        } elseif ($resultado === "existe") {

            header("Location: " . BASE_URL . "app/views/auth/registro.php?error=Usuario ya registrado");
            exit();

        } else {

            header("Location: " . BASE_URL . "app/views/auth/registro.php?error=Error en el registro");
            exit();
        }
    }

    /* =====================================================
    RECUPERACIÓN DE CUENTA
    ===================================================== */
    if (isset($_POST['accion']) && $_POST['accion'] === 'recuperacion') {

        $correo = trim($_POST['correoRecuperacion'] ?? '');

        if (empty($correo)) {
            header("Location: " . BASE_URL . "app/views/auth/login.php?error=correo");
            exit();
        }

        $resultado = $modelo->procesarRecuperacion($correo);

        header("Location: " . BASE_URL . "app/views/auth/login.php?success=recuperacion");
        exit();
    }


    /* =====================================================
    RESTABLECER CONTRASEÑA
    ===================================================== */
    if (isset($_POST['accion']) && $_POST['accion'] === 'restablecer') {

        $token = $_POST['token'] ?? '';
        $nueva = $_POST['nueva_contrasena'] ?? '';
        $confirmar = $_POST['confirmar_contrasena'] ?? '';

        if ($nueva !== $confirmar) {
            header("Location: " . BASE_URL . "app/views/auth/restablecer.php?token=$token&error=1");
            exit();
        }

        $hash = password_hash($nueva, PASSWORD_DEFAULT);

        $modelo->actualizarContrasenaPorToken($token, $hash);

        header("Location: " . BASE_URL . "app/views/auth/login.php?success=actualizada");
        exit();
    }


    /* =====================================================
       2️ LOGIN
    ===================================================== */
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (empty($usuario) || empty($contrasena)) {
        header("Location: " . BASE_URL . "app/views/auth/login.php?error=campos");
        exit();
    }

    $data = $modelo->login($usuario);

    if ($data && password_verify($contrasena, $data['hash_password'])) {

        session_regenerate_id(true);

        // Guardar datos del usuario
        $_SESSION['usuario'] = [
            'idusuario' => $data['idusuario'],
            'nombre_usuario' => $data['nombre_usuario'],
            'idrol' => $data['idrol'],
            'rol' => $data['rol']
        ];

        // Inicializar seguridad de sesión
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

        // Solo administrador va a admin
        if ($data['idrol'] == 1) {
            header("Location: " . BASE_URL . "admin.php");
        } else {
            header("Location: " . BASE_URL . "index.php");
        }

        exit();

    } else {

        header("Location: " . BASE_URL . "app/views/auth/login.php?error=credenciales");
        exit();
    }
}