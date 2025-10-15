<?php
include('../config/database.php');
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {
    if (isset($_GET['detalle'])) {
        $id_evento = $_GET['id_evento'];
        $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE e.id_evento=$id_evento AND a.id_evento=e.id_evento");
        if (!$query) {
            die('Error en la consulta' . mysqli_error($mysqli));
        }

        $json = array();
        while ($row = mysqli_fetch_array($query)) {
            $indice = $row['indice'];
            //Invitados
            $query_count = mysqli_query($mysqli, "SELECT COUNT(*) total FROM asistencias WHERE cod_alumno='$indice' "); //and reservado=0
            $data = mysqli_fetch_array($query_count);
            $aplico = $data['total'];
            //asistencia
            $query_asist = mysqli_query($mysqli, "SELECT COUNT(*) total FROM asistencias WHERE cod_alumno='$indice' and reservado=1"); //
            $data_asis = mysqli_fetch_array($query_asist);
            $asistencia = $data_asis['total'];
            if ($asistencia > 0) {
                $var = 'SI';
            } else {
                $var = 'NO';
            }
            $json[] = array(
                'alumno' => $row['alumno'],
                'codigo' => $row['identificacion'],
                'titulo' => $row['titulo'],
                'email' => $row['email'],
                'evento' => $row['evento'],
                'asistencia' => $var,
                'cupo' => $row['cupo'],
                'aplico' => $aplico
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    if (isset($_GET['p'])) {
    
        //  $query = mysqli_query($mysqli, "SELECT * FROM eventos");
    
        $query = mysqli_query($mysqli, "SELECT e.*, i.name AS nombre_institucion FROM eventos e LEFT JOIN instituciones i ON e.institucion = i.id");
        if (!$query) {
            $errorMessage = 'Error en la consulta: ' . mysqli_error($mysqli);
            $json = array('error' => $errorMessage);
            $jsonstring = json_encode($json);
            echo $jsonstring;
            exit;
        }

        $json = array();
        while ($row = mysqli_fetch_array($query)) {
            if ($row['estado_evento'] == 1) {
                $est = "<span class='badge badge-success'>Abierto</span>";
                $btn = "
                <a href='?detalles#$row[id_evento]' class='btn btn-success btn-sm' title='Detalles'><i class='fas fa-edit' ></i></a>
                <a href='#' onclick='cerrar($row[id_evento])' class='btn btn-danger btn-sm' title='Cerrar'><i class='fas fa-times' ></i></a>";
            }
            if ($row['estado_evento'] == 2) {
                $est = "<span class='badge badge-danger'>Cerrado</span>";
                $btn = "<a href='?detalles#$row[id_evento]' class='btn btn-success btn-sm' title='Detalles'><i class='fas fa-edit' ></i></a>";
            }
            $json[] = array(
                'evento' => $row['evento'],
                'lugar' => $row['lugar'],
                'direccion' => $row['direccion'],
                'fecha' => $row['fecha'] . ' ' . $row['hora'],
                'estado' => $est,
                'institucion' => $row['nombre_institucion'],
                'btn' => $btn
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    if (isset($_GET['list'])) {
        $query = mysqli_query($mysqli, "SELECT * FROM eventos WHERE estado_evento=1");
        if (!$query) {
            die('Error en la consulta' . mysqli_error($mysqli));
        }

        $json = array();
        while ($row = mysqli_fetch_array($query)) {
            $json[] = array(
                'evento' => $row['evento'],
                'lugar' => $row['lugar'],
                'fecha' => $row['fecha'],
                'id' => $row['id_evento']
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
    if (isset($_GET['contador'])) {
        $id_evento = $_GET['id_eventoc'];
        $query = mysqli_query($mysqli, "SELECT COUNT(*) asistencia FROM asistencias a, alumnos al, eventos e WHERE al.indice=a.cod_alumno and al.id_evento=e.id_evento AND e.id_evento=$id_evento and a.reservado=1 GROUP BY e.id_evento");
        if (!$query) {
            die('Error en la consulta' . mysqli_error($mysqli));
        }
        $row = mysqli_fetch_array($query);
        $asis = $row['asistencia'];

        //-------Invitados
        $query_in = mysqli_query($mysqli, "SELECT COUNT(*) invitados FROM asistencias a, alumnos al, eventos e WHERE al.indice=a.cod_alumno and al.id_evento=e.id_evento AND e.id_evento=$id_evento GROUP BY e.id_evento");
        if (!$query_in) {
            die('Error en la consulta' . mysqli_error($mysqli));
        }
        $row_in = mysqli_fetch_array($query_in);
        $inv = $row_in['invitados'];

        $json = array();
        $json[] = array(
            'asistencia' => $asis,
            'invitados' => $inv
        );
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
    }
}
if ($metodo == 'POST') {
    if (isset($_POST['nombre_e'])) {
        $nombre = $_POST['nombre_e'];
        $lugar = $_POST['lugar_e'];
        $fecha = $_POST['fecha_e'];
        $hora = $_POST['hora_e'];
        $direccion = $_POST['direccione'];
        $link1 = $_POST['link1'];
        $link2 = $_POST['link2'];
        $institucion = $_POST['institucion'];


        $target_file = '../Archivos/' . preg_replace('/\s+/', '', basename($_FILES["fileToUpload"]["name"]));
        $ruta_file = preg_replace('/\s+/', '', basename($_FILES["fileToUpload"]["name"]));
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

        // $query = mysqli_query($mysqli, "INSERT INTO eventos VALUES (NULL,'$nombre','$lugar','$direccion','$fecha','$hora','$link1','$link2','$ruta_file',1, '$institucion')");
        $query = mysqli_query($mysqli, "INSERT INTO eventos (evento, lugar, direccion, fecha, hora, link_1, link_2, archivo, estado_evento, institucion) 
                               VALUES ('$nombre', '$lugar', '$direccion', '$fecha', '$hora', '$link1', '$link2', '$ruta_file', 1, '$institucion')");

        if (!$query) {
            die('Error en el registro' . mysqli_error($mysqli));
        }

        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Evento registrado',
                text: 'El evento ha sido registrado satisfactoriamente!',
                confirmButtonText: 'OK'
            });
        </script>
        ";
    }
    if (isset($_POST['id_evento'])) {
        $id = $_POST['id_evento'];
        $query = mysqli_query($mysqli, "UPDATE eventos SET estado_evento='2' WHERE id_evento=$id");
        echo 'Registro actualizado';
    }
}
