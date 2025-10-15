<?php
error_reporting(0);
session_start(); if($_SESSION['id_user'] != ''){ header('Location: main.php?inicio');}else{
?>
<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Andres Camilo Rivera">
    <title>Control de Acceso</title>
    <link rel=icon href="img/favicon1.png" sizes="40x40" type="image/png">
    <!-- Custom fonts for this template-->
    <link href="lib/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        <link href="lib/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="lib/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    </head>
    <body style="background-image: url(https://scontent.fbog2-4.fna.fbcdn.net/v/t1.6435-9/135499416_5552246021467724_4743999598469280096_n.png?_nc_cat=108&ccb=1-7&_nc_sid=e3f864&_nc_ohc=8RobXFbGjh0AX-BVJJ7&_nc_ht=scontent.fbog2-4.fna&oh=00_AfASMIdu7rBK4rC0dBv95dY1NufjpgGvd7_rYeLrejDLtQ&oe=63A4EF21); background-size: cover;">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-1 rounded-lg mt-5">
                                    <div class="card-header text-center bold">
                                        <h3><i class="fa fa-elevator"></i> Inicio de sesion</h3>
                                    </div>
                                    <div class="card-body">
                                        <form action="back-end/check-login.php" method="POST">
                                            <div class="form-group">
                                                <label for="inputEmailAddress">Usuario</label>
                                                <input class="form-control py-4" name="username" type="trxt" placeholder="Ingrese Usuario"  autofocus >
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword">Password</label>
                                                <input class="form-control py-4" name="password" type="password" placeholder="Ingrese Password" />
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox"><input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" /><label class="custom-control-label" for="rememberPasswordCheck">Recordar Datos</label></div>
                                            </div>
                                            <div class="form-group d-flex align-items-center">
                                                <input type="submit" class="btn btn-danger btn-user btn-block" name="Guardar" value="Iniciar Sesion">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small">En caso de inconvenientre comunicarse con el Administrador</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>
<?php } ?>