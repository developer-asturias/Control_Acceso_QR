<?php 
header('Content-Type: text/html; charset=UTF-8');
setlocale(LC_TIME, "es_ES.UTF-8");
include('../config/database.php');
$id = $_GET['id'];

$query = mysqli_query($mysqli, "SELECT a.*, e.*
                               FROM alumnos a
                               JOIN eventos e ON a.id_evento = e.id_evento
                               WHERE a.id_alumno = $id") or die('Error en la consulta: '. mysqli_error($mysqli));
$row = mysqli_fetch_array($query);
$id_alumno = $row['id_alumno'];
$email = $row['email'];
$nombre = $row['alumno'];
$indice = $row['indice'];
$archivo = $row['archivo'];
$lugar = $row['lugar'];
$fecha1 = date("d-m-Y", strtotime($row['fecha']));
$fecha = strftime("%A %d de %B del %Y", strtotime($fecha1));
$hora = date("g:i a",strtotime($row['hora']));
$direccion = $row['direccion'];
$link1 = $row['link_1'];
$link2 = $row['link_2'];
$asiento = $row['asiento'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Tarjeta de Invitacion</title>
<link rel=icon href="images/favicon.png" sizes="40x40" type="image/png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>

body, html {height: 100%}
.bgimg {
  background-image: url('images/banner-bg.png');
  min-height: 100%;
  background-position: center;
  background-size: cover;
}

.w3-container{
    margin-top: 100px;
}
</style>
</head>
<body>

<div class="bgimg w3-display-container w3-text-white">
  
</div>

<!-- Menu Modal -->

<!-- Modal -->
<div id="contact" class="w3-modal">
  <div class="w3-modal-content w3-animate-zoom">
    <div class="w3-container">
      <h1>La Corporaci&oacute;n Universitaria de Asturias se complace en invitarte a la ceremonia de graduaci&oacute;n</h1>
      
      <div class="w3-row">
          <div class="w3-col s12">
              <p><span>Fecha: </span> <span style="font-family: Gotham; color: white;"><?php echo $fecha; ?></span></p>
              <p><span>Hora: </span><span style=" font-family: Gotham; color: white;"><?php echo $hora; ?></span></p>
              <p><span>Lugar: </span><span style=" font-family: Gotham; color: white;"><?php echo $lugar; ?></span></p>
              <p><span>Direcci&oacute;n: </span><span style="font-family: Gotham; color: white; "><?php echo $direccion; ?> </span></p>
              <p><span>Silla Graduando: </span><span style=" font-family: Gotham; color: white;"><?php echo $asiento; ?></span></p>
              <!--<p><span>C&oacute;digo de Acceso Ceremonia: </span><br><span style=" font-family: Gotham; margin-left: 150px; margin-top:1000px;  "><img src="http://controlacceso.solicitudesasturias.co/Archivos/1.png" width="250px" ></span></p>-->
             
              <p><span>Agradecemos verificar el codigo QR adjunto y el comunicado de grados</span></p>
             <br> <p><span>Transmisi&oacute;n en vivo: </span><span ><a href="<?php echo $link1; ?>"> <img src="images/fcb.png" width="50px"></a> <a href="<?php echo $link2; ?>"> <img src="images/ytb.png" width="53px"> </a></span></p>
          </div>
          <div class="w3-col s12">
              
          </div>
          
      </div>
      <h1>
         <p style="font-size: 24px;"><br>
        <img src="images/MD.png" width="350px"></p>
      </h1>
    </div>
  </div>
</div>

</body>
</html>

