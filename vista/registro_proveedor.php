<?php

    session_start();
    if($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3){
        header("location: ../index.php");
    }
    include "../modelo/conexion.php";

    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['suburbio']) || empty($_POST['codpostal'])){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $suburbio = $_POST['suburbio'];
            $codPostal = $_POST['codpostal'];
            $usuario_id = $_SESSION['idUser'];

            $query_insert = mysqli_query($conection, "INSERT INTO proveedor(proveedor,contacto, telefono, direccion,suburbio,codigo_postal, usuario_id) VALUES ('$proveedor','$contacto','$telefono','$direccion','$suburbio','$codPostal','$usuario_id')");

            if($query_insert){
                    $alert = '<p class="msg_save">Proveedor registrado correctamente.</p>';
            }else{
                    $alert = '<p class="msg_error">Error al registrar prveedor.</p>';
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
	<title>Registro de proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="../modelo/sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1 class="registro_title"><i class="fas fa-building"></i> Registro de proveedor</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST">
                <div class="prove-box">
                    <input type="text" name="proveedor" id="proveedor" required>
                    <span>Nombre proveedor</span>
                </div>

                <div class="prove-box">
                    <input type="text" name="contacto" id="contacto" required>
                    <span>Nombre del contacto</span>
                </div>

                <div class="prove-box">
                    <input type="number" name="telefono" id="telefono" required>
                    <span>Telefono</span>
                </div>

                <div class="prove-box">
                    <input type="text" name="direccion" id="direccion" required>
                    <!--"Direccion,suburbio,codigo postal"-->
                    <span>Direccion</span>
                </div>
                <div class="prove-box">
                    <input type="text" name="suburbio" id="suburbio" required>
                    <span>Suburbio</span>
                </div>
                <div class="prove-box">
                    <input type="number" name="codpostal" id="codpostal" class="dir" required>
                    <span>Codigo postal</span>
                </div>
                
                <!--<input type="submit" value="Guardar cliente" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar proveedor</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</html>