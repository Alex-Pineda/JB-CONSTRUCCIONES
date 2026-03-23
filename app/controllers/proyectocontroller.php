<?php
require_once __DIR__ . '/../models/proyecto.php';

class ProyectoController {

    private $modelo;

    public function __construct() {
        $this->modelo = new Proyecto();
    }

    public function listar() {
        return $this->modelo->obtenerTodos();
    }
}