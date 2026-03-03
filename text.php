<?php

require_once "Database/Data.php";

$database = new Data();
$db = $database->getConnection();

if($db){
    echo "Conexión exitosa";
} else {
    echo "Error de conexión";
}