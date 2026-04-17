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

    public function listarPorUsuario($usuario_id) {
        return $this->modelo->obtenerPorUsuario($usuario_id);
    }

    public function obtenerRolUsuario($usuario_id) {
    return $this->modelo->obtenerRolUsuario($usuario_id);
    }

    public function tieneProyectos($usuario_id) {
    return $this->modelo->tieneProyectos($usuario_id);
    }

}