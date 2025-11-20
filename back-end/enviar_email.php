<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function enviar($email,$nombre,$indice,$evento,$lugar,$fecha,$hora,$direccion){
    $bodyHTML = '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns:v="urn:schemas-microsoft-com:vml">
    
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
        <!--[if !mso]--><!-- -->
        <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,700" rel="stylesheet">
        <!-- <![endif]-->
    
        <title>PlannerPack</title>
    
        <style type="text/css">
            body {
                width: 100%;
                background-color: #e3e3e3;
                margin: 0;
                padding: 0;
                -webkit-font-smoothing: antialiased;
                mso-margin-top-alt: 0px;
                mso-margin-bottom-alt: 0px;
                mso-padding-alt: 0px 0px 0px 0px;
            }
            
            p,
            h1,
            h2,
            h3,
            h4 {
                margin-top: 0;
                margin-bottom: 0;
                padding-top: 0;
                padding-bottom: 0;
            }
            
            span.preheader {
                display: none;
                font-size: 1px;
            }
            
            html {
                width: 100%;
            }
            
            table {
                font-size: 14px;
                border: 0;
            }
            /* ----------- responsivity ----------- */
            
            @media only screen and (max-width: 640px) {
                /*------ top header ------ */
                .main-header {
                    font-size: 20px !important;
                }
                .main-section-header {
                    font-size: 28px !important;
                }
                .show {
                    display: block !important;
                }
                .hide {
                    display: none !important;
                }
                .align-center {
                    text-align: center !important;
                }
                .no-bg {
                    background: none !important;
                }
                /*----- main image -------*/
                .main-image img {
                    width: 440px !important;
                    height: auto !important;
                }
                /* ====== divider ====== */
                .divider img {
                    width: 440px !important;
                }
                /*-------- container --------*/
                .container590 {
                    width: 440px !important;
                }
                .container580 {
                    width: 400px !important;
                }
                .main-button {
                    width: 220px !important;
                }
                /*-------- secions ----------*/
                .section-img img {
                    width: 320px !important;
                    height: auto !important;
                }
                .team-img img {
                    width: 100% !important;
                    height: auto !important;
                }
            }
            
            @media only screen and (max-width: 479px) {
                /*------ top header ------ */
                .main-header {
                    font-size: 18px !important;
                }
                .main-section-header {
                    font-size: 26px !important;
                }
                /* ====== divider ====== */
                .divider img {
                    width: 280px !important;
                }
                /*-------- container --------*/
                .container590 {
                    width: 280px !important;
                }
                .container590 {
                    width: 280px !important;
                }
                .container580 {
                    width: 260px !important;
                }
                /*-------- secions ----------*/
                .section-img img {
                    width: 280px !important;
                    height: auto !important;
                }
            }
        </style>
        <!-- [if gte mso 9]><style type=¡±text/css¡±>
            body {
            font-family: arial, sans-serif!important;
            }
            </style>
        <![endif]-->
    </head>
    
    
    <body class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- pre-header -->
        <table style="display:none!important;">
            <tr>
                <td>
                    <div style="overflow:hidden;display:none;font-size:1px;color:#e3e3e3;line-height:1px;font-family:Arial;maxheight:0px;max-width:0px;opacity:0;">
                        Invitación Ceremonia de Graduación Asturias.
                    </div>
                </td>
            </tr>
        </table>
        <!-- pre-header end --> 
        <!-- header -->
        <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="e3e3e3">
            <tr>
                <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
            </tr>
            <tr>
                <td align="center" height="70" style="height:70px;">
                    <a href="" style="display: block; border-style: none !important; border: 0 !important;">
                        <img width="100" border="0" style="display: block; width: 380px;" src="https://asturias.systemsolutions.com.co/img/Logo-largo-blanco.png" alt="" />
                    </a>
                </td>
            </tr>
        </table>
        <!-- end header -->
    
        <!-- big image section -->
        <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="e3e3e3" class="bg_color">
    
            <tr>
                <td align="center">
                    <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                        
                        <tr>
                            <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center" style="color: #000; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">
    
    
                                <div style="line-height: 35px">
    
                                    <span style="color: #000;">Invitación Ceremonia de Grados</span>
    
                                </div>
                            </td>
                        </tr>
    
                        <tr>
                            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                        </tr>
    
                        <tr>
                            <td align="center">
                                <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="000">
                                    <tr>
                                        <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
    
                        <tr>
                            <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                        </tr>
    
                        <tr>
                            <td align="center">
                                <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
                                    <tr>
                                        <td align="center" style="color: #000; font-size: 16px; font-family: Quicksand, Calibri, sans-serif; line-height: 24px;">
    
    
                                            <div style="line-height: 24px;">
                                                
                                                La Corporación Universitaria de Asturias se complace en invitarlos a la ceremonia de grado. <br>
                                                <b>Fecha:</b> '.$fecha.' <br> <b>Hora:</b> '.$hora.' <br> <b>Lugar:</b> '.$lugar.'  <br> <b>Dirección:</b> '.$direccion.' <BR>
                                                <img width="100" border="0" style="display: block; width: 300px;" src="https://controlacceso.systemsolutions.com.co/Archivos/'.$indice.'.png" alt="" />
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="center">
                                <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
                                    <tr>
                                        <td style="color: #000; font-size: 16px; font-family: Quicksand, Calibri, sans-serif; line-height: 24px;">
                                            <div style="line-height: 24px; text-align: justify;">
                                               <br> <h3 style="text-align: center;">Para tener en cuenta:</h3>
                                               <ul>
                                                 <li>Código QR válido para 3 invitados.</li>
                                                 <li>El código QR es único por cada estudiante, por lo tanto, no podrá ser redimido por otro estudiante.</li>
                                                 <li>El código QR será solicitado al ingreso del auditorio para poder acceder a la ceremonia.</li>
                                                 <li>Los asistentes deberán presentar carné de vacunación físico o digital con el esquema completo.</li>
                                                 <li>Recuerde que solo se permitirá el acceso de niños mayores de 5 años.</li>
                                                 <li>En caso de que no se muestre el código QR por favor descargar la imagen adjunta o presionar en la opción Mostrar contenido bloqueado ubicado en la parte superior de este correo.</li>
                                                 <li>En caso que el código QR no pueda ser leido por favor indicar en el registro este numero '.$indice.'</li>
                                                 <li>Por favor leer atentamente las recomendaciones relacionadas en el archivo pdf adjunto.</li>
                                                </ul>
                                                <br> Cualquier información adicional previo a la ceremonia puede solicitarla al correo servicioalestudiante@asturias.edu.co
                                                <br><br>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
    
                        <tr>
                            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                        </tr>
    
    
                    </table>
    
                </td>
            </tr>
    
        </table>
        <!-- end section -->
    
        
    
    </body>
    
    </html>
    ';
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp-mail.outlook.com";
    $mail->Port = 587;
    $mail->Username = "no-reply@controlacceso.redsummacloud.com";
    $mail->Password = "XkkFXqr!ENJ,LRa#";
    
    $mail->setFrom("no-reply@controlacceso.redsummacloud.com", "Ceremonia de Graduación Asturias");
    $mail->AddReplyTo('no-reply@controlacceso.redsummacloud.com','Ceremonia de Graduación Asturias');
    
    $mail->addAddress($email,$nombre);
    $mail->addCC('accesoasturias@gmail.com');
    $mail->Subject = "Invitación Ceremonia de Graduación Asturias";
    $mail->Body = $bodyHTML;
    $mail ->AddAttachment('../Archivos/'.$indice.'.png',"QR.png");
    $mail ->AddAttachment('../Archivos/'.$evento.'.pdf',"Comunicado Ceremonia de Graduacion.pdf");
    //$mail->msgHTML(file_get_contents('https://asturias.systemsolutions.com.co/prueba.html'), __DIR__);
    //Replace the plain text body with one created manually andresrivera16@gmail.com
    $mail->isHTML(true);
    $mail->CharSet = "UTF-8";
    $mail->send();  
}

