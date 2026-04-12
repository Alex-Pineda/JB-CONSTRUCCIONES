<?php
session_start();

// Vaciar variables de sesión
$_SESSION = [];

// Destruir sesión completamente
session_destroy();

// Eliminar cookie de sesión (CRÍTICO)
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

// Redirección ABSOLUTA (evita errores de rutas)
header("Location: /JB-CONSTRUCCIONES/index.php");
exit();