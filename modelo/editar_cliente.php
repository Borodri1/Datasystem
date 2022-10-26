<?php
    session_start();

    include "conexion.php";
    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['suburbio']) || empty($_POST['codpostal'])){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $idCliente = $_POST['id'];
            $nit = $_POST['nit'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $suburbio = $_POST['suburbio'];
            $codPostal = $_POST['codpostal'];

            $result = 0;
            if(is_numeric($nit) and $nit != 0){
                //verificamos que no se repita ni usuario ni correo
                $query = mysqli_query($conection, "SELECT * FROM cliente WHERE (nit = '$nit' AND idcliente != $idCliente)");

                $result = mysqli_fetch_array($query);

            }

            if($result > 0){
                $alert = '<p class="msg_error">El nit ya existe, ingrese otro</p>';
            }else{
                //por si se manda vacio se asigan automaticamente vacio
                if($nit == ''){
                    $nit = 0;
                }

                $sql_update = mysqli_query($conection, "UPDATE cliente SET nit = $nit, nombre = '$nombre', telefono = '$telefono', direccion = '$direccion', suburbio = '$suburbio', codigo_postal = '$codPostal' WHERE idcliente = $idCliente");
                
                //cuando se ejecute esta instruccion nos devuelve verdadero o falso por lo cual validamos esto
                if($sql_update){
                    $alert = '<p class="msg_save">Cliente actualizado correctamente</p>';
                }else{
                    $alert = '<p class="msg_error">Error al actualizar usuario</p>';
                }
            }

        }
    }

    //Mostrar datos para actualizar
    if(empty($_REQUEST['id'])){
        header('location: ../vista/lista_clientes.php');
        mysqli_close($conection);
    }
    $idcliente = $_REQUEST['id'];
    $sql = mysqli_query($conection, "SELECT * FROM cliente WHERE idcliente = $idcliente and estatus = 1"); //no es necesario comillas por que es entero y no cadena

    mysqli_close($conection);
    $result_sql = mysqli_num_rows($sql); //devuelve el numero de filas
    if($result_sql == 0){
        header('location: ../vista/lista_clientes.php');
    }else{
        while($data = mysqli_fetch_array($sql)){
            $idcliente  = $data['idcliente'];
            $nit = $data['nit'];
            $nombre = $data['nombre'];
            $telefono = $data['telefono'];
            $direccion = $data['direccion'];
            $suburbio = $data['suburbio'];
            $codPostal = $data['codigo_postal'];
        }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Editar cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1><i class="fas fa-edit"></i> Editar cliente</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST">
                
                <div class="client-edit">
                    <input type="hidden" name="id" value="<?php echo $idcliente;?>">
                    <input type="number" name="nit" id="nit" value="<?php echo $nit; ?>">
                    <span>Numero de nit</span>
                </div>

                <div class="client-edit">
                    <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" required>
                    <span>Nombre completo</span>
                </div>

                <div class="client-edit">
                    <input type="number" name="telefono" id="telefono" value="<?php echo $telefono; ?>" required>
                    <span>Telefono</span>
                </div>

                <div class="client-edit">
                    <input type="text" name="direccion" id="direccion" value="<?php echo $direccion; ?>" required>
                    <span>Direccion</span>
                </div>

                <div class="client-edit">
                    <input type="text" name="suburbio" id="suburbio" value="<?php echo $suburbio; ?>" required>
                    <span>Suburbio</span>
                </div>

                <div class="client-edit">
                    <input type="number" name="codpostal" id="codpostal" value="<?php echo $codPostal; ?>" class="cli-dir" required>
                    <span>Codigo postal</span>
                </div>

                <!--<input type="submit" value="Actualizar cliente" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar cliente</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</html>