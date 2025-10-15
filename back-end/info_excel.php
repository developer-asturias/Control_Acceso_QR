<?php
//Informe de asistencia de estudiantes y invitados 
header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
header('Content-Disposition: attachment; filename=listado_asistencia.xls');

include('../config/database.php');
$id_evento=$_GET['id'];

$queryc = mysqli_query($mysqli, "SELECT * FROM alumnos a, eventos e WHERE e.id_evento=$id_evento AND a.id_evento=e.id_evento") or die('error: '.mysqli_error($mysqli));
?>

<table border="1" cellpadding="2" cellspacing="0" width="100%">
    <thead>
        <tr>
    	    <th colspan="9" style="background-color: blue;color:white;font-size: 20px;">LISTADO DE ASISTENCIA DE ALUMNOS Y INVITADOS</th>
        </tr>
        <tr>
            <th>Nro</th>
            <th>Indice</th>
            <th>Alumno</th>
            <th>Identificacion</th>
            <th>Titulo</th>
            <th>Evento</th>
            <th>Asist</th>
            <th>Cupo</th>
            <th>Aplico</th>
        </tr>
    </thead>
    <tbody>
    <?php  
    $num = 1; $sumador=0;$d_estado='';
    while ($data = mysqli_fetch_assoc($queryc)) {
        $indice = $data['indice'];
        //Invitados
        $query_count=mysqli_query($mysqli,"SELECT COUNT(*) total FROM asistencias WHERE cod_alumno='$indice' "); //and reservado=0
        $data1 = mysqli_fetch_array($query_count);
        $aplico = $data1['total'];
        //asistencia
        $query_asist=mysqli_query($mysqli,"SELECT COUNT(*) total FROM asistencias WHERE cod_alumno='$indice' and reservado=1"); // 
        $data_asis = mysqli_fetch_array($query_asist);
        $asistencia = $data_asis['total'];
        if($asistencia > 0){$var = 'SI';}else{$var = 'NO';}
        
        ?>
        <tr>
          <td><?php echo $num; ?></td>
          <td><?php echo $data['indice']; ?></td>
          <td><?php echo $data['alumno']; ?></td>
          <td><?php echo $data['identificacion']; ?></td>
          <td><?php echo $data['titulo']; ?></td>
          <td><?php echo $data['evento']; ?></td>
          <td><?php echo $var; ?></td>
          <td><?php echo $data['cupo']; ?></td>
          <td><?php echo $aplico; ?></td>
        </tr>
    <?php $num++;   } ?>
    
    </tbody>
</table>