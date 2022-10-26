<?php
	session_start();	
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable = no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<?php include "includes/scripts.php"; ?>
	<script src = "../modelo/sistema/js/plotly-2.12.1.min.js"></script>
	<title>Aplicacion web</title>
</head>
<body>
	<?php 
	
		include "includes/header.php"; 
		include "../modelo/conexion.php";

		//datos empresa
		$nit = '';
		$nombreEmpresa = '';
		$razonSocial = '';
		$telEmpresa = '';
		$emailEmpresa = '';
		$dirEmpresa = '';
		$iva = '';

		$query_empresa = mysqli_query($conection,"SELECT * FROM configuracion");
		$row_empresa = mysqli_num_rows($query_empresa);
		if($row_empresa > 0){
			while ($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa)){
				$nit = $arrInfoEmpresa['nit'];
				$nombreEmpresa = $arrInfoEmpresa['nombre'];
				$razonSocial = $arrInfoEmpresa['razon_social'];
				$telEmpresa = $arrInfoEmpresa['telefono'];
				$emailEmpresa = $arrInfoEmpresa['email'];
				$dirEmpresa = $arrInfoEmpresa['direccion'];
				$iva = $arrInfoEmpresa['iva'];
			}
		}

		$query_dash = mysqli_query($conection,"CALL dataDashboard();");
		$result_dash = mysqli_num_rows($query_dash);
		if($result_dash > 0){
			$data_dash = mysqli_fetch_assoc($query_dash);
			//mysqli_close($conection);
		}
	?>

	<section id="container">
		<div class="divContainer">
			<div>
				<h1 class="titlePanelControl">Panel de control</h1>
			</div>
			<div class="dashboard">
				<?php
					if($_SESSION['rol'] == 1){
				?>
				<a href="lista_usuarios.php">
					<i class="fas fa-users"></i>
					<p>
						<strong>Usuarios</strong><br>
						<span><?= $data_dash['usuarios']; ?></span>
					</p>
				</a>
				<?php } ?>
				<?php
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
				?>
				<a href="lista_clientes.php">
					<i class="fas fa-user"></i>
					<p>
						<strong>Clientes</strong><br>
						<span><?= $data_dash['clientes']; ?></span>
					</p>
				</a>
				<?php } ?>
				<?php
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3){
				?>
				<a href="lista_proveedores.php">
					<i class="fas fa-building"></i>
					<p>
						<strong>Proveedores</strong><br>
						<span><?= $data_dash['proveedores']; ?></span>
					</p>
				</a>
				<?php } ?>
				<a href="lista_producto.php">
					<i class="fas fa-cubes"></i>
					<p>
						<strong>Productos</strong><br>
						<span><?= $data_dash['productos']; ?></span>
					</p>
				</a>
				<?php
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
				?>
				<a href="ventas.php">
					<i class="fas fa-file-alt"></i>
					<p>
						<strong>Ventas</strong><br>
						<span><?= $data_dash['ventas']; ?></span>
					</p>
				</a>
				<?php } ?>
			</div>
		</div>
		

		<!--   Para las graficas  -->
		<div class="grafi-container">
			<div>
				<h1 class="titlePanelControl">Graficas de ventas</h1>
			</div>
			<div class="graficos">
				<div class="lineal">
					<div id="cargaLineal">
					</div>
				</div>
				<div class="barra">
					<div id="cargaBarras">
					</div>
				</div>
			</div>
		</div>

		<div class="divInfoSistema">
			<div>
				<h1 class="titlePanelControl">Configuracion</h1>
			</div>
			<div class="containerPerfil">
				<div class="containerDataUser">
					<div class="logoUser">
						<img src="../modelo/sistema/img/user.png" alt="usuario">
					</div>
					<div class="divDataUser">
						<h4>informacion personal</h4>
						<div>
							<label>Nombre:</label> <span><?= $_SESSION['nombre'];?></span>
						</div>
						<div>
							<label>Correo:</label> <span><?= $_SESSION['email'];?></span>
						</div>
						<h4>Datos de usuario</h4>
						<div>
							<label>Rol:</label> <span><?= $_SESSION['rol_name'];?></span>
						</div>
						<div>
							<label>Usuario:</label> <span><?= $_SESSION['user'];?></span>
						</div>
						<h4>Cambiar contraseña</h4>
						<form action="" method="POST" name="frmChangePass" id="frmChangePass">
							<div class="user-box">
								<input type="password" name="txtPassUser" id="txtPassUser" required>
								<span>Contraseña actual</span>
							</div>
							<div class="user-box">
								<input class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" required>
								<span>Nueva contraseña</span>
							</div>
							<div class="user-box">
								<input class="newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" required>
								<span>Confirmar contraseña</span>
							</div>
							<div class="alertChangePass" style="display: none;">
							</div>
							<div>
								<button type="submit" class="btn_save btnChangePass"><i class="fas fa-key"></i>Cambiar contraseña</button>
							</div>
						</form>
					</div>
				</div>
				<?php if($_SESSION['rol'] == 1){?>
				<div class="containerDataEmpresa">
					<div class="logoEmpresa">
						<img src="../modelo/sistema/img/empresa.png" alt="empresa">
					</div>
					<h4>Datos de la empresa</h4>

					<form action="" method="POST" name="frmEmpresa" id="frmEmpresa">
						<input type="hidden" name="action" value="updateDataEmpresa">
						<div class="empresa-box">
							<input type="text" name="txtNit" id="txtNit" value="<?= $nit;?>" required>
							<span>Nit de la empresa</span>
						</div>
						<div class="empresa-box">
							<input type="text" name="txtNombre" id="txtNombre" value="<?= $nombreEmpresa;?>" required>
							<span>Nombre de la empresa</span>
						</div>
						<div class="empresa-box">
							<input type="text" name="txtRSocial" id="txtRSocial" value="<?= $razonSocial;?>">
							<span>Razon social</span>
						</div>
						<div class="empresa-box">
							<input type="text" name="txtTelEmpresa" id="txtTelEmpresa" value="<?= $telEmpresa;?>" required>
							<span>Numero de telefono</span>
						</div>
						<div class="empresa-box">
							<input type="email" name="txtEmailEmpresa" id="txtEmailEmpresa" value="<?= $emailEmpresa;?>" required>
							<span>Correo electronico</span>
						</div>
						<div class="empresa-box">
							<input type="text" name="txtDirEmpresa" id="txtDirEmpresa" value="<?= $dirEmpresa;?>" required>
							<span>Direccion</span>
						</div>
						<div class="empresa-box">
							<input type="text" name="txtIva" id="txtIva" value="<?= $iva;?>" required>
							<span>IVA %</span>
						</div>
						<div class="alertFormEmpresa" style="display: none;"></div>
						<div>
							<button type="submit" class="btn_save btnChangePass"><i class="far fa-save fa-lg"></i>Guardar datos</button>
						</div>
					</form>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<?php include "includes/footer.php"; ?>
	<script>
		$(document).ready(function(){
			$('#cargaLineal').load('../controlador/lineal.php');
			$('#cargaBarras').load('../controlador/barras.php');
		});
	</script>
</body>
</html>