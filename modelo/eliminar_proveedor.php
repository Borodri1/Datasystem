<?php

    session_start();
    if($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3){
        header("location: ../index.php");
    }
    include "conexion.php";
    if(!empty($_POST)){

        if(empty($_POST['idproveedor'])){
            header('locarion: ../vista/lista_proveedores.php');
            mysqli_close($conection);
        }

        $idproveedor = $_POST['idproveedor'];

        //eliminar definitivamente usuario
        //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE idusuario = $idusuario");

        //poner estatus a 0 para que se inhabilite y no se muestre
        $query_delete = mysqli_query($conection, "UPDATE proveedor SET estatus = 0 WHERE codproveedor = $idproveedor");

        mysqli_close($conection);

        if($query_delete){
            header('location: ../vista/lista_proveedores.php');
        }else{
            echo "Error al eliminar proveedor";
        }
    }

    //para recuperar detos del usuario 
    if(empty($_REQUEST['id'])){//request recibe tanto get como post por si se cambia el metodo de envio de datos
        header('location: ../vista/lista_proveedores.php');
        mysqli_close($conection);
    }else{  

        $idproveedor = $_REQUEST['id'];
        $query = mysqli_query($conection, "SELECT * FROM proveedor WHERE codproveedor = $idproveedor");

        mysqli_close($conection);

        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                $proveedor = $data['proveedor']; 
                $contacto = $data['contacto'];
                $telefono = $data['telefono'];
            }
        }else{
            header('location: ../vista/lista_proveedores.php');
        }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
            <i class="fas fa-building fa-7x" style = "color: #c66262"></i>
            <br>
            <br>
            <h2>¿Esta seguro de eliminar el siguiente proveedor?</h2>
            <p>Nombre del proveedor: <span><?php echo $proveedor; ?></span></p>
            <p>Contacto del proveedor: <span><?php echo $contacto; ?></span></p>
            <p>Telefono del proveedor: <span><?php echo $telefono; ?></span></p>

            <form action="" method="POST" class="form_delete">
                <input type="hidden" name="idproveedor" value="<?php echo $idproveedor; ?>">
                <a href="../vista/lista_proveedores.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
                <!--<input type="submit" value="Eliminar" class="btn_accept">-->
                <button type="submit" class="btn_accept"><i class="fas fa-trash-alt"></i> Eliminar</button>
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>