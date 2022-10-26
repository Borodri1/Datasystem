<?php
    session_start();
    include "conexion.php";
    if($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3){
        header("location: ../index.php");
    }

    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove'])){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $codproducto = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $producto = $_POST['producto'];
            $precio = $_POST['precio'];
            $img_producto = $_POST['foto_actual'];
            $img_remove = $_POST['foto_remove'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name']; //para obtener el nombre de la imagen
            $type = $foto['type'];  //tipo de archivo
            $url_tem = $foto['tmp_name']; //url temporal
            $upd = ''; 

            //validacion de la imagen
            if($nombre_foto != ''){
                $destino = 'sistema/img/uploads/';
                $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));//para que la foto j
                $img_producto = $img_nombre.'.jpg';
                $src = $destino.$img_producto;
            }else{
                if($_POST['foto_actual'] != $_POST['foto_remove']){
                    $img_producto = 'img_producto.png';
                }
            }

            $query_update = mysqli_query($conection, "UPDATE producto SET descripcion = '$producto', proveedor = $proveedor, precio = $precio, foto = '$img_producto' WHERE codproducto = $codproducto");

            if($query_update){
                if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove'])){
                    unlink('sistema/img/uploads/'.$_POST['foto_actual']);
                }

                if($nombre_foto != ''){
                    move_uploaded_file($url_tem, $src);
                }
                $alert = '<p class="msg_save">Producto actualizado correctamente.</p>';
            }else{
                $alert = '<p class="msg_error">Error al actualizar producto.</p>';
            }
            
        }
    }

    //validar producto
    if(empty($_REQUEST['id'])){
        header ("location: ../vista/lista_producto.php");
    }else{
        $id_producto = $_REQUEST['id'];
        if(!is_numeric($id_producto)){
            header ("location: ../vista/lista_producto.php");
        }
        $query_producto = mysqli_query($conection, "SELECT p.codproducto, p.descripcion, p.precio, p.foto, pr.codproveedor, pr.proveedor FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor WHERE p.codproducto = $id_producto AND p.estatus = 1");

        $result_producto = mysqli_num_rows($query_producto);

        //para que se muestre la foto
        $foto = '';
        $classRemove = 'notBlock';

        if($result_producto > 0){
            $data_producto = mysqli_fetch_assoc($query_producto);
            if($data_producto['foto'] != 'img_producto.png'){
                $classRemove = '';
                $foto = '<img id="img" src="sistema/img/uploads/'.$data_producto['foto'].'" alt="Producto">';
            }
        }else{
            header("location: ../vista/lista_productos.php");
        }
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1 class="registro_title"><i class="fas fa-cubes"></i> Actualizar Producto</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST" enctype="multipart/form-data"><!-- para adjuntar archivos-->

                <input type="hidden" name="id" value="<?php echo $data_producto['codproducto']; ?>">
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">

                <div class="product-edit">

                    <?php
                        $query_proveedor = mysqli_query($conection, "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                        $result_prveedor = mysqli_num_rows($query_proveedor);
                        mysqli_close($conection);
                    ?>

                    <select name="proveedor" id="proveedor" class="notitemone">
                        <option value="<?php echo $data_producto['codproveedor']; ?>" selected><?php echo $data_producto['proveedor']?></option>
                        <?php
                            if($result_prveedor>0){
                                while($proveedor = mysqli_fetch_array($query_proveedor)){
                        ?>
                            <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']?></option>
                        <?php
                                }
                            }
                        ?>
                    </select>
                    <span>Proveedor</span>
                </div>

                <div class="product-edit">
                    <input type="text" name="producto" id="producto" value="<?php echo $data_producto['descripcion']; ?>" required>
                    <span>Nombre del producto</span>
                </div>

                <div class="product-edit">
                    <input type="number" name="precio" id="precio" value="<?php echo $data_producto['precio']; ?>" required>
                    <span>Precio del producto</span>
                </div>

                <!-- para cargar la imagen-->
                <div class="photo">
                    <label for="foto">IMAGEN</label>
                    <div class="prevPhoto">
                        <span class="delPhoto <?php echo $classRemove; ?>">X</span>
                        <label for="foto"></label>
                        <?php echo $foto; ?>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <!--<input type="submit" value="Guardar cliente" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-save"></i> Actualizar producto</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</html>