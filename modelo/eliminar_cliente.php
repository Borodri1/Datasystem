<?php

    session_start();
    if($_SESSION['rol'] != 1 && $_SESSION['rol'] != 2){
        header("location: ../index.php");
    }
    include "conexion.php";
    if(!empty($_POST)){

        if(empty($_POST['idcliente'])){
            header('locarion: ../vista/lista_clientes.php');
            mysqli_close($conection);
        }

        $idcliente = $_POST['idcliente'];

        //eliminar definitivamente usuario
        //$query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE idusuario = $idusuario");

        //poner estatus a 0 para que se inhabilite y no se muestre
        $query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE idcliente = $idcliente");

        mysqli_close($conection);

        if($query_delete){
            header('location: ../vista/lista_clientes.php');
        }else{
            echo "Error al eliminar cliente";
        }
    }

    //para recuperar detos del usuario 
    if(empty($_REQUEST['id'])){//request recibe tanto get como post por si se cambia el metodo de envio de datos
        header('location: ../vista/lista_clientes.php');
        mysqli_close($conection);
    }else{  

        $idcliente = $_REQUEST['id'];
        $query = mysqli_query($conection, "SELECT * FROM cliente WHERE idcliente = $idcliente");

        mysqli_close($conection);

        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                $nit = $data['nit']; 
                $nombre = $data['nombre'];
                $telefono = $data['telefono'];
            }
        }else{
            header('location: ../vista/lista_clientes.php');
        }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
            <i class="fas fa-user-times fa-7x" style = "color: #c66262"></i>
            <br>
            <br>
            <h2>¿Esta seguro de eliminar el siguiente cliente?</h2>
            <p>Nombre del cliente: <span><?php echo $nombre; ?></span></p>
            <p>Telefono del cliente: <span><?php echo $telefono; ?></span></p>
            <p>Nit del cliente: <span><?php echo $nit; ?></span></p>

            <form action="" method="POST" class="form_delete">
                <input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">
                <a href="../vista/lista_clientes.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
                <!--<input type="submit" value="Eliminar" class="btn_accept">-->
                <button type="submit" class="btn_accept"><i class="fas fa-trash-alt"></i> Eliminar</button>
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>