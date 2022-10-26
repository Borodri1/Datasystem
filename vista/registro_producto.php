<?php
    session_start();
    if($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3){
        header("location: ../index.php");
    }
    include "../modelo/conexion.php";

    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || $_POST['precio'] <= 0 || empty($_POST['cantidad']) || $_POST['cantidad'] <= 0){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $proveedor = $_POST['proveedor'];
            $producto = $_POST['producto'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $usuario_id = $_SESSION['idUser'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name']; //para obtener el nombre de la imagen
            $type = $foto['type'];  //tipo de archivo
            $url_tem = $foto['tmp_name']; //url temporal
            $img_producto = 'img_producto.png'; 

            //validacion de la imagen
            if($nombre_foto != ''){
                $destino = '../modelo/sistema/img/uploads/';
                $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));//para que la foto j
                $img_producto = $img_nombre.'.jpg';
                $src = $destino.$img_producto;
            }

            $query_insert = mysqli_query($conection, "INSERT INTO producto(proveedor,descripcion, precio, existencia, usuario_id, foto) VALUES ('$proveedor','$producto','$precio','$cantidad', '$usuario_id', '$img_producto')");

            if($query_insert){
                if($nombre_foto != ''){
                    move_uploaded_file($url_tem, $src);
                }
                $alert = '<p class="msg_save">Producto guardado correctamente.</p>';
            }else{
                $alert = '<p class="msg_error">Error al guardar producto.</p>';
            }
            
        }
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro de Producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="../modelo/sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1 class="registro_title"><i class="fas fa-cubes"></i> Registro Producto</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST" enctype="multipart/form-data"><!-- para adjuntar archivos-->

                <div class="product-box">

                    <?php
                        $query_proveedor = mysqli_query($conection, "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");
                        $result_prveedor = mysqli_num_rows($query_proveedor);
                        mysqli_close($conection);
                    ?>

                    <select name="proveedor" id="proveedor" class="prod">
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

                <div class="product-box">
                    <input type="text" name="producto" id="producto" required>
                    <span>Nombre del producto</span>
                </div>

                <div class="product-box">
                    <input type="number" name="precio" id="precio" required>
                    <span>Precio del producto</span>
                </div>
                
                <div class="product-box">
                    <input type="number" name="cantidad" id="cantidad" required>
                    <span>Cantidad del producto</span>
                </div>

                <!-- para cargar la imagen-->
                <div class="photo">
                    <label for="foto">IMAGEN</label>
                    <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <!--<input type="submit" value="Guardar cliente" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar producto</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</html>