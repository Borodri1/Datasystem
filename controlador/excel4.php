<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename = lista_productos.xls");

?>

<table>
    <tr>
        <th>Codigo</th>
        <th>Descripcion</th>
        <th>Precio</th>
        <th>Existencia</th>
        <th>Proveedor</th>
    </tr>

<?php 
    include "../modelo/conexion.php";
    //para llenar las tablas
    $query = mysqli_query($conection,"SELECT p.codproducto, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor WHERE p.estatus = 1 ORDER BY p.codproducto DESC");

    mysqli_close($conection);

    $result = mysqli_num_rows($query);

    if($result > 0){
        while($data = mysqli_fetch_array($query)){
?>
            <tr class="row<?php echo $data["codproducto"];?>">
                <td><?php echo $data["codproducto"];?></td>
                <td><?php echo $data["descripcion"];?></td>
                <td class="celPrecio"><?php echo $data["precio"];?></td>
                <td class="celExistencia"><?php echo $data["existencia"];?></td>
                <td><?php echo $data["proveedor"];?></td>
            </tr>
<?php
        }
    }
?>
</table>