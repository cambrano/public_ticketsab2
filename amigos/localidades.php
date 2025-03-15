<?php
if(!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    include "comp.php";
    include "functions.php";
    if($_POST['tipo'] =='select' ){
        $id_municipio = $_POST['id_municipio'];
        $localidades = localidades($conexion, $id_municipio, $id_estado,'');
        echo $localidades;
    }
}