<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename = lista_proveedores.xls");

?>

<table>
    <tr>
        <th>ID</th>
        <th>Proveedor</th>
        <th>Contacto</th>
        <th>Telefono</th>
        <th>Direccion</th>
        <th>Suburbio</th>
        <th>Codigo postal</th>
    </tr>

<?php 
    include "../modelo/conexion.php";

    //para llenar las tablas
    $query = mysqli_query($conection,"SELECT * FROM proveedor WHERE estatus = 1 ORDER BY codproveedor ASC");

    $result = mysqli_num_rows($query);

    if($result > 0){
        while($data = mysqli_fetch_array($query)){
            //para la fecha si se necesita
            /*
            $formato = 'Y-m-d H:i:s';//especificamos un formato para la fecha
            $fecha = DateTime::createFromFormat($formato, $data["dateadd"]);//pongo la fecha con el formato especificado*/
?>
            <tr>
                <td><?php echo $data["codproveedor"];?></td>
                <td><?php echo $data["proveedor"];?></td>
                <td><?php echo $data["contacto"];?></td>
                <td><?php echo $data["telefono"];?></td>
                <td><?php echo $data["direccion"];?></td>
                <td><?php echo $data["suburbio"];?></td>
                <td><?php echo $data["codigo_postal"];?></td>
                <!--<td><?php //echo $fecha->format('d-m-Y');?></td>-->
            </tr>
<?php
        }
    }
?>
</table>