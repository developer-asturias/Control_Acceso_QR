<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir base de datos
if (!file_exists('config/database.php')) {
    error_log('cargar_file.php: ERROR - No se encuentra config/database.php');
    die('No se encuentra el archivo de configuración de base de datos.');
}
require_once 'config/database.php';

// Incluir autoload de Composer
if (!file_exists('vendor/autoload.php')) {
    error_log('cargar_file.php: ERROR - No se encuentra vendor/autoload.php');
    die('No se encuentra la libreria PhpSpreadsheet (ruta vendor/autoload.php).');
}
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Incluir librerías adicionales
require_once 'back-end/phpqrcode/qrlib.php';

$objSpreadsheet = null;
$sheet = null;
$highestRow = 0;
$id_evento = null;
$evento = null;
$mostrar_tabla = false;
$error_msg = null;

if(isset($_POST['submit'])){
    try {
        $dir_subida = 'Archivos/';
        
        // Crear directorio si no existe
        if (!is_dir($dir_subida)) {
            if (!@mkdir($dir_subida, 0777, true)) {
                throw new Exception('No se puede crear el directorio Archivos/. Verifica los permisos del servidor.');
            }
        }
        
        // Verificar permisos de escritura
        if (!is_writable($dir_subida)) {
            throw new Exception('El directorio Archivos/ no tiene permisos de escritura. Contacta al administrador del servidor.');
        }
        
        $fichero_subido = $dir_subida . basename($_FILES['fichero_usuario']['name']);
        
        if (!move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
            throw new Exception('Error al mover el archivo subido. Verifica los permisos de la carpeta Archivos/.');
        }
        
        $archivo = $fichero_subido;
        error_log('cargar_file.php: Ruta de archivo Excel=' . $archivo);

        $objReader = IOFactory::createReaderForFile($archivo);
        error_log('cargar_file.php: Reader creado para archivo Excel');
        $objSpreadsheet = $objReader->load($archivo);
        error_log('cargar_file.php: Archivo Excel cargado correctamente');
        
        $sheet = $objSpreadsheet->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        
        error_log('cargar_file.php: highestRow=' . $highestRow . ' highestColumn=' . $highestColumn);
        $id_evento = $_POST['evento'];
        error_log('cargar_file.php: id_evento recibido=' . $id_evento);
        
        $query = mysqli_query($mysqli, "SELECT evento, lugar, direccion, hora, DATE_FORMAT(fecha,'%M') mes ,DATE_FORMAT(fecha,'%e') dia,DATE_FORMAT(fecha,'%Y') anno FROM eventos WHERE id_evento='$id_evento'");
        if (!$query) {
            throw new Exception('Error en la consulta de eventos: ' . mysqli_error($mysqli));
        }
        
        $row = mysqli_fetch_array($query);
        if ($row) {
            $dia = $row['dia']; 
            $mes = $row['mes']; 
            $anno = $row['anno'];
            $fecha = $dia.' de '.$mes.' del '.$anno;
            $hora = date("g:i a",strtotime($row['hora']));
            $lugar = $row['lugar'];
            $direccion = $row['direccion'];
            $evento = $row['evento'];
            $cupo = $_POST['cupo'];
            error_log('cargar_file.php: cupo recibido=' . $cupo);
            $mostrar_tabla = true;
        }
    } catch (Exception $e) {
        error_log('cargar_file.php: ERROR: ' . $e->getMessage());
        $error_msg = $e->getMessage();
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-users"></i> Datos de Alumnos</h1>
    </div>

    <!-- Content Row -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> <i class="fa fa-edit"></i> Leer Archivo para cargar alumnos</h6>
        </div>
        <div class="card-body">
            <?php if(isset($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> <?php echo htmlspecialchars($error_msg); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <form class="form-horizontal" enctype="multipart/form-data" action="?cargar_file" method="POST">
                <div class="row">
                    <div class="col-sm-3">
                       <div class="form-group">
                            Evento
                            <select id="evento" name="evento" class="form-control" required></select>
                        </div>  
                    </div>
                    <div class="col-sm-4">
                       <div class="form-group">
                            Archivo
                            <input type="file" name="fichero_usuario" class="form-control" accept=".xlsx,.xls" required>
                        </div>  
                    </div>
                    <div class="col-sm-2">
                       <div class="form-group">
                            Cupos de Invitados
                            <input type="number" name="cupo" placeholder="Nro de cupos" class="form-control" required>
                        </div>  
                    </div>
                    <div class="col-sm-3 py-4">
                        <input type="submit" class="btn btn-primary" name="submit" value="Enviar fichero" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($mostrar_tabla && $sheet): ?>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-list"></i> Tabla de Datos Cargados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Identificacion</th>
                            <th>Alumno</th>
                            <th>Indice</th>
                            <th>Titulo</th>
                            <th>Email</th>
                            <th>Asiento</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $num = 1;
                        error_log('cargar_file.php: Inicio del bucle de filas, highestRow=' . $highestRow);
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $codigo = $sheet->getCell("A" . $row)->getValue();
                            $nombre = $sheet->getCell("B" . $row)->getValue();
                            $indice = $sheet->getCell("C" . $row)->getValue();
                            $titulo = $sheet->getCell("D" . $row)->getValue();
                            $email = $sheet->getCell("E" . $row)->getValue();
                            $asiento = $sheet->getCell("F" . $row)->getValue();
                            
                            if (empty($codigo) || empty($nombre)) continue;
                            
                            error_log('cargar_file.php: Fila ' . $row . ' -> codigo=' . $codigo . ' indice=' . $indice . ' email=' . $email);
                            
                            $result = mysqli_query($mysqli, "SELECT * FROM alumnos WHERE indice='$indice' AND identificacion='$codigo' AND id_evento='$id_evento'");
                            if (!$result) {
                                error_log('cargar_file.php: ERROR en SELECT alumnos fila ' . $row . ': ' . mysqli_error($mysqli));
                            }
                            $num_rows = mysqli_num_rows($result);

                            if ($num_rows == 0) {
                                $query = mysqli_query($mysqli, "INSERT INTO alumnos VALUES (NULL, '$codigo', '$nombre', '$indice', '$titulo', '$email', '$asiento', '$id_evento', '$cupo', 0)") or die('Error en el registro' . mysqli_error($mysqli));
                                if (!$query) {
                                    error_log('cargar_file.php: ERROR en INSERT alumnos fila ' . $row . ': ' . mysqli_error($mysqli));
                                } else {
                                    error_log('cargar_file.php: INSERT alumnos OK fila ' . $row . ' codigo=' . $codigo . ' indice=' . $indice);
                                }
                                $codesDir = "Archivos/";
                                
                                // Crear directorio si no existe
                                if (!is_dir($codesDir)) {
                                    @mkdir($codesDir, 0777, true);
                                }
                                
                                $codeFile = $indice . '.png';
                                $qrPath = $codesDir . $codeFile;
                                
                                // Generar QR solo si el directorio es escribible
                                if (is_writable($codesDir)) {
                                    try {
                                        QRcode::png($indice, $qrPath, 'L', 10);
                                    } catch (Exception $qr_error) {
                                        error_log("Error generando QR: " . $qr_error->getMessage());
                                    }
                                }
                                ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td><?php echo htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($indice, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($asiento, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <?php
                                $num++;
                            } else {
                                echo "<script>
                                    var identificacion = \"" . htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8') . "\";
                                    var nombre = \"" . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . "\";
                                    var evento = \"" . htmlspecialchars($evento, ENT_QUOTES, 'UTF-8') . "\";
                                    mostrarVentanaEmergente(identificacion, nombre, evento);
                                </script>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<!-- /.container-fluid -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/alumnos.js"></script>
<script>
    function mostrarVentanaEmergente(identificacion, nombre, evento) {
        Swal.fire({
            icon: 'error',
            title: 'Alumno ya registrado',
            text: 'El alumno con identificación: ' + identificacion + ' (' + nombre + ') ya está registrado en el evento: ' + evento,
        });
    }
    
    list_eventos();
</script>