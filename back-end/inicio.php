<?php
include('../config/database.php');
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {
    $indice = $_GET['indice'];
    $evento = $_GET['id_evento'];
    $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE indice='$indice' AND e.id_evento=a.id_evento AND e.id_evento=$evento");
    if (!$query) {
        die('Error en la consulta' . mysqli_error($mysqli));
    }
    $rowcount = mysqli_num_rows($query);
    $json = array();
    if ($rowcount != 0) {
        while ($row = mysqli_fetch_array($query)) {
            $query_count = mysqli_query($mysqli, "SELECT COUNT(*) total FROM asistencias WHERE cod_alumno='$indice'");
            $data = mysqli_fetch_array($query_count);
            $aplico = $data['total']; // Número de veces que el alumno ha aplicado (incluyendo invitados)
            $cupo = $row['cupo']; // Total de cupos del evento

            // Cupos disponibles para invitados (total de cupos - 1 para el alumno)
            $cuposParaInvitados = $cupo - 1;

            // Calculamos los cupos restantes para invitados
            $cuposRestantes = $cuposParaInvitados - ($aplico - 1); // Restamos 1 porque el alumno ya ocupa un cupo

            if ($aplico >= $cupo) {
                $res = '<div class="alert alert-danger" role="alert" id="alerta_add">
                    <strong>Error!</strong> Sin cupo de ingreso para invitados.
                </div>';
                $btn = 0;
            } else {
                $res = '<div class="alert alert-success" role="alert" id="alerta_add">
                    <strong>Bienvenido!</strong> Por favor siga. Tienes ' . ($cuposParaInvitados - $cuposRestantes + 1) . ' de ' . $cuposParaInvitados . ' cupos para invitados disponibles.
                </div>';
                $btn = 1;
            }

            $json[] = array(
                'alumno' => $row['alumno'],
                'titulo' => $row['titulo'],
                'indice' => $row['indice'],
                'resultado' => $res,
                'btn' => $btn
            );
        }

        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
    } else {
        $res = '<div class="alert alert-danger" role="alert" id="alerta_add">
                    <strong>Error!</strong> No se encontraron datos con el índice y evento indicado.
                </div>';
        $json[] = array(
            'alumno' => 'Sin Datos',
            'titulo' => 'Sin Datos',
            'indice' => 'Sin Datos',
            'resultado' => $res,
            'btn' => 0
        );
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
    }
}




if($metodo=='POST'){
    if(isset($_POST['indice'])){
        $indice = $_POST['indice'];
        $query = mysqli_query($mysqli, "INSERT INTO asistencias VALUES (NULL,'$indice',0)");
        if(!$query){
            die('Error en la consulta'. mysqli_error($mysqli));
        }
        echo 'Registro Exitoso';
    }
    if(isset($_POST['indice_a'])){
        $indice = $_POST['indice_a'];
        $evento = $_POST['evento'];
        $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE indice='$indice' AND e.id_evento=a.id_evento AND e.id_evento=$evento");
        if(!$query){
            die('Error en la consulta'. mysqli_error($mysqli));
        }
        $row = mysqli_fetch_array($query);
        $nombre = $row['alumno'];
        $rowcount=mysqli_num_rows($query);
        if($rowcount != 0){
            $result = mysqli_query($mysqli, "SELECT * FROM asistencias WHERE cod_alumno='$indice' and reservado=1");
            $num = mysqli_num_rows($result);
            if($num == 0){
                $query = mysqli_query($mysqli, "INSERT INTO asistencias VALUES (NULL,'$indice',1)");
                if(!$query){
                    die('Error en la consulta'. mysqli_error($mysqli));
                }
                echo '1'.$nombre;
            }else{
                echo '0'.$nombre;
            }
        }else{echo '2';}
        
    }
    

}