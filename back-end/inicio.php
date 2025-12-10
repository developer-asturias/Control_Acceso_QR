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
        
        // CORREGIDO: Contar asistencias solo del evento actual usando JOIN con alumnos
        $query_count = mysqli_query($mysqli, "
            SELECT COUNT(*) as total 
            FROM asistencias ast
            INNER JOIN alumnos a ON ast.cod_alumno = a.indice
            WHERE ast.cod_alumno='$indice' 
            AND a.id_evento=$evento
        ");
        
        if (!$query_count) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al contar asistencias: ' . mysqli_error($mysqli)]);
            exit;
        }
        
        $data = mysqli_fetch_array($query_count);
        $aplico = intval($data['total'] ?? 0);
        $cupo = intval($row['cupo'] ?? 0);

        // Validar si ya alcanzó el límite total de cupos
        if ($aplico >= $cupo) {
            $res = '<div class="alert alert-danger" role="alert" id="alerta_add">
                <strong>Error!</strong> Sin cupo de ingreso para invitados. Ya se alcanzó el límite.
            </div>';
            $btn = 0;
        } else {
            // Cupos disponibles para invitados (total de cupos - 1 para el alumno)
            $cuposParaInvitados = $cupo - 1;
            
            // Cuántos invitados YA INGRESARON (sin contar al alumno)
            $invitadosIngresados = max(0, $aplico - 1);
            
            // Cuántos cupos quedan disponibles para invitados
            $cuposRestantes = $cuposParaInvitados - $invitadosIngresados;
            
            // $res = '<div class="alert alert-success" role="alert" id="alerta_add">
            //     <strong>Bienvenido!</strong> Por favor siga. Tienes ' . $cuposRestantes . ' de ' . $cuposParaInvitados . ' cupos para invitados disponibles.
            // </div>';
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
    if (isset($_POST['indice']) && isset($_POST['evento']) && !isset($_POST['indice_a'])) {
        // Registro de ALUMNO o INVITADO
        $indice = mysqli_real_escape_string($mysqli, $_POST['indice']);
        $evento = intval($_POST['evento']);
        
        // Obtener datos del alumno para este evento específico
        $query_alumno = mysqli_query($mysqli, "SELECT id_evento, cupo FROM alumnos WHERE indice='$indice' AND id_evento=$evento");
        if (!$query_alumno || mysqli_num_rows($query_alumno) == 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Alumno no encontrado en este evento']);
            exit;
        }
        
        $alumno_data = mysqli_fetch_array($query_alumno);
        $cupo_total = intval($alumno_data['cupo']);
        
        // Contar asistencias actuales del alumno en este evento
        $query_count = mysqli_query($mysqli, "
            SELECT COUNT(*) as total 
            FROM asistencias ast
            INNER JOIN alumnos a ON ast.cod_alumno = a.indice
            WHERE ast.cod_alumno='$indice' 
            AND a.id_evento=$evento
        ");
        
        $count_data = mysqli_fetch_array($query_count);
        $asistencias_actuales = intval($count_data['total']);
        
        // Verificar si ya alcanzó el límite de cupos
        if ($asistencias_actuales >= $cupo_total) {
            http_response_code(400);
            echo json_encode(['error' => 'Sin cupos disponibles. Ya se alcanzó el límite.']);
            exit;
        }
        
        // Verificar si ya tiene registro con reservado=1 (alumno ya entró)
        $query_reservado = mysqli_query($mysqli, "
            SELECT COUNT(*) as tiene_reserva 
            FROM asistencias ast
            INNER JOIN alumnos a ON ast.cod_alumno = a.indice
            WHERE ast.cod_alumno='$indice' 
            AND ast.reservado=1
            AND a.id_evento=$evento
        ");
        
        $reservado_data = mysqli_fetch_array($query_reservado);
        $tiene_reserva = intval($reservado_data['tiene_reserva']) > 0;
        
        // Si no tiene reserva, este es el registro del alumno (reservado=1)
        // Si ya tiene reserva, este es un invitado (reservado=0)
        $reservado = $tiene_reserva ? 0 : 1;
        
        // Insertar registro
        $query = mysqli_query($mysqli, "INSERT INTO asistencias VALUES (NULL,'$indice',$reservado)");
        if (!$query) {
            http_response_code(500);
            echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($mysqli)]);
            exit;
        }
        
        $mensaje = $reservado == 1 ? 'Alumno registrado exitosamente' : 'Invitado registrado exitosamente';
        echo json_encode(['success' => true, 'message' => $mensaje]);
    } 
    elseif (isset($_POST['indice_a']) && isset($_POST['evento'])) {
        // CORREGIDO: Registro del estudiante (reservado=1)
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
        
        // Verificar si ya tiene registro de estudiante (reservado=1) en este evento
        $result = mysqli_query($mysqli, "
            SELECT ast.* 
            FROM asistencias ast
            INNER JOIN alumnos a ON ast.cod_alumno = a.indice
            WHERE ast.cod_alumno='$indice' 
            AND ast.reservado=1
            AND a.id_evento=$evento
        ");
        
        if (!$result) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al verificar asistencia: ' . mysqli_error($mysqli)]);
            exit;
        }
        
        $num = mysqli_num_rows($result);
        
        if ($num == 0) {
            // Insertar registro del estudiante (reservado=1)
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
?>