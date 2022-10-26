<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename = lista_usuarios.xls");

?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Usuario</th>
        <th>Rol</th>
    </tr>

<?php 
    include "../modelo/conexion.php";
    //para llenar las tablas
    $query = mysqli_query($conection,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE estatus = 1 ORDER BY u.idusuario ASC");

    $result = mysqli_num_rows($query);

    if($result > 0){
        while($data = mysqli_fetch_array($query)){
?>
            <tr>
                <td><?php echo $data["idusuario"];?></td>
                <td><?php echo $data["nombre"];?></td>
                <td><?php echo $data["correo"];?></td>
                <td><?php echo $data["usuario"];?></td>
                <td><?php echo $data["rol"];?></td>
            </tr>
<?php
        }
    }
?>
</table>