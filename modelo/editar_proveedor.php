<?php
    session_start();
    if($_SESSION['rol'] !=1 && $_SESSION['rol'] != 3){
        header("location: ../index.php"); 
    }

    include "conexion.php";
    if(!empty($_POST)){
        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['suburbio']) || empty($_POST['codpostal'])){
            $alert = '<p class="msg_error">Todos los campos son obligatorios</p>';
        }else{

            /* con este include ya podemos utilizar $conection y las variables de conexion.php */
            $idproveedor = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $suburbio = $_POST['suburbio']; 
            $codPostal = $_POST['codpostal'];

            $sql_update = mysqli_query($conection, "UPDATE proveedor SET proveedor = '$proveedor', contacto = '$contacto', telefono = '$telefono', direccion = '$direccion', suburbio = '$suburbio', codigo_postal = '$codPostal' WHERE codproveedor = $idproveedor");
                
            //cuando se ejecute esta instruccion nos devuelve verdadero o falso por lo cual validamos esto
            if($sql_update){
                $alert = '<p class="msg_save">Proveedor actualizado correctamente.</p>';
            }else{
                $alert = '<p class="msg_error">Error al actualizar proveedor.</p>';
            }
        }
    }

    //Mostrar datos para actualizar
    if(empty($_REQUEST['id'])){
        header('location: ../vista/lista_proveedores.php');
        mysqli_close($conection);
    }
    $idproveedor = $_REQUEST['id'];
    $sql = mysqli_query($conection, "SELECT * FROM proveedor WHERE codproveedor = $idproveedor and estatus = 1"); //no es necesario comillas por que es entero y no cadena

    mysqli_close($conection);
    $result_sql = mysqli_num_rows($sql); //devuelve el numero de filas
    if($result_sql == 0){
        header('location: ../vista/lista_proveedores.php');
    }else{
        while($data = mysqli_fetch_array($sql)){
            $idproveedor  = $data['codproveedor'];
            $proveedor = $data['proveedor'];
            $contacto = $data['contacto'];
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
	<title>Editar proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <img src="sistema/img/empresa.png" alt="" class="img_form">
		<div class="form_register">
            <h1><i class="fas fa-edit"></i> Editar proveedor</h1>
            <hr>
            <div class="alert">
                <?php 
                    echo isset($alert) ? $alert : '';
                ?>
            </div>
            <form action="" method="POST">
                <div class="prov-edit">
                    <input type="hidden" name="id" value="<?php echo $idproveedor; ?>">

                    <input type="text" name="proveedor" id="proveedor" value="<?php echo $proveedor; ?>" required> 
                    <span>Nombre del proveedor</span>
                </div>

                <div class="prov-edit">
                    <input type="text" name="contacto" id="contacto" value="<?php echo $contacto; ?>" required>
                    <span>Nomber del contacto</span>
                </div>

                <div class="prov-edit">
                    <input type="number" name="telefono" id="telefono" value="<?php echo $telefono; ?>" required>
                    <span>Telefono</span>
                </div>

                <div class="prov-edit">
                    <input type="text" name="direccion" id="direccion" value="<?php echo $direccion; ?>" required>
                    <span>Direccion</span>
                </div>
                <div class="prov-edit">
                    <input type="text" name="suburbio" id="suburbio" value="<?php echo $suburbio; ?>" required>
                    <span>Suburbio</span>
                </div>
                <div class="prov-edit">
                    <input type="number" name="codpostal" id="codpostal" value="<?php echo $codPostal; ?>" class="prov-dir" required>
                    <span>Codigo postal</span>
                </div>

                <!--<input type="submit" value="Guardar cliente" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="fas fa-edit"></i> Actualizar proveedor</button>
            </form>
        </div>
        <div class="foot">
            <p>International new market ptt ltd</p>
            <p>sales@intnewmarket.com | 61-2-80056848</p>
        </div>
	</section> 

	<?php include "includes/footer.php"; ?>
</html>