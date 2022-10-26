<?php

    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ../index.php");
    }
    include "conexion.php";
    if(!empty($_POST)){

        if($_POST['idusuario'] == 1){
            header('location: ../vista/lista_usuarios.php');
            mysqli_close($conection);
            exit;
        }

        $idusuario = $_POST['idusuario'];

        //eliminar definitivamente usuario
        //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE idusuario = $idusuario");

        //poner estatus a 0 para que se inhabilite y no se muestre
        $query_delete = mysqli_query($conection, "UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuario");

        mysqli_close($conection);

        if($query_delete){
            header('location: ../vista/lista_usuarios.php');
        }else{
            echo "Error al eliminar usuario";
        }
    }

    //para recuperar detos del usuario 
    if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1){//request recibe tanto get como post por si se cambia el metodo de envio de datos
        header('location: ../vista/lista_usuarios.php');
        mysqli_close($conection);
    }else{  

        $idusuario = $_REQUEST['id'];
        $query = mysqli_query($conection, "SELECT u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.idusuario = $idusuario");

        mysqli_close($conection);

        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                $nombre = $data['nombre'];
                $usuario = $data['usuario'];
                $rol = $data['rol'];
            }
        }else{
            header('location: ../vista/lista_usuarios.php');
        }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
            <i class="fas fa-user-times fa-7x" style = "color: #c66262"></i>
            <br>
            <br>
            <h2>¿Esta seguro de eliminar el siguiente usuario?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>Usuario: <span><?php echo $usuario; ?></span></p>
            <p>Tipo de Usuario: <span><?php echo $rol; ?></span></p>

            <form action="" method="POST" class="form_delete">
                <input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
                <a href="../vista/lista_usuarios.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
                <!--<input type="submit" value="Aceptar" class="btn_accept">-->
                <button type="submit" class="btn_accept"><i class="fas fa-trash-alt"></i> Eliminar</button>
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>