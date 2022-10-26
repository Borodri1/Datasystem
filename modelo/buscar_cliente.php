<?php
    session_start();
    include "conexion.php";

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de Clientes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

        <?php
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header('location: ../vista/lista_clientes.php');
                mysqli_close($conection);
            }
        ?>

		<h1><i class="fas fa-users"></i> Lista de clientes</h1>
        <a href="../vista/registro_clientes.php" class="btn_new"><i class="fas fa-user-plus"></i> Crear cliente</a>

        <!-- formulario para el buscador-->
        <form action="buscar_cliente.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="input_search" value="<?php echo $busqueda; ?>">
            <!--<input type="submit" value="Buscar" class="btn_search">-->
            <button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
        </form>
    <div class="containerTable">
        <table>
            <tr>
                <th>ID</th>
                <th>Nit</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Direccion</th>
                <th>Acciones</th>
            </tr>

        <?php 
        
            //para el buscador

            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_register FROM cliente WHERE (idcliente LIKE '%$busqueda%' OR nit LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR direccion LIKE '%$busqueda%') AND estatus = 1");

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
            $query = mysqli_query($conection,"SELECT * FROM cliente WHERE (idcliente LIKE '%$busqueda%' OR nit LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR direccion LIKE '%$busqueda%') AND estatus = 1 ORDER BY idcliente ASC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);

            if($result > 0){
                while($data = mysqli_fetch_array($query)){
        ?>
                    <tr>
                        <td><?php echo $data["idcliente"];?></td>
                        <td><?php echo $data["nit"];?></td>
                        <td><?php echo $data["nombre"];?></td>
                        <td><?php echo $data["telefono"];?></td>
                        <td><?php echo $data["direccion"];?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?php echo $data["idcliente"];?>" class="link_edit"><i class="fas fa-edit"></i> Editar</a>

                            <?php
                                if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
                            ?>        

                            |
                            <a href="eliminar_cliente.php?id=<?php echo $data["idcliente"];?>" class="link_delete"><i class="fas fa-trash-alt"></i> Eliminar</a>
                            
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
        <?php
            if($total_registro != 0){
        ?>
        <div class="paginador">
            <ul>

                <?php
                    if($pagina != 1){
                ?>
                <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-backward"></i></a></li>
                <li><a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-caret-left"></i></a></li>

                <?php
                    }
                    for($i = 1; $i <= $total_paginas; $i++){
                        if($i == $pagina){
                            echo '<li class="page_selected">'.$i.'</li>';
                        }else{
                            echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                        }
                    }

                    if($pagina != $total_paginas){
                ?>

                <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-caret-right"></i></a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-forward"></i></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php
            }
        ?>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>