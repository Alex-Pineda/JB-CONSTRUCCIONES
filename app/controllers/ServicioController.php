<?php

require_once __DIR__ . '/../models/servicio.php';

class ServicioController {

    private $model;

    public function __construct() {
        $this->model = new Servicio();
    }

    public function listarPorCategoria() {
        return $this->model->obtenerServiciosConCategoria();
    }
}