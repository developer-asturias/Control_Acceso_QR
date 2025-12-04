<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include('../config/database.php');
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {
    $indice = isset($_GET['indice']) ? mysqli_real_escape_string($mysqli, $_GET['indice']) : '';
    $evento = isset($_GET['id_evento']) ? intval($_GET['id_evento']) : 0;
    
    if (empty($indice) || $evento <= 0) {
        echo json_encode([
            'alumno' => 'Sin Datos',
            'titulo' => 'Sin Datos',
            'indice' => 'Sin Datos',
            'resultado' => '<div class="alert alert-danger">Error: Parámetros inválidos</div>',
            'btn' => 0
        ]);
        exit;
    }
    
    $query = mysqli_query($mysqli, "SELECT a.*, e.cupo FROM alumnos a JOIN eventos e ON a.id_evento=e.id_evento WHERE a.indice='$indice' AND a.id_evento=$evento");
    
    if (!$query) {
        die('Error en la consulta: ' . mysqli_error($mysqli));
    }
    
    $rowcount = mysqli_num_rows($query);
    
    if ($rowcount != 0) {
        $row = mysqli_fetch_array($query);
        
        // Contar asistencias confirmadas (invitados que ya ingresaron)
        $query_invitados = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM asistencias WHERE cod_alumno='$indice' AND reservado=1");
        $data_invitados = mysqli_fetch_array($query_invitados);
        $invitadosRegistrados = intval($data_invitados['total']);
        
        $cupoTotal = intval($row['cupo']); // Total de cupos (alumno + invitados)
        $cuposParaInvitados = $cupoTotal - 1; // Cupos disponibles para invitados (restamos 1 para el alumno)
        $cuposDisponibles = $cuposParaInvitados - $invitadosRegistrados; // Cupos restantes
        
        if ($cuposDisponibles <= 0) {
            $res = '<div class="alert alert-danger" role="alert" id="alerta_add">
                <strong>Error!</strong> Sin cupo de ingreso para invitados. Se han usado todos los ' . $cuposParaInvitados . ' cupos disponibles.
            </div>';
            $btn = 0;
        } else {
            $res = '<div class="alert alert-success" role="alert" id="alerta_add">
                <strong>¡Bienvenido!</strong> Por favor siga. Tienes <strong>' . $cuposDisponibles . ' de ' . $cuposParaInvitados . '</strong> cupos para invitados disponibles.
            </div>';
            $btn = 1;
        }

        $json = [
            'alumno' => $row['alumno'] ?? '',
            'titulo' => $row['titulo'] ?? '',
            'indice' => $row['indice'] ?? '',
            'resultado' => $res,
            'btn' => $btn,
            'cuposDisponibles' => $cuposDisponibles,
            'cuposParaInvitados' => $cuposParaInvitados
        ];
        
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    } else {
        $res = '<div class="alert alert-danger" role="alert" id="alerta_add">
            <strong>Error!</strong> No se encontraron datos con el índice y evento indicado.
        </div>';
        $json = [
            'alumno' => 'Sin Datos',
            'titulo' => 'Sin Datos',
            'indice' => 'Sin Datos',
            'resultado' => $res,
            'btn' => 0,
            'cuposDisponibles' => 0,
            'cuposParaInvitados' => 0
        ];
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }
}




if($metodo=='POST'){
    if(isset($_POST['indice'])){
        $indice = mysqli_real_escape_string($mysqli, $_POST['indice']);
        
        // Registrar invitado (reservado=1)
        $query = mysqli_query($mysqli, "INSERT INTO asistencias (cod_alumno, reservado) VALUES ('$indice', 1)");
        if(!$query){
            die('Error en la consulta: ' . mysqli_error($mysqli));
        }
        echo json_encode(['success' => true, 'message' => 'Invitado registrado exitosamente'], JSON_UNESCAPED_UNICODE);
    }
    elseif(isset($_POST['indice_a'])){
        $indice = mysqli_real_escape_string($mysqli, $_POST['indice_a']);
        $evento = intval($_POST['evento']);
        
        // Verificar que el alumno existe y pertenece al evento
        $query = mysqli_query($mysqli, "SELECT a.alumno, a.cupo FROM alumnos a JOIN eventos e ON a.id_evento=e.id_evento WHERE a.indice='$indice' AND a.id_evento=$evento");
        
        if(!$query){
            die('Error en la consulta: ' . mysqli_error($mysqli));
        }
        
        $rowcount = mysqli_num_rows($query);
        
        if($rowcount != 0){
            $row = mysqli_fetch_array($query);
            $nombre = $row['alumno'];
            $cupoTotal = intval($row['cupo']);
            $cuposParaInvitados = $cupoTotal - 1;
            
            // Contar invitados ya registrados
            $result = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM asistencias WHERE cod_alumno='$indice' AND reservado=1");
            $data = mysqli_fetch_array($result);
            $invitadosRegistrados = intval($data['total']);
            
            // Verificar si hay cupos disponibles
            if($invitadosRegistrados >= $cuposParaInvitados){
                echo '0' . $nombre; // Sin cupos disponibles
            } else {
                // Registrar nuevo invitado
                $query_insert = mysqli_query($mysqli, "INSERT INTO asistencias (cod_alumno, reservado) VALUES ('$indice', 1)");
                if(!$query_insert){
                    die('Error en la consulta: ' . mysqli_error($mysqli));
                }
                echo '1' . $nombre; // Éxito
            }
        } else {
            echo '2'; // Alumno no encontrado en el evento
        }
    }
}