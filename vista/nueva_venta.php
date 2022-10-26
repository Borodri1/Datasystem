<?php
    session_start();
    include "../modelo/conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva venta</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="title_page">
            <h1><i class="fas fa-cube"></i> Nueva venta</h1>
        </div>
        <div class="datos_cliente">
            <div class="action_cliente">
                <h4>Datos del cliente</h4>
                <a href="" class="btn_new btn_new_cliente"><i class="fas fa-plus"></i> Nuevo cliente</a>
            </div>
            <form action="" name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
                <input type="hidden" name="action" value="addCliente">
                <input type="hidden" id="idcliente" name="idcliente" value="" required>
                <div class="wd100">
                    <label id="label_input_bq">Buscar por ID</label>
                    <input type="text" name="busq_id" id="busq_id" class="input_bq">
                </div>
                <div class="wd30">
                    <label>Nit</label>
                    <input type="text" name="nit_cliente" id="nit_cliente">
                </div>
                <div class="wd30">
                    <label>Nombre</label>
                    <input type="text" name="nom_cliente" id="nom_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Telefono</label>
                    <input type="number" name="tel_cliente" id="tel_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Direccion</label>
                    <input type="text" name="dir_cliente" id="dir_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Suburbio</label>
                    <input type="text" name="sub_cliente" id="sub_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Codigo postal</label>
                    <input type="number" name="codp_cliente" id="codp_cliente" disabled required>
                </div>
                <div class="wd100" id="div_registro_cliente">
                    <button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
        <div class="datos_venta">
            <h4>Datos venta</h4>
            <div class="datos">
                <div class="wd50">
                    <label>Vendedor</label>
                    <p><?php echo $_SESSION['nombre'];?></p>
                </div>
                <div class="wd50">
                    <label for="">Acciones</label>
                    <div class="acciones_venta">
                        <a href="#" class="btn_accept textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
                        <a href="#" class="btn_new textcenter" id="btn_facturar_venta" style="display: none;"><i class="far fa-edit"></i> Procesar</a>
                </div>
                </div>
            </div>
        </div>
    <div class="containerTable">
        <table class="tbl_venta">
            <thead>
                <tr>
                    <th width="100px">Codigo</th>
                    <th>Description</th>
                    <th>Existencia</th>
                    <th width="100px">Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Precio total</th>
                    <th> Accion</th>
                </tr>
                <tr>
                    <td><input type="text" name="txt_cod_producto" id="txt_cod_producto"></td>
                    <td id="txt_descripcion">-</td>
                    <td id="txt_existencia">-</td>
                    <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                    <td id="txt_precio" class="textright">0.00</td>
                    <td id="txt_precio_total" class="textright">0.00</td>
                    <td><a href="#" id="add_product_venta" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
                </tr>
                <tr>
                    <th>Codigo</th>
                    <th colspan="2">Descripcion</th>
                    <th>Cantidad</th>
                    <th class="textright">Precio</th>
                    <th class="textright">Preico total</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody id="detalle_venta">
                <!-- contenido ajax -->
            </tbody>
            <tfoot id="detalle_totales">
                <!-- contenido ajax-->
            </tfoot>
        </table>
    </div>
    </section>

    <?php include "includes/footer.php"; ?>

    <script type="text/javascript">
        $(document).ready(function(){
            var usuarioid = '<?php echo $_SESSION['idUser'];?>';
            searchForDetalle(usuarioid);
        });
    </script>
</body>
</html>