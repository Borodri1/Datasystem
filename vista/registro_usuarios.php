<?php

    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ../index.php");
    }

    include "../modelo/conexion.php";
    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol'])){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = md5($_POST['clave']);
            $rol = $_POST['rol']; 

            //verificamos que no se repita ni usuario ni correo
            $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email'");
            
            //mysqli_close($conection);
            
            $result = mysqli_fetch_array($query);

            if($result > 0){
                $alert = '<p class="msg_error">El correo o el usuario ya exite</p>';
            }else{
                $query_insert = mysqli_query($conection, "INSERT INTO usuario(nombre, correo, usuario, clave, rol) VALUES ('$nombre','$email','$user','$clave','$rol')");
                //cuando se ejecute esta instruccion nos devuelve verdadero o falso por lo cual validamos esto
                if($query_insert){
                    $alert = '<p class="msg_save">Usuario creado correctamente</p>';
                }else{
                    $alert = '<p class="msg_error">Error al crear usuario</p>';
                }
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro de usuarios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="../modelo/sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1 class="registro_title"><i class="fas fa-user-plus"></i> Registro de usuarios</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST">
                <div class="registro-box">
                    <input type="text" name="nombre" id="nombre" required>
                    <span>Nombre completo</span>
                </div>

                <div class="registro-box">
                    <input type="email" name="correo" id="correo" required>
                    <span>Correo electronico</span>
                </div>

                <div class="registro-box">
                    <input type="text" name="usuario" id="usuario" required>
                    <span>Usuario</span>
                </div>

                <div class="registro-box">
                    <input type="passwprd" name="clave" id="clave" required>
                    <span>Contraseña</span>
                </div>

                <div class="registro-box">

                    <?php
                        //para que se extraiga el valor del rol de la base de datos
                        $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                        mysqli_close($conection);
                        $result_rol = mysqli_num_rows($query_rol);
                    ?>
                    <select name="rol" id="rol">
                        <?php
                            if($result_rol > 0){
                                while($rol = mysqli_fetch_array($query_rol)){
                        ?>
                                <option value="<?php echo $rol["idrol"]?>"><?php echo $rol["rol"]?></option>
                        <?php
                                }
                            }
                        ?>

                    </select>
                    <span>Tipo usuario</span>
                </div>
                <!--<input type="submit" value="Crear ususario" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-save"></i> Crear usuario</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</html>