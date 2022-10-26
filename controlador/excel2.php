<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename = lista_clientes.xls");

?>

<table>
    <tr>
        <th>ID</th>
        <th>Nit</th>
        <th>Nombre</th>
        <th>Telefono</th>
        <th>Direccion</th>
        <th>Suburbio</th>
        <th>Codigo postal</th>
    </tr>

<?php 

    include "../modelo/conexion.php";
    //para llenar las tablas
    $query = mysqli_query($conection,"SELECT * FROM cliente WHERE estatus = 1 ORDER BY idcliente ASC");

    $result = mysqli_num_rows($query);

    if($result > 0){
        while($data = mysqli_fetch_array($query)){

            if($data["nit"] == 0){
                $nit = 'C/F';
            }else{
                $nit = $data["nit"];
            }
?>
            <tr>
                <td><?php echo $data["idcliente"];?></td>
                <td><?php echo $nit;?></td>
                <td><?php echo $data["nombre"];?></td>
                <td><?php echo $data["telefono"];?></td>
                <td><?php echo $data["direccion"];?></td>
                <td><?php echo $data["suburbio"];?></td>
                <td><?php echo $data["codigo_postal"];?></td>
            </tr>
<?php
        }
    }
?>
</table>