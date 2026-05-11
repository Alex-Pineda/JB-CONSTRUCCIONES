<?php

require_once __DIR__ . '/../../config/data.php';

class VisitaPendiente
{
    private $conn;

    public function __construct()
    {
        $database = new Data();

        $this->conn = $database->getConnection();
    }


    public function obtenerVisitasPendientes()
    {
        $sql = "SELECT 
                    idcotizacion,
                    nombres,
                    apellidos,
                    numero_documento,
                    correo,
                    contacto,
                    ubicacion,
                    direccion,
                    fecha_visita
                FROM cotizacion
                WHERE ser_contactado = 1
                ORDER BY fecha_visita ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function confirmarVisita($idcotizacion)
    {
        $sql = "UPDATE cotizacion
                SET ser_contactado = 0
                WHERE idcotizacion = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $idcotizacion
        ]);
    }


    public function cancelarVisita($idcotizacion)
    {
        $sql = "UPDATE cotizacion
                SET ser_contactado = 0
                WHERE idcotizacion = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $idcotizacion
        ]);
    }
}