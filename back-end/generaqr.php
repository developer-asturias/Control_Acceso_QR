<?php 
    include('phpqrcode/qrlib.php'); 
    $codesDir = "../Archivos/";   
    $codeFile = date('d-m-Y-h-i-s').'.png';
    QRcode::png('informacin', $codesDir.$codeFile, 'L', 10); 
    echo '<img class="img-thumbnail" src="'.$codesDir.$codeFile.'" />';

?>