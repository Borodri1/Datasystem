<?php

    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ../index.php"); 
    }
    include "../modelo/conexion.php";

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de usuarios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<h1><i class="fas fa-users"></i> Lista de usuarios</h1>
        <a href="registro_usuarios.php" class="btn_new adap"><i class="fas fa-user-plus"></i> Crear usuario</a>

        <!-- formulario para el buscador-->
        <form action="../modelo/buscar_usuario.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="input_search">
            <!--<input type="submit" value="Buscar" class="btn_search">-->
            <button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
        </form>

        <a href="../controlador/excel.php" class="btn_new adap excel">Descargar Excel</a>

        <div class="containerTable">

            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>

            <?php 
            
                //para el paginador

                $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_register FROM usuario WHERE estatus = 1");

                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_register'];

                $por_pagina = 9; //numero de registros que queremos mostrar

                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);//ceil es para redondear el valor


                //para llenar las tablas
                $query = mysqli_query($conection,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE estatus = 1 ORDER BY u.idusuario ASC LIMIT $desde,$por_pagina");

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
                            <td>
                                <a href="../modelo/editar_usuario.php?id=<?php echo $data["idusuario"];?>" class="link_edit"><i class="fas fa-edit"></i> Editar</a>

                                <?php
                                    if($data["idusuario"] != 1){
                                ?>        

                                |
                                <a href="../modelo/eliminar_usuario.php?id=<?php echo $data["idusuario"];?>" class="link_delete"><i class="fas fa-trash-alt"></i> Eliminar</a>
                                
                                <?php
                                    }
                                ?>
                                </td>
                        </tr>
            <?php
                    }
                }
            ?>
            </table>
        </div>

        <div class="paginador">
            <ul>

                <?php
                    if($pagina != 1){
                ?>
                <li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
                <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fas fa-caret-left"></i></a></li>

                <?php
                    }
                    for($i = 1; $i <= $total_paginas; $i++){
                        if($i == $pagina){
                            echo '<li class="page_selected">'.$i.'</li>';
                        }else{
                            echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                        }
                    }

                    if($pagina != $total_paginas){
                ?>

                <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fas fa-caret-right"></i></a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i></a></li>
                <?php } ?>
            </ul>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>