<?php
$serverName = "DESKTOP-56FEFQK";
$database   = "Inventario";
$uid        = "DFME";
$pwd        = "1234";

$connectionInfo = array(
    "Database" => $database,
    "UID" => $uid,
    "PWD" => $pwd,
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die("❌ Error de conexión: <br>" . print_r(sqlsrv_errors(), true));
} else {
    // echo "Conexión exitosa a SQL Server";
}
?>