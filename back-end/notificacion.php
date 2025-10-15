<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$proceso='55';
$resultado = 'bn';
$msg = 'hello word';
$bodyHTML = '<html>
<head>
<title>HTML</title>
</head>
<body>
<h2>Proceso de integracion -> '.$proceso.'</h2>
<p>El resultado del proceso es: '.$resultado.'</p>
<p>El mensaje del proceso es: '.$msg.'</p>
</body>
</html>';
$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = "smtp.gmail.com";
$mail->Port = 587;
$mail->Username = "uniasturias.ar@gmail.com";
$mail->Password = "mpikeucvnliknclk";


$mail->setFrom("andres.rivera@logismart.com.co", "Notificacion de Proceso");

$mail->addAddress('andres.rivera@colompack.com','Andres Camilo Rivera');

$mail->Subject = $proceso;
$mail->Body = $bodyHTML;
$mail->isHTML(true);
$mail->CharSet = "UTF-8";
var_dump($mail->send()); 



