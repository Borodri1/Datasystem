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


<div id="graficaLineal"></div>

<script>
    function crearCadenaLineal(json){
        var parsed = JSON.parse(json);
        var arr = [];
        for(var x in parsed){
            arr.push(parsed[x]);
        }
        return arr;
    }
</script>
<script>

    datosX=crearCadenaLineal('<?php echo $datosX; ?>');
    datosY=crearCadenaLineal('<?php echo $datosY; ?>');

    var trace1 = {
        x: datosX,
        y: datosY,
        type: 'scatter', 
        line: {
            color: '#0089cb',
            width: 2.5
        },
        marker: {
            color: '#0089cb',
            size: 12
        }
    };

    var layout = {
        title: 'Grafica de ventas lineal',
        xaxis: {
            title: 'Fechas de venta'
        },
        yaxis: {
            title: 'Monto de la venta'
        }
    };

    var data = [trace1];

    Plotly.newPlot('graficaLineal', data, layout);
</script>