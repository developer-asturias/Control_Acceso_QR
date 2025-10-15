<?php
$ruta=explode("?",$_SERVER['REQUEST_URI']);
$m=$ruta[1];
if ($_SESSION['permisos_acceso']=='Super Admin'){
    //Inicio
    if($m=='inicio'){
        include ('view/inicio/view.html');
        include ('view/inicio/confirma_ingreso.html');
        include ('view/inicio/leerqr.html');
    }
    if($m=='asistencias'){
        include ('view/inicio/asistencia.html');
        include ('view/inicio/confirma_ingreso.html');
        include ('view/inicio/leerqr.html');
    }
    
    if($m=='eventos'){
        include ('view/evento/view.html');
        include ('view/evento/nuevo_evento.html');
    }
    if($m=='detalles'){
        include ('view/reporte/view.html');
    }
    if($m=='alumnos'){
        include ('view/alumnos/view.html');
        include ('view/alumnos/nuevo_alumno.html');
        include ('view/alumnos/editar_alumno.html');
    }
    if($m=='cargar_file'){
        include ('view/alumnos/cargar_file.php');
    }
}

if ($_SESSION['permisos_acceso']=='Gerente'){
    //Inicio
    if($m=='inicio'){
        include ('view/inicio/view.html');
        include ('view/inicio/confirma_ingreso.html');
        include ('view/inicio/leerqr.html');
    }
    if($m=='asistencias'){
        include ('view/inicio/asistencia.html');
        include ('view/inicio/confirma_ingreso.html');
        include ('view/inicio/leerqr.html');
    }
}