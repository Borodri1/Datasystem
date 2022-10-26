<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename = lista_ventas.xls");

?>

<table>
    <tr>
        <th>No.</th>
        <th>Fecha / Hora</th>
        <th>Cliente</th>
        <th>Vendedor</th>
        <th>Estado</th>
        <th class="textright">Total Factura</th>
    </tr>

<?php 
    include "../modelo/conexion.php";
    //para llenar las tablas
    $query = mysqli_query($conection,"SELECT f.nofactura,f.fecha,f.totalfactura,f.codcliente,f.estatus,u.nombre as vendedor, cl.nombre as cliente FROM factura f INNER JOIN usuario u ON f.usuario = u.idusuario INNER JOIN cliente cl ON f.codcliente = cl.idcliente WHERE f.estatus != 10 ORDER BY f.fecha DESC");

    mysqli_close($conection);
    $result = mysqli_num_rows($query);

    if($result > 0){
        while($data = mysqli_fetch_array($query)){
            if($data["estatus"] == 1){
                $estado = '<span class="pagada">Pagada</span>';
            }else{
                $estado = '<span class="anulada">Anulada</span>';
            }
?>
            <tr id="row_<?php echo $data["nofactura"];?>">
                <td><?php echo $data["nofactura"];?></td>
                <td><?php echo $data["fecha"];?></td>
                <td><?php echo $data["cliente"];?></td>
                <td><?php echo $data["vendedor"];?></td>
                <td class="estado"><?php echo $estado;?></td>
                <td class="textright totalfactura"><span>$ AUD.</span><?php echo $data["totalfactura"];?></td>
            </tr>
<?php
        }
    }
?>
</table>