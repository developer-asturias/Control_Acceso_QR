<?php
setlocale(LC_TIME, "es_ES.UTF-8");
include('../config/database.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE a.id_evento=e.id_evento AND a.estado=0 LIMIT 0,30") or die('Error en la consulta'. mysqli_error($mysqli));

    $query = mysqli_query($mysqli, "
    SELECT a.*, e.*, i.name AS nombre_institucion
    FROM alumnos a
    JOIN eventos e ON a.id_evento = e.id_evento
    JOIN instituciones i ON e.institucion = i.id
    WHERE a.estado = 0
    LIMIT 0,30
    ") or die('Error en la consulta: ' . mysqli_error($mysqli));



while ($row = mysqli_fetch_array($query)) {
    $id_alumno = $row['id_alumno'];
    $update = mysqli_query($mysqli, "UPDATE alumnos SET estado = 1 WHERE id_alumno= $id_alumno;") or die('Error en la consulta' . mysqli_error($mysqli));
    $email = $row['email'];
    $nombre = $row['alumno'];
    $indice = $row['indice'];
    $archivo = $row['archivo'];
    $lugar = $row['lugar'];
    $fecha1 = date("d-m-Y", strtotime($row['fecha']));
    $fecha = strftime("%A %d de %B del %Y", strtotime($fecha1));
    $hora = date("g:i a", strtotime($row['hora']));
    $direccion = $row['direccion'];
    $nombre_institucion = $row['nombre_institucion'];


    // aca vamos a usar las plantillas creadas para diferentes instituciones
    // CONDICIONAL PARA CARGAR DIFERENTE PLANTILLA DE CORREO SEGUN LA INSTITUCION
    
    
    
     $mail = new PHPMailer();
    if ($nombre_institucion == 'Universidad Asturias') {
    $bodyHTML = file_get_contents('../templates/correo_asturias.html');
    $subject = "Invitación Ceremonia de Graduación Asturias";
    } elseif ($nombre_institucion == 'Instituto Europeo de Posgrados') {
    $bodyHTML = file_get_contents('../templates/correo_institutoEuropeo.html');
    $subject = "Invitación Ceremonia de Graduación IEP";
    }

    

    // cambios en las variables de la plantilla 
    $bodyHTML = str_replace(array(
        '{{nombre}}',
        '{{fecha}}',
        '{{hora}}',
        '{{lugar}}',
        '{{direccion}}',
        '{{indice}}',
        '{{archivo}}',
        '{{id_alumno}}'
    ), array(
        $nombre,
        $fecha,
        $hora,
        $lugar,
        $direccion,
        $indice,
        $archivo,
        $id_alumno
    ), $bodyHTML);




    // envio de correo
    
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp-mail.outlook.com";
    $mail->Port = 587;
    $mail->Username = "no-reply@controlacceso.redsummacloud.com"; // "accesoasturias@gmail.com";
    $mail->Password = "XkkFXqr!ENJ,LRa#";
    $mail->SMTPDebug = 2; // Habilita la salida de depuración detallada
    
    $mail->setFrom("no-reply@controlacceso.redsummacloud.com", "Ceremonia de Graduación Asturias");
    $mail->AddReplyTo('servicioalestudiante@asturias.edu.co','Ceremonia de Graduación Asturias');
    
    $mail->addAddress($email,$nombre);
    //$mail->addCC('accesoasturias@gmail.com');
    $mail->Subject = $subject;
    $mail->Body = $bodyHTML;
    $mail ->AddAttachment('../Archivos/'.$indice.'.png',"QR.png");
    $mail ->AddAttachment('../Archivos/'.$archivo,"Comunicado Ceremonia de Graduacion.pdf");
    //$mail->msgHTML(file_get_contents('https://asturias.systemsolutions.com.co/prueba.html'), __DIR__);
    //Replace the plain text body with one created manually andresrivera16@gmail.com
    $mail->isHTML(true);
    $mail->CharSet = "UTF-8";
    $mail->send();  
}
