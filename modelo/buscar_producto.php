<?php

    session_start();

    include "conexion.php";

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de productos</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <?php
            $busqueda = '';
            $search_proveedor = '';
            if(empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor'])){
                header("location: ../vista/lista_producto.php");
            }
            if(!empty($_REQUEST['busqueda'])){
                $busqueda = strtolower($_REQUEST['busqueda']);
                $where = "(p.codproducto LIKE '%$busqueda%' OR p.descripcion LIKE '%$busqueda%') AND p.estatus = 1";
                $buscar = 'busqueda='.$busqueda;
            }
            if(!empty($_REQUEST['proveedor'])){
                $search_proveedor = $_REQUEST['proveedor'];
                $where = "p.proveedor LIKE $search_proveedor AND p.estatus = 1";
                $buscar = 'proveedor='.$search_proveedor;
            }
        ?>
		<h1><i class="fas fa-cube"></i> Lista de productos</h1>
        <a href="../vista/registro_producto.php" class="btn_new btn_new_product"><i class="fas fa-truck-loading"></i> Crear producto</a>

        <!-- formulario para el buscador-->
        <form action="buscar_producto.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" class="input_search" value="<?php echo $busqueda; ?>">
            <!--<input type="submit" value="Buscar" class="btn_search">-->
            <button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
        </form>
    <div class="containerTable">
        <table>
            <tr>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>
                    <?php
                        $pro = 0;
                        if(!empty($_REQUEST['proveedor'])){
                            $pro = $_REQUEST['proveedor'];
                        }
                        $query_proveedor = mysqli_query($conection, "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                        $result_prveedor = mysqli_num_rows($query_proveedor);
                    ?>

                    <select name="proveedor" id="search_proveedor">
                        <option value="" selected>PROVEEDOR</option>
                    <?php
                        if($result_prveedor>0){
                            while($proveedor = mysqli_fetch_array($query_proveedor)){
                                if($pro == $proveedor['codproveedor']){
                    ?>
                                    <option value="<?php echo $proveedor['codproveedor']; ?>" selected><?php echo $proveedor['proveedor']?></option>
                    <?php       
                                }else{
                    ?>
                                    <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']?></option>
                    <?php
                                }
                            }
                        }
                    ?>
                    </select>
                </th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>

        <?php 
        
            //para el paginador

            $sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_register FROM producto as p WHERE $where"); 

            $result_register = mysqli_fetch_array($sql_register);
            $total_registro = $result_register['total_register'];

            $por_pagina = 5; //numero de registros que queremos mostrar

            if(empty($_GET['pagina'])){
                $pagina = 1;
            }else{
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);//ceil es para redondear el valor


            //para llenar las tablas
            $query = mysqli_query($conection,"SELECT p.codproducto, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor WHERE $where ORDER BY p.codproducto DESC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);

            if($result > 0){
                while($data = mysqli_fetch_array($query)){

                    if($data['foto'] != 'sistema/img/img_producto.png'){
                        $foto = 'sistema/img/uploads/'.$data['foto'];
                    }else{
                        $foto = 'sistema/img/'.$data['foto']; 
                    }
        ?>
                    <tr class="row<?php echo $data["codproducto"];?>">
                        <td><?php echo $data["codproducto"];?></td>
                        <td><?php echo $data["descripcion"];?></td>
                        <td class="celPrecio"><?php echo $data["precio"];?></td>
                        <td class="celExistencia"><?php echo $data["existencia"];?></td>
                        <td><?php echo $data["proveedor"];?></td>
                        <td class="img_producto"><img src="<?php echo $foto;?>" alt="<?php echo $data["descripcion"];?>"></td>
                        <?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3){ ?>
                        <td>
                            <a href="#" product = "<?php echo $data["codproducto"];?>" class="link_add add_product"><i class="fas fa-plus"></i> Agregar</a>
                            |
                            <a href="editar_producto.php?id=<?php echo $data["codproducto"];?>" class="link_edit"><i class="fas fa-edit"></i> Editar</a>
                            |
                            <a href="#" product = "<?php echo $data["codproducto"];?>" class="link_delete del_product"><i class="fas fa-trash-alt"></i> Eliminar</a>
                        </td>
                        <?php } ?>
                    </tr>
        <?php
                }
            }
        ?>
        </table>
    </div>        
        <?php
            if($total_paginas != 0){
        ?>

        <div class="paginador">
            <ul>

                <?php
                    if($pagina != 1){
                ?>
                <li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-backward"></i></a></li>
                <li><a href="?pagina=<?php echo $pagina - 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-caret-left"></i></a></li>

                <?php
                    }
                    for($i = 1; $i <= $total_paginas; $i++){
                        if($i == $pagina){
                            echo '<li class="page_selected">'.$i.'</li>';
                        }else{
                            echo '<li><a href="?pagina='.$i.'&'.$buscar.'">'.$i.'</a></li>';
                        }
                    }

                    if($pagina != $total_paginas){
                ?>

                <li><a href="?pagina=<?php echo $pagina + 1; ?>&<?php echo $buscar; ?>"><i class="fas fa-caret-right"></i></a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>&<?php echo $buscar; ?>"><i class="fas fa-step-forward"></i></a></li>
                <?php } ?>
            </ul>
        </div>

        <?php } ?>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>