<?php
include('../config/database.php');
include('enviar_email.php');
include('phpqrcode/qrlib.php');
$metodo=$_SERVER['REQUEST_METHOD'];

if($metodo=='GET'){
    //Si se mando un id quiere decir que se hara una busqueda individual
   if(isset($_GET['id_alumno'])){
       $id = $_GET['id_alumno'];
       $query = mysqli_query($mysqli, "SELECT * FROM alumnos WHERE id_alumno='$id'") or die('Error en la consulta'. mysqli_error($mysqli));
        $btn = '';
        $json = array(); 
        while($row = mysqli_fetch_array($query)){
            $json[] = array(
                'alumno' => $row['alumno'],
                'codigo' => $row['identificacion'],
                'titulo' => $row['titulo'],
                'email' => $row['email'],
                'indice' => $row['indice'],
                'cupo' => $row['cupo']
            );
        }
        $jsonstring = json_encode($json[0]);
        echo $jsonstring;
   }else{
       $query = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE a.id_evento=e.id_evento AND e.estado_evento=1");
        if(!$query){
            die('Error en la consulta'. mysqli_error($mysqli));
        }
        $btn = '';
        $json = array(); 
        while($row = mysqli_fetch_array($query)){
            $btn = "<a href='#' onclick='buscar($row[id_alumno])' class='btn btn-success btn-sm' title='Detalles'><i class='fas fa-edit' ></i></a>";
            $json[] = array(
                'alumno' => $row['alumno'],
                'codigo' => $row['identificacion'],
                'titulo' => $row['titulo'],
                'indice' => $row['indice'],
                'email' => $row['email'],
                'evento' => $row['evento'],
                'cupo' => $row['cupo'],
                'btn' => $btn
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
   }
    
}
if($metodo=='POST'){
    if(isset($_POST['codigo'])){
        $nombre = $_POST['nombre'];
        $codigo = $_POST['codigo'];
        $indice = $_POST['indice'];
        $titulo = $_POST['titulo'];
        $email = $_POST['email'];
        $id_evento = $_POST['id_evento'];

        $query = mysqli_query($mysqli, "INSERT INTO alumnos VALUES (NULL,'$codigo','$nombre','$indice','$titulo','$email','$id_evento',3)");
        if(!$query){
            die('Error en el registro'. mysqli_error($mysqli));
        } 
        $codesDir = "../Archivos/";   
        $codeFile = $indice.'.png';
        QRcode::png($indice, $codesDir.$codeFile, 'L', 10); 
        echo '
        <div class="alert alert-success" role="alert" id="alerta_add">
            El alumno ha sido registrado satisfactoriamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        ';
    }
    
    if(isset($_POST['id_alumno'])){
        $id_alumno = $_POST['id_alumno'];
        $cupo = $_POST['cupo'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $indice = $_POST['indice'];
        $query_s = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE a.id_alumno=$id_alumno AND a.id_evento=e.id_evento");
        if(!$query_s){
            die('Error en la consulta'. mysqli_error($mysqli));
        }
        $row = mysqli_fetch_array($query_s);
        $evento = $row['evento'];
        $fecha = $row['fecha'];
        $hora = date("g:i a",strtotime($row['hora']));
        $direccion = $row['direccion'];
        $lugar = $row['lugar'];

        $query = mysqli_query($mysqli, "UPDATE alumnos SET cupo=$cupo, alumno='$nombre', email='$email', estado = 0 WHERE id_alumno=$id_alumno");
        if(!$query){
            die('Error en el registro'. mysqli_error($mysqli));
        } 
        //enviar($email,$nombre,$indice,$evento,$lugar,$fecha,$hora,$direccion);
        echo '
        <div class="alert alert-success" role="alert" id="alerta_add">
            El alumno ha sido actualizado satisfactoriamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        ';
    }
}