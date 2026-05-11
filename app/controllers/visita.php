<?php

require_once __DIR__ . '/../models/visitaPendiente.php';

$modelo = new VisitaPendiente();


/* =========================
   CONFIRMAR VISITA
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['accion'])
    && $_POST['accion'] === 'confirmar') {

    $idcotizacion = $_POST['idcotizacion'];

    $resultado = $modelo->confirmarVisita($idcotizacion);

    echo $resultado ? 'ok' : 'error';

    exit();
}


/* =========================
   CANCELAR VISITA
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['accion'])
    && $_POST['accion'] === 'cancelar') {

    $idcotizacion = $_POST['idcotizacion'];

    $resultado = $modelo->cancelarVisita($idcotizacion);

    echo $resultado ? 'ok' : 'error';

    exit();
}


/* =========================
   LISTAR VISITAS
========================= */
$visitasPendientes = $modelo->obtenerVisitasPendientes();