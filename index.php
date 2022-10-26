<?php
    $alert = '';
    session_start();
    if(!empty($_SESSION['active'])){
        header('location: vista/index.php');
    }else{  
        if(!empty($_POST)){
            if(empty($_POST['usuario']) || empty($_POST['clave'])){
                $alert = "Ingrese su usario y contraseña"; 
            }else{
                require_once "modelo/conexion.php";
                $user = mysqli_real_escape_string($conection,$_POST['usuario']);
                $pass = md5(mysqli_real_escape_string($conection,$_POST['clave']));

                $query = mysqli_query($conection, "SELECT u.idusuario,u.nombre,u.correo,u.usuario,r.idrol,r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.usuario = '$user' AND u.clave = '$pass'");
                mysqli_close($conection);
                /* selecciona la fila con un numero ya que existe ese usuario y clave */
                $result = mysqli_num_rows($query);

                if($result > 0){
                    /* guardamos los datos de la fila en data*/
                    $data = mysqli_fetch_array($query);
                    $_SESSION['active'] = true;
                    $_SESSION['idUser'] = $data['idusuario'];
                    $_SESSION['nombre'] = $data['nombre'];
                    $_SESSION['email'] = $data['correo'];
                    $_SESSION['user'] = $data['usuario'];
                    $_SESSION['rol'] = $data['idrol'];
                    $_SESSION['rol_name'] = $data['rol'];

                    header('location: vista/index.php'); 
                }else{
                    $alert = "El usuario o clave son incorrectos"; 
                    session_destroy();
                }
            }
        }
    }    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable = no, initial-scale=1.0, maximux-scale=1.0, minimum-scale=1.0">
    <title>Login | Datasystem</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <!-- Login start -->
    <div class="login-box">
        <img src="img/logo.png" alt="logo de DS">
        <h1>Iniciar sesion</h1>
        <form action="" method="POST">
            <div class="input-box">
                <input type="text" name="usuario" required>
                <span>Usuario</span>
            </div>
            <div class="input-box">
                <input type="password" name="clave" required>
                <span>contraseña</span>
            </div>

            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : ''; 
                ?>
            </div>
            <input type="submit" class="btnlogin" value="Login">

            <a href="https://wa.me/59160584127?text=Hola en que puedo ayudarte" target="_blank">¿Tienes algun problema?</a>
        </form>
    </div>
    <!-- Login end-->
</body>
</html>