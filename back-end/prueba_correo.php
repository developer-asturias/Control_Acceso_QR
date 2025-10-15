<?php
setlocale(LC_TIME, "spanish");
$server   = "localhost";
$username = "systemso_usuario";
$password = "Rivera2020*";
$database = "systemso_control_acceso";


$mysqli = new mysqli($server, $username, $password, $database);


if ($mysqli->connect_error) {
    die('error'.$mysqli->connect_error);
}
$mysqli->query("SET lc_time_names = 'es_ES'");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';



    $bodyHTML = '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title></title>
    <!--[if (mso 16)]>
    <style type="text/css">
    a {text-decoration: none;}
    </style>
    <![endif]-->
    <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]-->
    <!--[if gte mso 9]>
<xml>
    <o:OfficeDocumentSettings>
    <o:AllowPNG></o:AllowPNG>
    <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
</xml>
<![endif]-->
    <!--[if !mso]><!-- -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500&display=swap" rel="stylesheet">
    <!--<![endif]-->
</head>

<body>
    <div class="es-wrapper-color">
        <!--[if gte mso 9]>
			<v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
				<v:fill type="tile" src="https://oioftq.stripocdn.email/content/guids/CABINET_4ac7fa81c170457e241ae85d364a90b8/images/group_15.png" color="#ffffff" origin="0.5, 0" position="0.5, 0"></v:fill>
			</v:background>
		<![endif]-->
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" background="https://oioftq.stripocdn.email/content/guids/CABINET_4ac7fa81c170457e241ae85d364a90b8/images/group_15.png" style="background-position: center -420px;">
            <tbody>
                <tr>
                    <td class="esd-email-paddings" valign="top">
                        <table cellpadding="0" cellspacing="0" class="esd-header-popover es-header" align="center">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" align="center">
                                        <table class="es-header-body" align="center" cellpadding="0" cellspacing="0" width="600" style="background-color: transparent;">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="es-m-p0r esd-container-frame" valign="top" align="center">
                                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-image es-m-p10b" style="font-size: 0px;"><a target="_blank" href="https://viewstripo.email"><img src="https://oioftq.stripocdn.email/content/guids/CABINET_4ac7fa81c170457e241ae85d364a90b8/images/group_16.png" alt="Logo" style="display: block;" height="100" title="Logo"></a></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="esd-block-menu" esd-tmp-menu-padding="10|10" esd-tmp-menu-color="#ffffff" esd-tmp-divider="0|solid|#ffffff" esd-tmp-menu-font-size="12px" esd-tmp-menu-font-weight="bold">
                                                                                        <table cellpadding="0" cellspacing="0" width="100%" class="es-menu">
                                                                                            <tbody>
                                                                                                <tr class="links">
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">About us</a></td>
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">News</a></td>
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">Career</a></td>
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">The shops</a></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="es-content" cellspacing="0" cellpadding="0" align="center">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" align="center">
                                        <table class="es-content-body" style="background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p20" align="left">
                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="es-m-p0r es-m-p20b esd-container-frame" width="560" valign="top" align="center">
                                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-banner" style="position: relative;" esdev-config="h1"><a target="_blank" href="https://viewstripo.email"><img class="adapt-img esdev-stretch-width esdev-banner-rendered" src="https://tlr.stripocdn.email/content/guids/bannerImgGuid/images/image16480297650584787.png" alt title width="560" style="display: block;"></a></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-social es-p15t" style="font-size:0">
                                                                                        <table cellpadding="0" cellspacing="0" class="es-table-not-adapt es-social">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td align="center" valign="top" class="es-p10r"><a target="_blank" href><img title="Facebook" src="https://oioftq.stripocdn.email/content/assets/img/social-icons/square-black/facebook-square-black.png" alt="Fb" width="32"></a></td>
                                                                                                    <td align="center" valign="top" class="es-p10r"><a target="_blank" href><img title="Twitter" src="https://oioftq.stripocdn.email/content/assets/img/social-icons/square-black/twitter-square-black.png" alt="Tw" width="32"></a></td>
                                                                                                    <td align="center" valign="top" class="es-p10r"><a target="_blank" href><img title="Instagram" src="https://oioftq.stripocdn.email/content/assets/img/social-icons/square-black/instagram-square-black.png" alt="Inst" width="32"></a></td>
                                                                                                    <td align="center" valign="top"><a target="_blank" href><img title="Youtube" src="https://oioftq.stripocdn.email/content/assets/img/social-icons/square-black/youtube-square-black.png" alt="Yt" width="32"></a></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-footer esd-footer-popover" align="center">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" align="center" esd-custom-block-id="584410">
                                        <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" width="600" bgcolor="rgba(0, 0, 0, 0)" style="border-radius: 0 0 10px 10px;">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p20" align="left" esd-custom-block-id="396918">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="esd-container-frame" align="left">
                                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="esd-block-menu" esd-tmp-menu-padding="10|10" esd-tmp-menu-color="#ffffff" esd-tmp-divider="0|solid|#ffffff" esd-tmp-menu-font-size="12px" esd-tmp-menu-font-weight="bold">
                                                                                        <table cellpadding="0" cellspacing="0" width="100%" class="es-menu">
                                                                                            <tbody>
                                                                                                <tr class="links">
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">About us</a></td>
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">News</a></td>
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">Career</a></td>
                                                                                                    <td align="center" valign="top" width="25%" class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 10px;"><a target="_blank" href="https://viewstripo.email">The shops</a></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-text es-p10t es-p10b" esd-links-color="#ffffff" esd-links-underline="none">
                                                                                        <p>You are receiving this email because you have visited our site or asked us about the regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).<br><a target="_blank" href="https://viewstripo.email">Privacy police</a> | <a target="_blank">Unsubscribe</a></p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-structure es-p20" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="esd-container-frame" align="left">
                                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-image made_with" style="font-size:0"><a target="_blank" href="https://viewstripo.email/?utm_source=templates&utm_medium=email&utm_campaign=art_day_5&utm_content=сurrent_trends_in_art"><img src="https://oioftq.stripocdn.email/content/guids/CABINET_09023af45624943febfa123c229a060b/images/7911561025989373.png" alt width="125" style="display: block;"></a></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
    ';
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->Username = "accesoasturias@gmail.com";
    $mail->Password = "uycexpxbvcaoxlvb";
    
    $mail->setFrom("servicioalestudiante@asturias.edu.co", "Ceremonia de Graduación Asturias");
    $mail->AddReplyTo('servicioalestudiante@asturias.edu.co','Ceremonia de Graduación Asturias');
    
    $mail->addAddress('andres.rivera@colompack.com','Andres Rivera');
    //$mail->addCC('accesoasturias@gmail.com');
    $mail->Subject = "Invitación Ceremonia de Graduación Asturias";
    $mail->Body = $bodyHTML;
    
    //$mail->msgHTML(file_get_contents('https://asturias.systemsolutions.com.co/prueba.html'), __DIR__);
    //Replace the plain text body with one created manually andresrivera16@gmail.com
    $mail->isHTML(true);
    $mail->CharSet = "UTF-8";
    $mail->send();  

