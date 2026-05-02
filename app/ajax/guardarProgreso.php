<?php
session_start();

require_once __DIR__ . '/../models/ProgresoObra.php';

header('Content-Type: application/json');

if(!isset($_SESSION['usuario'])){
    echo json_encode(["success"=>false,"error"=>"Sesión expirada"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

try{

$modelo = new ProgresoObra();

$modelo->crear([
    "proyecto_id"=>$data['proyecto_id'],
    "usuario_id"=>$_SESSION['usuario']['idusuario'],
    "titulo"=>$data['titulo'],
    "descripcion"=>$data['descripcion'],
    "youtube_video_id"=>$data['youtube_video_id'],
    "tipo_video"=>$data['tipo_video'],
    "porcentaje_aporte"=>$data['porcentaje_aporte']
]);

$modelo->recalcularProyecto($data['proyecto_id']);

echo json_encode(["success"=>true]);

}catch(Exception $e){

echo json_encode([
"success"=>false,
"error"=>$e->getMessage()
]);

}