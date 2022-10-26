<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: ../index.php");
    }
    include "conexion.php";
    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $idusuario = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = md5($_POST['clave']);
            $rol = $_POST['rol']; 

            //verificamos que no se repita ni usuario ni correo
            $query = mysqli_query($conection, "SELECT * FROM usuario WHERE (usuario = '$user' AND idusuario != $idusuario) OR (correo = '$email' AND idusuario != $idusuario)");

            $result = mysqli_fetch_array($query);

            if($result > 0){
                $alert = '<p class="msg_error">El correo o el usuario ya exite</p>';
            }else{

                if(empty($_POST['clave'])){
                    $sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo = '$email', usuario = '$user', rol = '$rol' WHERE idusuario = $idusuario");
                }else{
                    $sql_update = mysqli_query($conection, "UPDATE usuario SET nombre = '$nombre', correo = '$email', usuario = '$user', clave = '$clave', rol = '$rol' WHERE idusuario = $idusuario");
                }
                
                //cuando se ejecute esta instruccion nos devuelve verdadero o falso por lo cual validamos esto
                if($sql_update){
                    $alert = '<p class="msg_save">Usuario actualizado correctamente</p>';
                }else{
                    $alert = '<p class="msg_error">Error al actualizar usuario</p>';
                }
            }

        }
    }

    //Mostrar datos para actualizar
    if(empty($_REQUEST['id'])){
        header('location: lista_usuarios.php');
        mysqli_close($conection);
    }
    $iduser = $_REQUEST['id'];
    $sql = mysqli_query($conection, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE idusuario = $iduser and estatus = 1"); //no es necesario comillas por que es entero y no cadena

    mysqli_close($conection);
    $result_sql = mysqli_num_rows($sql); //devuelve el numero de filas
    if($result_sql == 0){
        header('location: ../vista/lista_usuarios.php');
    }else{
        $option = '';
        while($data = mysqli_fetch_array($sql)){
            $iduser = $data['idusuario'];
            $nombre = $data['nombre'];
            $correo = $data['correo'];
            $usuario = $data['usuario'];
            $idrol = $data['idrol'];
            $rol = $data['rol'];

            if($idrol == 1){
                $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
            }else if($idrol == 2){
                $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
            }else if($idrol == 3){
                $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Editar usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1><i class="fas fa-edit"></i> Editar usuario</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST">
                <div class="user-edit">
                    <input type="hidden" name="id" value="<?php echo $iduser; ?>">
                    <input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" required>
                    <span>Nombre completo</span>
                </div>

                <div class="user-edit">
                    <input type="email" name="correo" id="correo" value="<?php echo $correo; ?>" required>
                    <span>Correo electronico</span>
                </div>

                <div class="user-edit">
                    <input type="text" name="usuario" id="usuario" value="<?php echo $usuario; ?>" required>
                    <span>Usuario</span>
                </div>

                <div class="user-edit"> 
                    <input type="passwprd" name="clave" id="clave" required>
                    <span>Contraseña</span>
                </div>

                <div class="user-edit">

                    <?php
                        include "conexion.php";
                        //para que se extraiga el valor del rol de la base de datos
                        $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                        mysqli_close($conection);
                        $result_rol = mysqli_num_rows($query_rol);
                    ?>
                    <select name="rol" id="rol" class="notitemone">
                        <?php
                            echo $option;
                            if($result_rol > 0){
                                while($rol = mysqli_fetch_array($query_rol)){
                        ?>
                                <option value="<?php echo $rol["idrol"]?>"><?php echo $rol["rol"]?></option>
                        <?php
                                }
                            }
                        ?>

                    </select>
                    <span>Tipo de Usuario</span>
                </div>

                <!--input type="submit" value="Actualizar ususario" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar usuario</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</html>