<?php
// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json; charset=utf-8');

// Evitar almacenamiento en caché
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

include('../config/database.php');
// include('enviar_email.php');
// include('phpqrcode/qrlib.php');


// echo 'TEST-1';
// exit;
// Función para enviar respuestas JSON estandarizadas
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$metodo = $_SERVER['REQUEST_METHOD'];

// Log básico
$log_message = "[" . date('Y-m-d H:i:s') . "] alumnos.php - Metodo: $metodo - Params: " . json_encode($_REQUEST) . "\n";
error_log($log_message, 3, 'alumnos_log.txt');
 
if ($metodo == 'GET') {
    try {
        // Búsqueda individual
        if (isset($_GET['id_alumno'])) {
            if (!is_numeric($_GET['id_alumno'])) {
                sendJsonResponse(['error' => 'ID de alumno no válido'], 400);
            }

            $id = intval($_GET['id_alumno']);
            $query = mysqli_query($mysqli, "SELECT * FROM alumnos WHERE id_alumno=$id");
            if (!$query) {
                throw new Exception('Error en la consulta: ' . mysqli_error($mysqli));
            }

            if (mysqli_num_rows($query) === 0) {
                sendJsonResponse(['error' => 'Alumno no encontrado'], 404);
            }

            $row = mysqli_fetch_assoc($query);
            sendJsonResponse([
                'alumno' => $row['alumno'],
                'codigo' => $row['identificacion'],
                'titulo' => $row['titulo'],
                'email' => $row['email'],
                'indice' => $row['indice'],
                'cupo' => $row['cupo'],
            ]);
        }

        // Lista de alumnos para eventos activos
        $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE a.id_evento=e.id_evento AND e.estado_evento=1");
        if (!$query) {
            throw new Exception('Error en la consulta: ' . mysqli_error($mysqli));
        }

        $json = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $btn = "<a href='#' onclick='buscar({$row['id_alumno']})' class='btn btn-success btn-sm' title='Detalles'><i class='fas fa-edit'></i></a>";
            $json[] = [
                'alumno' => $row['alumno'],
                'codigo' => $row['identificacion'],
                'titulo' => $row['titulo'],
                'indice' => $row['indice'],
                'email' => $row['email'],
                'evento' => $row['evento'],
                'cupo' => $row['cupo'],
                'btn' => $btn,
            ];
        }

        sendJsonResponse($json);
    } catch (Exception $e) {
        error_log("[" . date('Y-m-d H:i:s') . "] alumnos.php ERROR GET: " . $e->getMessage() . "\n", 3, 'alumnos_log.txt');
        sendJsonResponse(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
    }
}

if ($metodo == 'POST') {
    try {
        // Registrar alumno nuevo
        if (isset($_POST['codigo'])) {
            $nombre = $_POST['nombre'] ?? '';
            $codigo = $_POST['codigo'] ?? '';
            $indice = $_POST['indice'] ?? '';
            $titulo = $_POST['titulo'] ?? '';
            $email = $_POST['email'] ?? '';
            $id_evento = $_POST['id_evento'] ?? '';

            if ($nombre === '' || $codigo === '' || $indice === '' || $titulo === '' || $email === '' || $id_evento === '') {
                sendJsonResponse(['error' => 'Todos los campos son obligatorios'], 400);
            }

            $id_evento = intval($id_evento);

            $query = mysqli_query($mysqli, "INSERT INTO alumnos VALUES (NULL,'$codigo','$nombre','$indice','$titulo','$email','$id_evento',3)");
            if (!$query) {
                throw new Exception('Error en el registro: ' . mysqli_error($mysqli));
            }

            $codesDir = "../Archivos/";
            if (!is_dir($codesDir)) {
                @mkdir($codesDir, 0755, true);
            }
            $codeFile = $indice . '.png';
            QRcode::png($indice, $codesDir . $codeFile, 'L', 10);

            sendJsonResponse([
                'success' => true,
                'message' => 'El alumno ha sido registrado satisfactoriamente',
            ]);
        }

        // Actualizar alumno existente
        if (isset($_POST['id_alumno'])) {
            $id_alumno = intval($_POST['id_alumno']);
            $cupo = intval($_POST['cupo'] ?? 0);
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $indice = $_POST['indice'] ?? '';

            if ($id_alumno <= 0) {
                sendJsonResponse(['error' => 'ID de alumno no válido'], 400);
            }

            $query_s = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE a.id_alumno=$id_alumno AND a.id_evento=e.id_evento");
            if (!$query_s) {
                throw new Exception('Error en la consulta: ' . mysqli_error($mysqli));
            }

            $row = mysqli_fetch_assoc($query_s);
            if (!$row) {
                sendJsonResponse(['error' => 'Alumno no encontrado'], 404);
            }

            $evento = $row['evento'];
            $fecha = $row['fecha'];
            $hora = date("g:i a", strtotime($row['hora']));
            $direccion = $row['direccion'];
            $lugar = $row['lugar'];

            $query = mysqli_query($mysqli, "UPDATE alumnos SET cupo=$cupo, alumno='$nombre', email='$email', estado = 0 WHERE id_alumno=$id_alumno");
            if (!$query) {
                throw new Exception('Error en el registro: ' . mysqli_error($mysqli));
            }

            // Mantengo comentado el envío de correo como estaba en tu código
            // enviar($email, $nombre, $indice, $evento, $lugar, $fecha, $hora, $direccion);

            sendJsonResponse([
                'success' => true,
                'message' => 'El alumno ha sido actualizado satisfactoriamente',
            ]);
        }

        // Si no coincide ninguna acción
        sendJsonResponse(['error' => 'Acción no válida'], 400);
    } catch (Exception $e) {
        error_log("[" . date('Y-m-d H:i:s') . "] alumnos.php ERROR POST: " . $e->getMessage() . "\n", 3, 'alumnos_log.txt');
        sendJsonResponse(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
    }
}

// Método no permitido
sendJsonResponse(['error' => 'Método no permitido'], 405);