<?php
$env = parse_ini_file(__DIR__.'/.env');

if ($env === false) {
    die("Error: No se pudo leer el archivo de configuración .env.");
}

$servername = $env["servidor"];
$username = $env["usuario"];
$password = $env["clave"];
$dbname = $env["basedatos"];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>