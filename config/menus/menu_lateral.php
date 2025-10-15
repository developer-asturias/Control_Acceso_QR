<?php 
$ruta=explode("?",$_SERVER['REQUEST_URI']);
$m=$ruta[1];
//Inicio
if($m=='inicio'){$i='active';}
elseif($m=='eventos'){$e='active';}
elseif($m=='alumnos'){$a='active';}
elseif($m=='asistencias'){$aa='active';}
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="?inicio">
        <div class="sidebar-brand-icon">
            <i class="fa fa-elevator"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
<?php if ($_SESSION['permisos_acceso']=='Super Admin'): ?>
    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo $aa;?>">
        <a class="nav-link" href="?asistencias">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Inicio</span></a>
    </li>
    <li class="nav-item <?php echo $i;?>">
        <a class="nav-link" href="?inicio">
            <i class="fas fa-fw fa-user"></i>
            <span>Invitados</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item <?php echo $e;?>">
        <a class="nav-link" href="?eventos">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Eventos</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item <?php echo $a;?>">
        <a class="nav-link" href="?alumnos">
            <i class="fa-solid fa-users"></i>
            <span>Alumnos</span></a>
    </li>
<?php endif ?>

<?php if ($_SESSION['permisos_acceso']=='Gerente'): ?>
    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo $aa;?>">
        <a class="nav-link" href="?asistencias">
            <i class="fas fa-fw fa-user"></i>
            <span>Alumnos</span></a>
    </li>
    <li class="nav-item <?php echo $i;?>">
        <a class="nav-link" href="?inicio">
            <i class="fas fa-fw fa-users"></i>
            <span>Invitados</span></a>
    </li>
<?php endif ?>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) 
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>-->

</ul>
<!-- End of Sidebar -->