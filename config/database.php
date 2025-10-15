<?php

$server   = "localhost";
$username = "solicitu_usuario";
$password = "30D&#Wd]YN*F";
$database = "solicitu_control_acceso";


$mysqli = new mysqli($server, $username, $password, $database);


if ($mysqli->connect_error) {
    die('error'.$mysqli->connect_error);
}
$mysqli->query("SET lc_time_names = 'es_ES'");
?>