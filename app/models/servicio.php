<?php

require_once __DIR__ . '/../../config/data.php';

class Servicio {

    private $conn;

    public function __construct() {
        $db = new Data();
        $this->conn = $db->getConnection();
    }


    public function obtenerServiciosConCategoria() {
        $sql = "SELECT s.*, c.nombre as categoria 
                FROM servicio s
                INNER JOIN categoria c ON s.idcategoria = c.idcategoria
                WHERE s.activo = 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
