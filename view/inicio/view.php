<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h1 class="h3 mb-0 text-gray-800">Datos de Satrack</h1>
    </div>

    <!-- Content Row -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> <i class="fa fa-edit"></i> Leer Archivo</h6>
        </div>
        <div class="card-body">
            <form class="form-horizontal" enctype="multipart/form-data"  action="?inicio" method="POST">
                <div class="row">
                    <div class="col-sm-6">
                       <div class="form-group">
                            Archivo
                            <input type="file" name="fichero_usuario" class="form-control" required>
                        </div>  
                    </div>
                    <div class="col-sm-3 py-4">
                        <input type="submit" class="btn btn-primary " name="submit" value="Enviar fichero" />
                    </div>
                </div>
                
            </form>
        </div>
    </div>
    <?php 
        
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/
 ?>
</div>
<!-- /.container-fluid -->