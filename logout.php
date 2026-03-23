<?php
session_start();

// Vaciar variables de sesión
$_SESSION = [];

// Destruir sesión
session_destroy();

// Eliminar cookie de sesión (MUY IMPORTANTE)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirigir al index público
header("Location: index.php");
exit();