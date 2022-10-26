<?php
    session_start();
    if($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3){
        header("location: ../index.php");
    }
    include "conexion.php";

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de proveedores</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

        <?php
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header('location: ../vista/lista_proveedores.php');
                mysqli_close($conection);
            }
        ?>

		<h1><i class="fas fa-building"></i> Lista de proveedores</h1>
        <a href="../vista/registro_proveedor.php" class="btn_new"><i class="fas fa-plus"></i> Crear proveedor</a>

        <!-- formulario para el buscador-->
        <form action="buscar_proveedor.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="input_search" value="<?php echo $busqueda; ?>">
            <!--<input type="submit" value="Buscar" class="btn_search">-->
            <button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
        </form> 
    <div class="containerTable">
        <table>
            <tr>
                <th>ID</th>
                <th>Proveedor</th>
                <th>Contacto</th>
                <th>Telefono</th>
                <th>Direccion</th>
                <th>Fecha de creacion</th>
                <th>Acciones</th>
            </tr>

        <?php 
        
            //para el buscador

            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_register FROM proveedor WHERE (codproveedor LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%' OR contacto LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%') AND estatus = 1");

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
            $query = mysqli_query($conection,"SELECT * FROM proveedor WHERE (codproveedor LIKE '%$busqueda%' OR proveedor LIKE '%$busqueda%' OR contacto LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%') AND estatus = 1 ORDER BY codproveedor ASC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);

            if($result > 0){
                while($data = mysqli_fetch_array($query)){
                    $formato = 'Y-m-d H:i:s';//especificamos un formato para la fecha
                    $fecha = DateTime::createFromFormat($formato, $data["dateadd"]);//pongo la fecha con el formato especificado
        ?>
                    <tr>
                    <td><?php echo $data["codproveedor"];?></td>
                        <td><?php echo $data["proveedor"];?></td>
                        <td><?php echo $data["contacto"];?></td>
                        <td><?php echo $data["telefono"];?></td>
                        <td><?php echo $data["direccion"];?></td>
                        <td><?php echo $fecha->format('d-m-Y');?></td>
                        <td>
                            <a href="editar_proveedor.php?id=<?php echo $data["codproveedor"];?>" class="link_edit"><i class="fas fa-edit"></i> Editar</a> 
                            |
                            <a href="eliminar_proveedor.php?id=<?php echo $data["codproveedor"];?>" class="link_delete"><i class="fas fa-trash-alt"></i> Eliminar</a>
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