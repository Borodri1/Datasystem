<?php
	if(empty($_SESSION['active'])){
		header('location: ../index.php');
	}
?>	
	
	
	<header>
		<div class="header">
			<a href="#" class="btn-menu"><i class="fas fa-bars"></i></a>
			<h1>Aplicacion web</h1>
			<div class="optionsBar">
				<p>Australia, <?php echo fechaC(); ?></p>
				<span>|</span>
				<span class="user"><?php echo $_SESSION['nombre'].' - '.$_SESSION['rol']; ?></span>
				<!--<img class="photouser" src="img/user.png" alt="Usuario">-->
				<a href="../controlador/salir.php"><img class="close" src="../modelo/sistema/img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
        <?php include "nav.php"?>
	</header>
	<div class="modal">
		<div class="bodyModal">
		</div>
	</div>