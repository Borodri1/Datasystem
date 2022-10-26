<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';

    include "../conexion.php";
    
    $codCliente= $_POST['cl'];
    $noFactura = $_POST['f'];

    $query = mysqli_query($conection,"SELECT f.nofactura, DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s') as  hora, f.codcliente, f.estatus,
											v.nombre as vendedor,
											cl.nit, cl.nombre, cl.telefono,cl.direccion, cl.suburbio, cl.codigo_postal
											FROM factura f
											INNER JOIN usuario v
											ON f.usuario = v.idusuario
											INNER JOIN cliente cl
											ON f.codcliente = cl.idcliente
											WHERE f.nofactura = $noFactura AND f.codcliente = $codCliente  AND f.estatus != 10 ");

    $result = mysqli_num_rows($query);
    if($result > 0){
        $factura = mysqli_fetch_assoc($query);
    }

    $nof = $factura['nofactura'];
    $fech = $factura['fecha'];
    $hor = $factura['hora'];
    $ven =  $factura['vendedor'];
    $nit = $factura['nit'];
    $tel = $factura['telefono'];
    $nom = $factura['nombre'];
    $dir = $factura['direccion'];
    $sub = $factura['suburbio'];
    $codP = $factura['codigo_postal'];

    $subtotal 	= 0;
	$iva 	 	= 0;
	$impuesto 	= 0;
	$tl_sniva   = 0;
	$total 		= 0;
    $body3 = '';

    $query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
    $result_config  = mysqli_num_rows($query_config);
    if($result_config > 0){
        $configuracion = mysqli_fetch_assoc($query_config);
        $iva = $configuracion['iva'];
    }

    $query_productos = mysqli_query($conection,"SELECT p.descripcion,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM factura f
														INNER JOIN detallefactura dt
														ON f.nofactura = dt.nofactura
														INNER JOIN producto p
														ON dt.codproducto = p.codproducto
														WHERE f.nofactura = $noFactura");
	$result_detalle = mysqli_num_rows($query_productos);

    if($result_detalle > 0){
        while ($row = mysqli_fetch_assoc($query_productos)){
            $cant = $row['cantidad'];
            $descr = $row['descripcion'];
            $precve = $row['precio_venta'];
            $preto = $row['precio_total'];

            $body2 = $cant . " --------|| " . $descr . " ------|| " . $precve . " $ AUD------|| " . $preto . " $ AUD<br>"; 
            $body3 = $body3 . $body2;
            $body1 = $body3; 

            $precio_total = $row['precio_total'];
			$subtotal = round($subtotal + $precio_total, 2);
        }
    }

    $impuesto 	= round($subtotal * ($iva / 100), 2);
    $tl_sniva 	= round($subtotal - $impuesto,2 );
    $total 		= round($tl_sniva + $impuesto,2);


    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    if(isset($_POST['enviar_fact'])){

        $body = "No. factura: " . $nof . "<br>Fecha: " . $fech . "<br>Hora: " . $hor . "<br>Vendedor: " . $ven . "<br><br><br>Nit: " . $nit . "<br>Telefono: " . $tel . "<br>Nombre: " . $nom . "<br>Direccion: " . $dir . "<br>Suburbio " . $sub . "<br>Codigo postal: " . $codP . "<br><br><br>Cantidad || Descripcion || Precio unitario || Precio total<br><br>" . $body1 . "<br>Subtotal: " . $tl_sniva . " $ AUD<br>impuesto: " . $impuesto . " $ AUD<br>Total: " . $total . " $ AUD"; 


        try {
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'rcuevascing@gmail.com';                     // SMTP username
        $mail->Password   = 'qbcgqpjsaquvmjpl';                               // SMTP password
        $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('rcuevascing@gmail.com');
        $mail->addAddress('brcuevasco123@gmail.com');     // Add a recipient
        //$mail->addAddress($user_email);

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'envío';
        $mail->Body    = $body;
        $mail->CharSet = 'UTF-8';

        $mail->send();
        echo '<script>
                alert("Mensaje enviado correctamente");
                window.location.assign("../../vista/ventas.php"); 
            </script>';

        } catch (Exception $e) {
            echo 'Hubo un error al enviar el mensaje: ', $mail->ErrorInfo;
        }
    }
?>