<?php

$server   = "redsummacloud.com";
$username = "redsummacloud_control";
$password = "j:6+v)HrY9}?#kZ";
$database = "redsummacloud_controlacceso";


$mysqli = new mysqli($server, $username, $password, $database);


if ($mysqli->connect_error) {
    die('error'.$mysqli->connect_error);
}
$mysqli->query("SET lc_time_names = 'es_ES'");