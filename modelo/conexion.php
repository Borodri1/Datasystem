<?php

    $host = 'localhost';
    $user = 'root';
    $password = '';
    $db = 'datasystem3';
    
    $conection = @mysqli_connect($host,$user,$password,$db);

    if(!$conection){
        echo "Error en la conexion";
    }
?>