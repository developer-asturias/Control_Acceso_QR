<?php
include('../config/database.php');
include('enviar_email.php');
$metodo=$_SERVER['REQUEST_METHOD'];

if($metodo=='GET'){
    if(isset($_GET['id_evento'])){
        $id = $_GET['id_evento'];
        $query_s = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE e.id_evento=4 AND a.id_evento=e.id_evento AND id_alumno > 780");
        if(!$query_s){
            die('Error en la consulta'. mysqli_error($mysqli));
        }
        $nro = 1;
        while($row = mysqli_fetch_array($query_s)){
            $evento = $row['evento'];
            $fecha = $row['fecha'];
            $hora = date("g:i a",strtotime($row['hora']));
            $direccion = $row['direccion'];
            $lugar = $row['lugar'];
            $indice =$row['indice'];
            $email = $row['email'];
            $nombre = $row['alumno'];
            $id_alumno = $row['id_alumno'];
            //echo $id_alumno.' '.$email.' '.$nombre.' '.$indice.' '.$evento.'<br>';
            enviar($email,$nombre,$indice,$evento,$lugar,$fecha,$hora,$direccion);
            echo $nro." Se envio email a:".$email."<br>";
            $nro++;
        }
    }
}