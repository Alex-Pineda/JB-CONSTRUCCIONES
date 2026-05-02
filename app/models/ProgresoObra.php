<?php
require_once __DIR__ . '/../../config/data.php';
require_once __DIR__ . '/../../config/data.php';

class ProgresoObra {

    private $conn;

    public function __construct() {
        $db = new Data();
        $this->conn = $db->getConnection();
    }

    public function crear($data) {

        $sql = "INSERT INTO progreso_obra(
                    proyecto_id,
                    usuario_id,
                    titulo,
                    descripcion,
                    youtube_video_id,
                    tipo_video,
                    porcentaje_aporte,
                    aprobado,
                    estado
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, 'publicado')";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['proyecto_id'],
            $data['usuario_id'],
            $data['titulo'],
            $data['descripcion'],
            $data['youtube_video_id'],
            $data['tipo_video'],
            $data['porcentaje_aporte']
        ]);
    }

    public function listar($idproyecto) {

        $sql = "SELECT 
                    p.*,
                    u.celular,
                    u.nombres,
                    u.apellidos
                FROM progreso_obra p
                INNER JOIN usuario u
                    ON p.usuario_id = u.idusuario
                WHERE p.proyecto_id = ?
                ORDER BY p.idprogreso DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idproyecto]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {

        $sql = "DELETE FROM progreso_obra WHERE idprogreso = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id]);
    }

    public function recalcularProyecto($idproyecto) {

        $sql = "SELECT SUM(porcentaje_aporte) total
                FROM progreso_obra
                WHERE proyecto_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idproyecto]);

        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        if($total > 100) $total = 100;

        $sql2 = "UPDATE proyecto
                 SET porcentaje_avance = ?
                 WHERE idproyecto = ?";

        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([$total, $idproyecto]);

        return $total;
    }
}