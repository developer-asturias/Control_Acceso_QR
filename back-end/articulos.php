<?php
include('../config/database.php');
$metodo=$_SERVER['REQUEST_METHOD'];

//Registrar nuevo articulo
if($metodo=='POST'){
    //Si me manda el id, quiere decir que se va a editar
    if(isset($_POST['id'])){
        $nombre=$_POST['nombre'];
        $presentacion = $_POST['presentacion'];
        $forma = $_POST['forma'];
        $precio = $_POST['precio'];
        $id = $_POST['id'];
        $query = mysqli_query($mysqli, "UPDATE articulos SET articulo='$nombre', presentacion='$presentacion',forma='$forma',precio='$precio',cliente=1 WHERE id_articulo=$id");
        if(!$query){
            die('Error en el registro'. mysqli_error($mysqli));
        }
        echo '
        <div class="alert alert-success" role="alert" id="alerta1">
            El articulo ha sido actualizado satisfactoriamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        ';
    //Sino, se hace el registro 
    }else{ 
        $nombre=$_POST['nombre'];
        $presentacion = $_POST['presentacion'];
        $forma = $_POST['forma'];
        $precio = $_POST['precio'];
        $cliente = $_POST['cliente'];
        $query = mysqli_query($mysqli, "INSERT INTO articulos VALUES (NULL,'$nombre','$presentacion','$forma','$precio','$cliente')");
        if(!$query){
            die('Error en el registro'. mysqli_error($mysqli));
        }
        echo '
        <div class="alert alert-success" role="alert" id="alerta_add">
        El articulo ha sido registrado satisfactoriamente!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        ';
    }
}

//Consultar todos
if($metodo=='GET'){
    //Si se mando un id quiere decir que se hara una busqueda individual
    if(isset($_GET['id_articulo'])){
        $id = $_GET['id_articulo'];
        $query = mysqli_query($mysqli, "SELECT * FROM articulos WHERE id_articulo='$id'");
        if(!$query){
            die('Error en la consulta'. mysqli_error($mysqli));
        }

        $json = array(); 
        while($row = mysqli_fetch_array($query)){
            $json[] = array(
                'name' => $row['articulo'],
                'presentacion' => $row['presentacion'],
                'forma' => $row['forma'],
                'precio' => $row['precio'],
                'id' => $row['id_articulo']
            );
        }
        $jsonstring = json_encode($json[0]);
        echo $jsonstring; 
    //Sino se hace la busqueda de todos
    }else{
        $query = mysqli_query($mysqli, "SELECT * FROM articulos");
    if(!$query){
        die('Error en la consulta'. mysqli_error($mysqli));
    }

    $json = array(); 
    while($row = mysqli_fetch_array($query)){
        $json[] = array(
            'name' => $row['articulo'],
            'presentacion' => $row['presentacion'],
            'forma' => $row['forma'],
            'precio' => $row['precio'],
            'id' => $row['id_articulo']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
    }  
    
}

//Eliminar
if($metodo=='DELETE'){
    $id=$_GET['id_articulo'];
    $query = mysqli_query($mysqli, "DELETE FROM articulos WHERE id_articulo='$id'");
    if(!$query){
        die('Error en el registro'. mysqli_error($mysqli));
    }
    echo '
    <div class="alert alert-danger" role="alert" id="alerta_delete">
    El articulo ha sido eliminado satisfactoriamente!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    ';
}