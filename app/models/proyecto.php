<?php
require_once __DIR__ . '/../../config/data.php';

class Proyecto {

    private $conn;

    public function __construct() {
        $data = new Data();
        $this->conn = $data->getConnection();
    }

    public function obtenerTodos() {

    $sql = "SELECT 
            p.idproyecto,
            p.nombre_proyecto,
            p.descripcion,
            p.direccion,
            p.fecha_inicio,
            p.fecha_fin_estimada,
            p.estado_proyecto,
            
            CONCAT(u.nombres, ' ', u.apellidos) AS cliente_nombre,
            u.celular AS cliente_telefono,
            u.correo AS cliente_correo

        FROM proyecto p

        INNER JOIN usuario u 
            ON p.usuario_id = u.idusuario

        ORDER BY p.fecha_inicio DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

        public function crear($data) {

        $sql = "INSERT INTO proyecto (
            nombre_proyecto,
            descripcion,
            direccion,
            ubicacion,
            fecha_inicio,
            fecha_fin_estimada,
            estado_proyecto,
            estado_contrato,
            porcentaje_avance,
            porcentaje_pagado,
            usuario_id,
            cotizacion_id
        ) VALUES (?, ?, ?, ?, ?, ?, 'pendiente', 'pendiente', 0, 0, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['nombre_proyecto'],
            $data['descripcion'],
            $data['direccion'],
            $data['ubicacion'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['usuario_id'],
            $data['cotizacion_id']
        ]);
    }
}