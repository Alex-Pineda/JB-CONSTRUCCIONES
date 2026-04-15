<?php
// 1. RUTAS DINÁMICAS
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];

if ($domain === 'localhost') {
    define('BASE_URL', $protocol . $domain . '/JB-CONSTRUCCIONES/');
    
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'jb_constructores'); // Verifica si es 'jb_construcciones' o 'jb_constructores'
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('BASE_URL', $protocol . $domain . '/');
    
    define('DB_HOST', $_ENV['MYSQLHOST'] ?? '');
    define('DB_NAME', $_ENV['MYSQLDATABASE'] ?? '');
    define('DB_USER', $_ENV['MYSQLUSER'] ?? '');
    define('DB_PASS', $_ENV['MYSQLPASSWORD'] ?? '');
}

// 2. CONEXIÓN PROCEDURAL (Opcional, si la usas en algún lado)
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conexion->connect_error) {
    // En producción es mejor no mostrar el error detallado al usuario
    error_log("Error de conexión mysqli: " . $conexion->connect_error);
}

// 3. CLASE DATA (La que usan tus modelos) - ¡CORREGIDA!
class Data {
    // Usamos las constantes definidas arriba para que cambien solas según el servidor
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // En producción, es mejor usar error_log y mostrar un mensaje genérico
            error_log("Error de conexión PDO: " . $exception->getMessage());
            echo "Error de conexión. Por favor, intente más tarde.";
        }

        return $this->conn;
    }
}