<?php

    session_start();

    include "../modelo/conexion.php";

    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['suburbio']) || empty($_POST['codpostal'])){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $nit = $_POST['nit'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $suburbio = $_POST['suburbio'];
            $codPostal = $_POST['codpostal'];
            $usuario_id = $_SESSION['idUser'];

            $result = 0;

            if(is_numeric($nit) && $nit != 0){
                $query = mysqli_query($conection, "SELECT * FROM cliente WHERE nit = '$nit'");
                $result = mysqli_fetch_array($query);
            }

            if($result > 0){
                $alert = '<p class="msg_error">El numero de nit ya existe</p>';
            }else{
                $query_insert = mysqli_query($conection, "INSERT INTO cliente(nit,nombre, telefono, direccion, suburbio, codigo_postal, usuario_id) VALUES ('$nit','$nombre','$telefono','$direccion', '$suburbio', '$codPostal', '$usuario_id')");

                if($query_insert){
                    $alert = '<p class="msg_save">Cliente registrado correctamente.</p>';
                }else{
                    $alert = '<p class="msg_error">Error al registrar cliente.</p>';
                }
            }
        }
        mysqli_close($conection);
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro de clientes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="../modelo/sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1 class="registro_title"><i class="fas fa-user-plus"></i> Registro de clientes</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST">
                <div class="cliente-box">
                    <input type="number" name="nit" id="nit">
                    <span>Numero de nit</span>
                </div>

                <div class="cliente-box">
                    <input type="text" name="nombre" id="nombre" required>
                    <span>Nombre completo</span>
                </div>

                <div class="cliente-box">
                    <input type="number" name="telefono" id="telefono" required>
                    <span>Telefono</span>
                </div>

                <div class="cliente-box">
                    <input type="text" name="direccion" id="direccion"  required>
                    <!--"Direccion,suburbio,codigo postal"-->
                    <span>Direccion</span>
                </div>
                <div class="cliente-box">
                    <input type="text" name="suburbio" id="suburbio"  required>
                    <!--"Direccion,suburbio,codigo postal"-->
                    <span>Suburbio</span>
                </div>
                <div class="cliente-box">
                    <input type="number" name="codpostal" id="codpostal" class="dir" required>
                    <span>Codigo postal</span>
                </div>

                <!--<input type="submit" value="Guardar cliente" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar cliente</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</html>