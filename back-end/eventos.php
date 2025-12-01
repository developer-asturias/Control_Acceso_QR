<?php
// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Capturar errores fatales
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("ERROR: $errstr en $errfile:$errline");
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor'], JSON_UNESCAPED_UNICODE);
    exit;
});

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json; charset=utf-8');

// Evitar almacenamiento en caché
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Incluir la conexión a la base de datos
if (!file_exists('../config/database.php')) {
    http_response_code(500);
    echo json_encode(['error' => 'No se encuentra config/database.php']);
    exit;
}
include('../config/database.php');

if (!isset($mysqli) || !$mysqli) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a base de datos']);
    exit;
}

// Función para enviar respuestas JSON estandarizadas
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Obtener el método de la petición
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {

    try {
        if (isset($_GET['detalle'])) {
            // Código para el detalle del evento
            if (!isset($_GET['id_evento']) || !is_numeric($_GET['id_evento'])) {
                sendJsonResponse(['error' => 'ID de evento no válido'], 400);
            }
            
            $id_evento = intval($_GET['id_evento']);
            $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE e.id_evento=$id_evento AND a.id_evento=e.id_evento");
            
            if (!$query) {
                throw new Exception('Error en la consulta de detalle: ' . mysqli_error($mysqli));
            }

            $json = array();
            while ($row = mysqli_fetch_assoc($query)) {
                $indice = $row['indice'];
                
                // Contar asistencias
                $query_count = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM asistencias WHERE cod_alumno='$indice'");
                $data = mysqli_fetch_assoc($query_count);
                $aplico = $data['total'];
                
                // Contar asistencias confirmadas
                $query_asist = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM asistencias WHERE cod_alumno='$indice' AND reservado=1");
                $data_asis = mysqli_fetch_assoc($query_asist);
                $asistencia = $data_asis['total'];
                
                $json[] = array(
                    'alumno' => $row['alumno'],
                    'codigo' => $row['identificacion'],
                    'titulo' => $row['titulo'],
                    'email' => $row['email'],
                    'evento' => $row['evento'],
                    'asistencia' => ($asistencia > 0) ? 'SI' : 'NO',
                    'cupo' => $row['cupo'],
                    'aplico' => $aplico
                );
            }
            
            sendJsonResponse($json);
        }
        elseif (isset($_GET['p'])) {
            // Consulta principal de eventos
            $query = mysqli_query($mysqli, "SELECT e.*, i.name AS nombre_institucion 
                                          FROM eventos e 
                                          LEFT JOIN instituciones i ON e.institucion = i.id
                                          ORDER BY e.fecha DESC, e.hora DESC");
            
            if (!$query) {
                throw new Exception('Error en la consulta de eventos: ' . mysqli_error($mysqli));
            }

            $json = array();
            while ($row = mysqli_fetch_assoc($query)) {
                $estado = ($row['estado_evento'] == 1) ? 
                    ['clase' => 'badge-success', 'texto' => 'Abierto'] : 
                    ['clase' => 'badge-danger', 'texto' => 'Cerrado'];
                
                $btn = "<a href='?detalles#{$row['id_evento']}' class='btn btn-success btn-sm' title='Detalles'>
                        <i class='fas fa-edit'></i></a>";
                
                if ($row['estado_evento'] == 1) {
                    $btn .= " <a href='#' onclick='cerrar({$row['id_evento']})' class='btn btn-danger btn-sm' title='Cerrar'>
                            <i class='fas fa-times'></i></a>";
                }

                $json[] = array(
                    'evento' => htmlspecialchars($row['evento'], ENT_QUOTES, 'UTF-8'),
                    'lugar' => htmlspecialchars($row['lugar'], ENT_QUOTES, 'UTF-8'),
                    'direccion' => htmlspecialchars($row['direccion'], ENT_QUOTES, 'UTF-8'),
                    'fecha' => $row['fecha'] . ' ' . $row['hora'],
                    'estado' => "<span class='badge {$estado['clase']}'>{$estado['texto']}</span>",
                    'institucion' => htmlspecialchars($row['nombre_institucion'] ?? 'Sin institución', ENT_QUOTES, 'UTF-8'),
                    'btn' => $btn
                );
            }
            
            sendJsonResponse($json);
        }
        elseif (isset($_GET['list'])) {
            // Lista simple de eventos activos
            $query = mysqli_query($mysqli, "SELECT id_evento, evento, lugar, fecha, hora FROM eventos WHERE estado_evento=1 ORDER BY fecha DESC, hora DESC");
            
            if (!$query) {
                throw new Exception('Error en la consulta de lista: ' . mysqli_error($mysqli));
            }

            $json = array();
            $num_rows = mysqli_num_rows($query);
            
            while ($row = mysqli_fetch_assoc($query)) {
                $json[] = array(
                    'id' => intval($row['id_evento']),
                    'evento' => $row['evento'] ?? '',
                    'lugar' => $row['lugar'] ?? '',
                    'fecha' => $row['fecha'] ?? '',
                    'hora' => $row['hora'] ?? ''
                );
            }
            
            sendJsonResponse($json);
        }
        elseif (isset($_GET['contador'])) {
            // Contador de asistencias
            if (!isset($_GET['id_eventoc']) || !is_numeric($_GET['id_eventoc'])) {
                sendJsonResponse(['error' => 'ID de evento no válido para contador'], 400);
            }
            
            $id_evento = intval($_GET['id_eventoc']);
            
            // Contar asistencias confirmadas
            $query = mysqli_query($mysqli, "SELECT COUNT(*) as total 
                                          FROM asistencias a 
                                          JOIN alumnos al ON al.indice = a.cod_alumno 
                                          WHERE al.id_evento = $id_evento AND a.reservado = 1");
            
            if (!$query) {
                throw new Exception('Error en la consulta de contador: ' . mysqli_error($mysqli));
            }
            
            $row = mysqli_fetch_assoc($query);
            $asis = intval($row['total']);

            // Contar total de invitados
            $query_in = mysqli_query($mysqli, "SELECT COUNT(*) as total 
                                             FROM asistencias a 
                                             JOIN alumnos al ON al.indice = a.cod_alumno 
                                             WHERE al.id_evento = $id_evento");
            
            if (!$query_in) {
                throw new Exception('Error en la consulta de invitados: ' . mysqli_error($mysqli));
            }
            
            $row_in = mysqli_fetch_assoc($query_in);
            $inv = intval($row_in['total']);

            sendJsonResponse([
                'asistencia' => $asis,
                'invitados' => $inv
            ]);
        }
        else {
            sendJsonResponse(['error' => 'Parámetro no válido'], 400);
        }
    } 
    catch (Exception $e) {
        error_log("ERROR eventos.php GET: " . $e->getMessage());
        sendJsonResponse(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
    }
}
elseif ($metodo == 'POST') {
    // Procesar solicitudes POST
    try {
        if (isset($_POST['nombre_e'])) {
            // Validar y limpiar datos de entrada
            $required = ['nombre_e', 'lugar_e', 'fecha_e', 'hora_e', 'direccione', 'institucion'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    sendJsonResponse(['error' => "El campo $field es requerido"], 400);
                }
            }

            $nombre = mysqli_real_escape_string($mysqli, $_POST['nombre_e']);
            $lugar = mysqli_real_escape_string($mysqli, $_POST['lugar_e']);
            $fecha = mysqli_real_escape_string($mysqli, $_POST['fecha_e']);
            $hora = mysqli_real_escape_string($mysqli, $_POST['hora_e']);
            $direccion = mysqli_real_escape_string($mysqli, $_POST['direccione']);
            $link1 = isset($_POST['link1']) ? mysqli_real_escape_string($mysqli, $_POST['link1']) : '';
            $link2 = isset($_POST['link2']) ? mysqli_real_escape_string($mysqli, $_POST['link2']) : '';
            $institucion = intval($_POST['institucion']);

            // Procesar archivo subido si existe
            $ruta_file = '';
            if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
                $target_dir = '../Archivos/';
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                
                $file_name = preg_replace('/\s+/', '', basename($_FILES["fileToUpload"]["name"]));
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $ruta_file = $file_name;
                }
            }

            // Insertar en la base de datos
            $query = "INSERT INTO eventos (evento, lugar, direccion, fecha, hora, link_1, link_2, archivo, estado_evento, institucion) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
            
            $stmt = mysqli_prepare($mysqli, $query);
            mysqli_stmt_bind_param($stmt, "ssssssssi", 
                $nombre, $lugar, $direccion, $fecha, $hora, $link1, $link2, $ruta_file, $institucion);
            
            if (mysqli_stmt_execute($stmt)) {
                $id_evento = mysqli_insert_id($mysqli);
                sendJsonResponse([
                    'success' => true,
                    'message' => 'Evento registrado exitosamente',
                    'id_evento' => $id_evento
                ]);
            } else {
                throw new Exception('Error al registrar el evento: ' . mysqli_error($mysqli));
            }
        }
        elseif (isset($_POST['id_evento'])) {
            // Cerrar evento
            $id = intval($_POST['id_evento']);
            $query = "UPDATE eventos SET estado_evento = 2 WHERE id_evento = ?";
            
            $stmt = mysqli_prepare($mysqli, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            if (mysqli_stmt_execute($stmt)) {
                sendJsonResponse(['success' => true, 'message' => 'Evento cerrado exitosamente']);
            } else {
                throw new Exception('Error al cerrar el evento: ' . mysqli_error($mysqli));
            }
        }
        else {
            sendJsonResponse(['error' => 'Acción no válida'], 400);
        }
    } 
    catch (Exception $e) {
        error_log("ERROR eventos.php POST: " . $e->getMessage());
        sendJsonResponse(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
    }
}
else {
    sendJsonResponse(['error' => 'Método no permitido'], 405);
}