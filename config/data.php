<?php
// 1. RUTAS DINÁMICAS
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];

// Usamos strpos para que si el dominio contiene 'localhost', sepa que estás en tu PC
if (strpos($domain, 'localhost') !== false) {
    define('BASE_URL', $protocol . $domain . '/JB-CONSTRUCCIONES/');
    
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'jb_constructores');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_PORT', '3306');
} else {
    // ESTAMOS EN RAILWAY
    define('BASE_URL', $protocol . $domain . '/');
    
    // Usar getenv() es 100% más seguro en Railway que $_ENV
    define('DB_HOST', getenv('MYSQLHOST'));
    define('DB_NAME', getenv('MYSQLDATABASE'));
    define('DB_USER', getenv('MYSQLUSER'));
    define('DB_PASS', getenv('MYSQLPASSWORD'));
    define('DB_PORT', getenv('MYSQLPORT') ?: '3306');
}

// 2. CONEXIÓN PROCEDURAL
// Solo se activa si las constantes no están vacías
if (DB_HOST) {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($conexion->connect_error) {
        error_log("Error mysqli: " . $conexion->connect_error);
    }
}

// 3. CLASE DATA
class Data {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $port = DB_PORT;
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Añadimos el puerto directamente en la cadena de conexión
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            error_log("Error PDO: " . $exception->getMessage());
            // Esto te ayudará a ver el error real en los logs de Railway
            echo "Error de conexión de base de datos."; 
        }
        return $this->conn;
    }
}