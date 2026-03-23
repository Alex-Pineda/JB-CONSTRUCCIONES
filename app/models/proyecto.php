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

            INNER JOIN usuario_has_rol ur 
                ON u.idusuario = ur.usuario_idusuario

            WHERE ur.rol_idrol = 2

            ORDER BY p.fecha_inicio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}