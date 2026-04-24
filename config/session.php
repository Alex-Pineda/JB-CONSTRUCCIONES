<?php

require_once __DIR__ . '/data.php'; 

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Tiempo máximo de inactividad (10 minutos)
$tiempo_max = 600;

// Validar inactividad
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if ((time() - $_SESSION['LAST_ACTIVITY']) > $tiempo_max) {
        session_unset();
        session_destroy();
        
        header("Location: " . BASE_URL . "index.php?session=expirada");
        exit();
    }
}

// Actualizar última actividad
$_SESSION['LAST_ACTIVITY'] = time();


// Validar IP (evita secuestro de sesión)
if (!isset($_SESSION['IP'])) {
    $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
} elseif ($_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . "index.php?session=ip_error");
    exit();
}

// Validar navegador (User Agent)
if (!isset($_SESSION['USER_AGENT'])) {
    $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . "index.php?session=agent_error");
    exit();
}