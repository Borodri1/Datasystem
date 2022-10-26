<?php
    include "../modelo/conexion.php";
    //graficas
    $query_graf = mysqli_query($conection, "SELECT fecha, totalfactura FROM factura WHERE estatus = 1");
    $valoresY=array();
    $valoresX=array();

    while ($ver=mysqli_fetch_row($query_graf)){
        $valoresY[]=$ver[1];
        $valoresX[]=$ver[0];
    }

    $datosX=json_encode($valoresX);
    $datosY=json_encode($valoresY);
    mysqli_close($conection);
?>


<div id="graficaBarras"></div>

<script>
    function crearCadenaBarras(json){
        var parsed = JSON.parse(json);
        var arr = [];
        for(var x in parsed){
            arr.push(parsed[x]);
        }
        return arr;
    }
</script>

<script>

    datosX=crearCadenaBarras('<?php echo $datosX; ?>');
    datosY=crearCadenaBarras('<?php echo $datosY; ?>');
    
    var data = [
    {
        x: datosX,
        y: datosY,
        type: 'bar',
    }
    ];
    var layout = {
        title: 'Grafica de ventas barras',
        xaxis: {
            title: 'Fechas de venta'
        },
        yaxis: {
            title: 'Monto de la venta'
        }
    };

    Plotly.newPlot('graficaBarras', data, layout);
</script>