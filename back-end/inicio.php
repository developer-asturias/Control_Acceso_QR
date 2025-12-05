<?php
header('Content-Type: application/json; charset=utf-8');
include('../config/database.php');

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {
    // Validar y sanitizar entrada
    $indice = isset($_GET['indice']) ? mysqli_real_escape_string($mysqli, $_GET['indice']) : '';
    $evento = isset($_GET['id_evento']) ? intval($_GET['id_evento']) : 0;
    
    if (empty($indice) || $evento <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Parámetros inválidos']);
        exit;
    }
    
    $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE indice='$indice' AND e.id_evento=a.id_evento AND e.id_evento=$evento");
    if (!$query) {
        http_response_code(500);
        echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($mysqli)]);
        exit;
    }
    
    $rowcount = mysqli_num_rows($query);
    
    if ($rowcount != 0) {
        $row = mysqli_fetch_array($query);
        
        $query_count = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM asistencias WHERE cod_alumno='$indice'");
        $data = mysqli_fetch_array($query_count);
        $aplico = intval($data['total'] ?? 0);
        $cupo = intval($row['cupo'] ?? 0);

        // Cupos disponibles para invitados (total de cupos - 1 para el alumno)
        $cuposParaInvitados = $cupo - 1;
        $cuposRestantes = $cuposParaInvitados - ($aplico - 1);

        if ($aplico >= $cupo) {
            $res = '<div class="alert alert-danger" role="alert" id="alerta_add">
                <strong>Error!</strong> Sin cupo de ingreso para invitados.
            </div>';
            $btn = 0;
        } else {
            $cuposDisponibles = $cuposParaInvitados - $cuposRestantes + 1;
            $res = '<div class="alert alert-success" role="alert" id="alerta_add">
                <strong>Bienvenido!</strong> Por favor siga. Tienes ' . $cuposDisponibles . ' de ' . $cuposParaInvitados . ' cupos para invitados disponibles.
            </div>';
            $btn = 1;
        }

        $json = array(
            'alumno' => $row['alumno'] ?? 'Sin Datos',
            'titulo' => $row['titulo'] ?? 'Sin Datos',
            'indice' => $row['indice'] ?? 'Sin Datos',
            'resultado' => $res,
            'btn' => $btn
        );

        echo json_encode($json);
    } else {
        $res = '<div class="alert alert-danger" role="alert" id="alerta_add">
            <strong>Error!</strong> No se encontraron datos con el índice y evento indicado.
        </div>';
        $json = array(
            'alumno' => 'Sin Datos',
            'titulo' => 'Sin Datos',
            'indice' => 'Sin Datos',
            'resultado' => $res,
            'btn' => 0
        );
        echo json_encode($json);
    }
}

if ($metodo == 'POST') {
    if (isset($_POST['indice'])) {
        $indice = mysqli_real_escape_string($mysqli, $_POST['indice']);
        $query = mysqli_query($mysqli, "INSERT INTO asistencias VALUES (NULL,'$indice',0)");
        if (!$query) {
            http_response_code(500);
            echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($mysqli)]);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Registro Exitoso']);
    } 
    elseif (isset($_POST['indice_a']) && isset($_POST['evento'])) {
        $indice = mysqli_real_escape_string($mysqli, $_POST['indice_a']);
        $evento = intval($_POST['evento']);
        
        $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE indice='$indice' AND e.id_evento=a.id_evento AND e.id_evento=$evento");
        if (!$query) {
            http_response_code(500);
            echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($mysqli)]);
            exit;
        }
        
        $rowcount = mysqli_num_rows($query);
        if ($rowcount == 0) {
            echo json_encode(['code' => 2, 'message' => 'Índice no relacionado al evento o no existe']);
            exit;
        }
        
        $row = mysqli_fetch_array($query);
        $nombre = $row['alumno'] ?? 'Desconocido';
        
        $result = mysqli_query($mysqli, "SELECT * FROM asistencias WHERE cod_alumno='$indice' AND reservado=1");
        $num = mysqli_num_rows($result);
        
        if ($num == 0) {
            $query = mysqli_query($mysqli, "INSERT INTO asistencias VALUES (NULL,'$indice',1)");
            if (!$query) {
                http_response_code(500);
                echo json_encode(['error' => 'Error al registrar asistencia: ' . mysqli_error($mysqli)]);
                exit;
            }
            echo json_encode(['code' => 1, 'message' => 'Asistencia Confirmada', 'nombre' => $nombre]);
        } else {
            echo json_encode(['code' => 0, 'message' => 'Ya cuenta con registro de asistencia', 'nombre' => $nombre]);
        }
    } 
    else {
        http_response_code(400);
        echo json_encode(['error' => 'Parámetros inválidos']);
    }
}