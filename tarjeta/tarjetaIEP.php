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
// $fecha1 = date("d-m-Y", strtotime($row['fecha']));
$fecha = $row['fecha'];


$hora = date("g:i a",strtotime($row['hora']));
$direccion = $row['direccion'];
$link1 = $row['link_1'];
$link2 = $row['link_2'];
$asiento = $row['asiento'];

$fecha1 = date("d-m-Y", strtotime($fecha));
$fecha_formateada = strftime("%A %d de %B del %Y", strtotime($fecha1));
$fecha_formateada = htmlspecialchars($fecha_formateada, ENT_QUOTES, 'UTF-8');
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
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
</style>
<style>

body, html { height: 100% }
    .bgimg {
        background-image: url('images/fondoIEP.jpg');
        min-height: 100%;
        background-position: center;
        background-size: cover;
}

.w3-modal {
        border: none !important;
}

* {
        font-family: "Monserrat", sans-serif;
}

.invitation {
        font-size: 2rem;
        color: white;
        background-color: #C21111;
        padding: 10px 20px;
        border-radius: 5px;
        text-transform: uppercase;
        letter-spacing: 5px;
        display: inline-block;
        text-align: center;
        width: 260px;
}

.ceremonia {
        text-align: left;
        color: white !important; 
        font-family: "Montserrat", serif !important; 
        font-weight: bolder !important;
        text-transform: uppercase;
        font-size: 53px !important;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        line-height: 1.0;
}
        
.grado {
        color: white !important;
        font-weight: 700;
}

.ceremonia div {
        margin: 0;
        padding: 0;
}

.w3-container{
    margin-top: 40px;
}

.highlight-text {
    margin-top:30px;
    font-size: 1.2em;
    line-height: 1.5;
     margin-bottom: 20px;
}

.highlight {
    position: relative;
    display: inline;
    font-family: "Montserrat", serif !important; 
    color: #fff;
    font-weight: bold !important;
    font-size: 24px !important;
    text-transform: uppercase;
}

.highlight::after {
    content: '';
    position: absolute;
    top: 50%;
    padding-bottom: 10px;
    left: 0;
    width: 100%;
    height: 15px;
    background-color: #C21111;
    transform: translateY(20%);
    z-index: -1;
}

p {
    color: white !important;
}

.Label{
    font-family: "Montserrat", serif !important; 
    color: white !important;
    font-weight: bold !important;
    font-size: 21px;
}
.Label2{
     color: white !important;
     font-weight: 700;
     font-size: 25px;
}

p span{
        font-size: 21px;
      font-weight: regular !important;
      color: white !important;
      transform: translateX(200px); 
}
</style>
</head>
<body>

<div class="bgimg w3-display-container w3-text-white">  
</div>


<!-- Modal -->
<div id="contact" class="w3-modal">
  <div class="w3-modal-content w3-animate-zoom">
    <div class="w3-container">
     
      <div class="w3-row">
          <div class="w3-col s12">
               <h1 class="invitation" >INVITACI&#x00D3;N</h1>
      
      <div class="ceremonia">
        <div class="grado">Ceremonia <br> de grado</div> 
        </div>
        
        <p class="highlight-text">
        <span class="highlight"> ¡Felicidades!</span> Has alcanzado una meta importante, <br> ahora a conquistar un nuevo sue&ntilde;o.
        </p>
    
              <p><span class="Label">Fecha:  </span> <span> <?php echo $fecha_formateada;?></span></p>
              <p><span class="Label">Hora:  </span><span> <?php echo $hora; ?></span></p>
              <p><span class="Label">Lugar: </span><span> <?php echo $lugar; ?></span></p>
              <p><span class="Label">Direcci&oacute;n:  </span><span> <?php echo $direccion; ?> </span></p>
              <p><span class="Label">Silla Graduando:  </span><span><?php echo $asiento; ?></span></p> <br>
              <p><span class="Label2" >Agradecemos verificar el codigo QR adjunto y el comunicado de grados</span></p>
              <br> <p><span>Transmisi&oacute;n en vivo: </span><span ><a href="<?php echo $link1; ?>"> <img src="images/fcb.png" width="50px"></a> <a href="<?php echo $link2; ?>"> <img src="images/ytb.png" width="53px"> </a></span></p>
          </div>
          <div class="w3-col s12">
          </div>
      </div>
      
      <h1>
         <p style="font-size: 24px;"><br>
        <img src="images/LogoIEP.jpg" width="350px"></p>
      </h1>
      
      
    </div>
  </div>
</div>
</body>
</html>

