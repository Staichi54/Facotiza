<?php
$serverName = "https://webmssql.monsterasp.net";
$database   = "db26219";
$uid        = "db26219";
$pwd        = "SANTAFE2025!";

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
