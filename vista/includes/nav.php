<nav>
			<ul>
				<li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
				<?php
					if($_SESSION['rol'] == 1){
				?>
				<li class="principal">
					<a href="#"><i class="fas fa-users"></i> Usuarios</a>
					<ul>
						<li><a href="registro_usuarios.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fas fa-users"></i> Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
				?>
				<li class="principal">
					<a href="#"><i class="fas fa-user-tie"></i> Clientes</a>
					<ul>
						<li><a href="registro_clientes.php"><i class="fas fa-user-plus"></i> Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php"><i class="fas fa-users"></i> Lista de Clientes</a></li>
					</ul>
				</li>
				<?php } ?>
				<?php
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3){
				?>
				<li class="principal">
					<a href="#"><i class="fas fa-warehouse"></i> Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fas fa-clinic-medical"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedores.php"><i class="fas fa-clipboard-list"></i> Lista de Proveedores</a></li>
					</ul>
				</li>
				<?php } ?>
				<li class="principal">
					<a href="#"><i class="fas fa-box-open"></i> Productos</a>
					<ul>
						<li><a href="registro_producto.php"><i class="fas fa-truck-loading"></i> Nuevo Producto</a></li>
						<li><a href="lista_producto.php"><i class="fas fa-boxes"></i> Lista de Productos</a></li>
					</ul>
				</li>
				<?php 
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
				?>
				<li class="principal">
					<a href="#"><i class="fas fa-file-invoice-dollar"></i> Ventas</a>
					<ul>
						<li><a href="nueva_venta.php"><i class="fas fa-bible"></i> Nueva venta</a></li>
						<li><a href="ventas.php"><i class="fas fa-file-invoice"></i> Ventas</a></li>
					</ul>
				</li>
				<?php } ?>
			</ul>
		</nav>