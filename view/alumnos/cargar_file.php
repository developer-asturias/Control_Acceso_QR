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
            <form class="form-horizontal" enctype="multipart/form-data"  action="?cargar_file" method="POST">
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
                            <input type="file" name="fichero_usuario" class="form-control" required>
                        </div>  
                    </div>
                    <div class="col-sm-2">
                       <div class="form-group">
                            Cupos de Invitados
                            <input type="number" name="cupo" placeholder="Nro de cupos" class="form-control" required>
                        </div>  
                    </div>
                    <div class="col-sm-3 py-4">
                        <input type="submit" class="btn btn-primary " name="submit" value="Enviar fichero" />
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    <?php 
        if(isset($_POST['submit'])){
            error_log('cargar_file.php: Inicio procesamiento submit');
            include('config/database.php');
            include('back-end/phpqrcode/qrlib.php');
            include('enviar_email.php');
            $dir_subida = 'Archivos/';
            $fichero_subido = $dir_subida . basename($_FILES['fichero_usuario']['name']);
            error_log('cargar_file.php: Archivo recibido - nombre=' . (isset($_FILES['fichero_usuario']['name']) ? $_FILES['fichero_usuario']['name'] : 'NO DEFINIDO'));
            if (!move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
                error_log('cargar_file.php: ERROR al mover el archivo subido a ' . $fichero_subido);
            } else {
                error_log('cargar_file.php: Archivo movido correctamente a ' . $fichero_subido);
            }
            if (!file_exists('lib/PHPExcel/Classes/PHPExcel.php')) {
                error_log('cargar_file.php: ERROR - No se encuentra lib/PHPExcel/Classes/PHPExcel.php desde main.php');
                die('No se encuentra la libreria PHPExcel (ruta lib/PHPExcel/Classes/PHPExcel.php).');
            }
            error_log('cargar_file.php: PHPExcel.php encontrado, incluyendo libreria');
            require_once 'lib/PHPExcel/Classes/PHPExcel.php';
            if (!class_exists('PHPExcel_IOFactory')) {
                error_log('cargar_file.php: ERROR - Clase PHPExcel_IOFactory no existe despues del require');
                die('Error al cargar PHPExcel_IOFactory.');
            }
            $archivo = $fichero_subido;
            error_log('cargar_file.php: Ruta de archivo Excel=' . $archivo);


            // $inputFileType = PHPExcel_IOFactory::identify($archivo);
            // error_log('cargar_file.php: Tipo de archivo Excel detectado=' . $inputFileType);


            try {
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                error_log('cargar_file.php: Reader Excel2007 creado');
                $objPHPExcel = $objReader->load($archivo);
                error_log('cargar_file.php: Archivo Excel cargado correctamente');
            } catch (Exception $e) {
                error_log('cargar_file.php: ERROR cargando Excel: ' . $e->getMessage());
                die('Error leyendo el archivo de Excel: ' . $e->getMessage());
            }


            $sheet = $objPHPExcel->getSheet(0); 
            $highestRow = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();


            error_log('cargar_file.php: highestRow=' . $highestRow . ' highestColumn=' . $highestColumn);
            $id_evento = $_POST['evento'];
            error_log('cargar_file.php: id_evento recibido=' . $id_evento);
            $query = mysqli_query($mysqli, "SELECT evento, lugar, direccion, hora, DATE_FORMAT(fecha,'%M') mes ,DATE_FORMAT(fecha,'%e') dia,DATE_FORMAT(fecha,'%Y') anno FROM eventos WHERE id_evento='$id_evento'");
            if (!$query) {
                error_log('cargar_file.php: ERROR en consulta eventos: ' . mysqli_error($mysqli));
            }
            $row = mysqli_fetch_array($query);
            $dia = $row['dia']; $mes =$row['mes']; $anno = $row['anno'];
            $fecha = $dia.' de '.$mes.' del '.$anno;
            $hora = date("g:i a",strtotime($row['hora']));
            $lugar = $row['lugar'];
            $direccion = $row['direccion'];
            $evento = $row['evento'];
            $archivo = $row['archivo']; 
            $cupo = $_POST['cupo'];
            error_log('cargar_file.php: cupo recibido=' . $cupo);
    ?>


    

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Alumnos</title>
    <!-- Incluir la librer铆a SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mostrarVentanaEmergente(identificacion, nombre, evento) {
            Swal.fire({
                icon: 'error',
                title: 'Alumno ya registrado',
                text: 'El alumno con identificaci贸n: ' + identificacion + ' (' + nombre + ') ya est谩 registrado en el evento: ' + evento,
            });
        }
    </script>
</head>>
<body>


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
                            <th></th>
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
                            error_log('cargar_file.php: Fila ' . $row . ' -> codigo=' . $codigo . ' indice=' . $indice . ' email=' . $email);
                            // $result = mysqli_query($mysqli, "SELECT * FROM alumnos WHERE indice='$indice' AND identificacion='$codigo'");
                            $result = mysqli_query($mysqli, "SELECT * FROM alumnos WHERE indice='$indice' AND identificacion='$codigo' AND id_evento='$id_evento'");
                            if (!$result) {
                                error_log('cargar_file.php: ERROR en SELECT alumnos fila ' . $row . ': ' . mysqli_error($mysqli));
                            }
                            $num_rows = mysqli_num_rows($result);

                            // Manejar excepciones de duplicaci贸n de alumnos
                            if ($num_rows == 0) {
                                $query = mysqli_query($mysqli, "INSERT INTO alumnos VALUES (NULL, '$codigo', '$nombre', '$indice', '$titulo', '$email', '$asiento', '$id_evento', '$cupo', 0)") or die('Error en el registro' . mysqli_error($mysqli));
                                if (!$query) {
                                    error_log('cargar_file.php: ERROR en INSERT alumnos fila ' . $row . ': ' . mysqli_error($mysqli));
                                } else {
                                    error_log('cargar_file.php: INSERT alumnos OK fila ' . $row . ' codigo=' . $codigo . ' indice=' . $indice);
                                }
                                $codesDir = "Archivos/";
                                $codeFile = $indice . '.png';
                                QRcode::png($indice, $codesDir . $codeFile, 'L', 10);
                                ?>
                                <tr>
                                    <th scope='row'><?php echo $num; ?></th>
                                    <td><?php echo htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($indice, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($asiento, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                                <?php
                            } else {
                                echo "<script>
                                    var identificacion = \"" . htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8') . "\";
                                    var nombre = \"" . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . "\";
                                    var evento = \"" . htmlspecialchars($evento, ENT_QUOTES, 'UTF-8') . "\";
                                    // console.log('Nombre del alumno ya registrado:', usuario);
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
    <?php } ?>
</div>
<!-- /.container-fluid -->
<script src="js/alumnos.js"></script>
<script type="text/javascript">
    list_eventos()
</script>