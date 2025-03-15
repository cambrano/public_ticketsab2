<?php
	//? Aqui obtenemos los valores totales para los recuadros
    $sql = "SELECT 
				t.votos_validos,
				(SELECT SUM(votos_nulos) FROM casillas_votos_2016 WHERE tipo = '{$tipo}') votos_nulos,
				(SELECT SUM(votos_can_nreg) FROM casillas_votos_2016 WHERE tipo = '{$tipo}') votos_can_nreg,
				(SELECT COUNT(id) FROM secciones_ine ) secciones,
				(SELECT COUNT(id) FROM casillas_votos_2016 WHERE tipo = '{$tipo}') casillas,
				(SELECT COUNT(id) FROM distritos_federales ) distritos_federales,
				(SELECT SUM(lista_nominal) FROM casillas_votos_2016 WHERE tipo = '{$tipo}') total_lista_nominal
			FROM
			(SELECT SUM(votos) votos_validos FROM  casillas_votos_partidos_2016 WHERE tipo = '{$tipo}')t";


	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$distritos_federales = $row['distritos_federales'];
	$votos_nulos = $row['votos_nulos'];
	$votos_can_nreg = $row['votos_can_nreg'];
	$votos_validos = $row['votos_validos'];
	$votos_totales = $votos_nulos + $votos_can_nreg + $votos_validos;

	$secciones = $row['secciones'];
	$casillas = $row['casillas'];
	$total_lista_nominal = $row['total_lista_nominal'];
	$participacion_ciudadana = ($votos_totales / $total_lista_nominal )*100;

	//? Aqui sacamos los partidos y los calsificamos por Individual y Coaliciones
    $sql="
        SELECT
            p.id,
            p.clave,
            p.nombre_corto,
            p.nombre,
            p.logo,
            p.color_border,
            p.color_background,
            SUM(cvp.votos) votos,
            p.clave_partidos_coaliciones,
            p.principal
        FROM partidos_2016 p
        LEFT JOIN casillas_votos_partidos_2016 cvp
        ON p.id = cvp.id_partido_2016
        WHERE cvp.tipo = '{$tipo}' 
        GROUP BY cvp.id_partido_2016 ";
    $result = $conexion->query($sql); 
    while($row=$result->fetch_assoc()){
        if($row['clave_partidos_coaliciones'] == ''){
            unset($row['clave_partidos_coaliciones']);
        }
        if($row['principal'] == ''){
            unset($row['principal']);
        }
        #$datos_partidos[$num]=$row;
        //? Colocamos en su arrelgo segun sea el tipo de partido
        if($row['clave_partidos_coaliciones'] != ''){
			$partidos_coaliciones[$row['clave_partidos_coaliciones']]=$row;
		}else{
			$partidos_sin_coaliciones[$row['clave']]=$row;
		}
        $num=$num+1;
    }

    //? Tomamos como princial el partido sin coalicion
    foreach ($partidos_sin_coaliciones as $clave => $array) {
        //? Colocamos en 0 la suma de coalciones para que no se sume con los demas
        //? El arreglo de coalciones lo vaciamos para que no se agregen de los demas partidos
        $sum_coaliciones = 0;
        unset($coaliciones); 
        unset($coalicion_orden_individual);
        foreach ($partidos_coaliciones as $nombre_corto => $arraysc) {
            //? Vemos si el nombre corto esta en la coalicion para agregarlo
            //? Si es negativo sigue con el siguiente
            $posicion_coalicion = explode(',', $nombre_corto);
            /*
            $pos = strpos($nombre_corto, $array['nombre_corto']);
            echo $nombre_corto;
            echo "-----";
            echo $array['nombre_corto'];
            echo "-----";
            echo var_dump($pos);
            echo "<br>";
            if ($pos !== false ) {
            */
            if (in_array($array['clave'], $posicion_coalicion)) {
                $coaliciones_array = explode(",", $nombre_corto);
                foreach ($coaliciones_array as $partido => $votos) {
                    $coaliciones[$votos] = $partidos_sin_coaliciones[$votos];
                    //! Importante
                    //? Buscamos si existe en el arrey para que no se repita
                    //* votos == nombre del partido segun la coalicion
                    //* [] == colocamos arreglo vacio por que puede ser que uno o mas partidos tengan los mismos votos
                    #$coalicion_orden_individual[$clave][$nombre_corto][ $partidos_sin_coaliciones[$votos]['votos'] ][]=$votos;
                    $search_coalicion = array_search($votos, $coalicion_orden_individual[$partidos_sin_coaliciones[$votos]['votos'] ]);
                    if($search_coalicion === NULL){
                        $coalicion_orden_individual[$partidos_sin_coaliciones[$votos]['votos'] ][]= $votos;
                    }
                }
                $sum_coaliciones = $sum_coaliciones + $arraysc['votos'];
            }
        }

        //? Nuestro Principal arreglo
        //* clave == nombre del partido
        $datos_partidos['partidos'][$clave]['id'] = $array['id'];
        $datos_partidos['partidos'][$clave]['clave'] = $clave;
        $datos_partidos['partidos'][$clave]['nombre_corto'] = $array['nombre_corto'];
        $datos_partidos['partidos'][$clave]['nombre'] = $array['nombre'];
        $datos_partidos['partidos'][$clave]['principal'] = $array['principal'];
        $datos_partidos['partidos'][$clave]['logo'] = $array['logo'];
        $datos_partidos['partidos'][$clave]['color_border'] = $array['color_border'];
        $datos_partidos['partidos'][$clave]['color_background'] = $array['color_background'];

        $datos_partidos['partidos'][$clave]['votos_individual'] = $array['votos'];
        $datos_partidos['partidos'][$clave]['coaliciones_sin_orden'] = $coaliciones;
        $datos_partidos['partidos'][$clave]['votos_coaliciones'] = $sum_coaliciones;

        //! Importante
        //? Ordenamos las coaliciones por votos en individual
        $total_votos_individual = 0;
        krsort($coalicion_orden_individual);
        foreach ($coalicion_orden_individual as $votos => $partidos_array) {
            foreach ($partidos_array as $index => $partido) {
                $datos_partidos['partidos'][$clave]['coaliciones_orden_votos_individual'][$partido]=$votos;
                if($clave != $partido){
                    $total_votos_individual = $total_votos_individual + $votos;
                }
            }
        }
        $datos_partidos['partidos'][$clave]['votos_coaliciones_individual'] = $total_votos_individual;
        $datos_partidos['partidos'][$clave]['votos_totales'] = $datos_partidos['partidos'][$clave]['votos_individual'] + $datos_partidos['partidos'][$clave]['votos_coaliciones'] + $datos_partidos['partidos'][$clave]['votos_coaliciones_individual'] ;


        $ordena_votos_individual[$array['votos']] [] = $clave ;
        $ordena_votos_totales[ $datos_partidos['partidos'][$clave]['votos_totales'] ] [] = $clave ;

        #$partidos_orden_individual[ $datos_partidos['partidos'][$clave]['votos_individual'] ][$array['votos']][] = $array['nombre_corto']; 
    }

    //! Importante
    //? Ordenamos los partidos
    krsort($ordena_votos_individual);
    krsort($ordena_votos_totales);

    foreach ($ordena_votos_individual as $votos => $partidos_array) {
        foreach ($partidos_array as $index => $partido) {
            $datos_partidos['orden_votos_individual']['partidos'][$partido]=$votos;
            if(empty($datos_partidos['orden_votos_individual']['primera_fuerza'])){
                $datos_partidos['orden_votos_individual']['primera_fuerza'] = $partido;
                if($datos_partidos['partidos'][$partido]['principal']==1 ){
                    $sistema = true;
                    $partido_sistema = $partido;
                }
            }elseif (empty($datos_partidos['orden_votos_individual']['segunda_fuerza'])  ) {
                $datos_partidos['orden_votos_individual']['segunda_fuerza'] = $partido;
                if($datos_partidos['partidos'][$partido]['principal']==1 ){
                    // colocamos la diferencia 
                    $partido_sistema = $partido;
                    $sistema = true;
                }
            }else{
                if($datos_partidos['partidos'][$partido]['principal'] == 1 && $sistema == false){
                    // colocamos la diferencia 
                    $partido_sistema = $partido;
                    $datos_partidos['orden_votos_individual']['sistema'] = $partido;
                }
            }
        }
    }

    $primera_fuerza = $datos_partidos['orden_votos_individual']['primera_fuerza'];
    $primera_fuerza_votos = $datos_partidos['orden_votos_individual']['partidos'][$primera_fuerza];
    $segunda_fuerza = $datos_partidos['orden_votos_individual']['segunda_fuerza'];
    $segunda_fuerza_votos = $datos_partidos['orden_votos_individual']['partidos'][$segunda_fuerza];

    $partido_sistema;
    $partido_sistema_votos = $datos_partidos['orden_votos_individual']['partidos'][$partido_sistema];

    $datos_partidos['orden_votos_individual']['diferencia_votos_fuerzas'] = $primera_fuerza_votos - $segunda_fuerza_votos ;
    if($primera_fuerza == $partido_sistema){
        $datos_partidos['orden_votos_individual']['diferencia_votos_sistema'] = $partido_sistema_votos - $segunda_fuerza_votos ;
    }elseif ($segunda_fuerza == $partido_sistema) {
        $datos_partidos['orden_votos_individual']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
    }else{
        $datos_partidos['orden_votos_individual']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
    }
    

    foreach ($ordena_votos_totales as $votos => $partidos_array) {
        foreach ($partidos_array as $index => $partido) {
            $datos_partidos['orden_votos_totales']['partidos'][$partido]=$votos;
            if(empty($datos_partidos['orden_votos_totales']['primera_fuerza'])){
                $datos_partidos['orden_votos_totales']['primera_fuerza'] = $partido;
                $primera_fuerza = $partido;
                if($datos_partidos['partidos'][$partido]['principal']==1 ){
                    $sistema = true;
                }
            }elseif (empty($datos_partidos['orden_votos_totales']['segunda_fuerza']) && empty($datos_partidos['partidos'][$partido]['coaliciones_orden_votos_individual'][$primera_fuerza]  )  ) {
                $datos_partidos['orden_votos_totales']['segunda_fuerza'] = $partido;
                if($datos_partidos['partidos'][$partido]['principal']==1 ){
                    $sistema = true;
                }
            }else{
                if($datos_partidos['partidos'][$partido]['principal'] == 1 && $sistema == false){
                    $datos_partidos['orden_votos_totales']['sistema'] = $partido;
                }
            }
        }
    }

    $primera_fuerza = $datos_partidos['orden_votos_totales']['primera_fuerza'];
    $primera_fuerza_votos = $datos_partidos['orden_votos_totales']['partidos'][$primera_fuerza];
    $segunda_fuerza = $datos_partidos['orden_votos_totales']['segunda_fuerza'];
    $segunda_fuerza_votos = $datos_partidos['orden_votos_totales']['partidos'][$segunda_fuerza];

    $partido_sistema;
    $partido_sistema_votos = $datos_partidos['orden_votos_totales']['partidos'][$partido_sistema];

    $datos_partidos['orden_votos_totales']['diferencia_votos_fuerzas'] = $primera_fuerza_votos - $segunda_fuerza_votos ;
    if($primera_fuerza == $partido_sistema){
        $datos_partidos['orden_votos_totales']['diferencia_votos_sistema'] = $partido_sistema_votos - $segunda_fuerza_votos ;
    }elseif ($segunda_fuerza == $partido_sistema) {
        $datos_partidos['orden_votos_totales']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
    }else{
        $datos_partidos['orden_votos_totales']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
    }
?>
<style type="text/css">
    .totales {
        display: table;
        float: left;
        width: 100%;
        font-family: 'Avenir Next';
        letter-spacing: 2px;
        font-weight: 10px;
        text-transform: uppercase;

    }
    .fontLabelReporteTable {
        padding: 1px;
        border-width: 1px;
        /*border-color: #ebccd1;*/
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 14px;
        font-family: 'Avenir Next';
        vertical-align: bottom;
    }
    .fontLabelReporte {
        padding: 1px;
        border-width: 1px;
        /*border-color: #ebccd1;*/
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 10px;
        font-family: 'Avenir Next';
    }
    .fontDataReporte {
        padding: 1px;
        font-weight: bold;
        border-width: 1px;
        /*border-color: #ebccd1;*/
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
        font-family: 'Avenir Next';
    }
    .div25Reporte {
        width: 25%;
        padding: 5px 25px 10px;
        float: left;
    }
    .div25Reportepartidos {
        width: 25%;
        padding: 5px 25px 10px;
        float: left;
    }
    .div30Reportepartidos {
        width: 33.29%;
        padding: 5px 25px 10px;
        float: left;
    }
    .div33Reporte {
        width: 33%;
        padding: 5px 25px 10px;
        float: left;
    }
    .div50Reporte {
        width: 50%;
        padding: 5px 25px 10px;
        float: left;
    }
    .div50ReporteSNF {
        width: 50%;
        padding: 5px 25px 10px;
    }
    .div100Reporte {
        width: 100%;
        padding: 5px 25px 10px;
        float: left;
    }
    .grafica_barras_horizontales {
        width: 100%;
        height: 128.5px;
        display: block;
        padding: 10px;
    }

    @media only screen and (max-width: 1200px) and (min-width: 980px) {
        /* For mobile phones: */
        .div25Reportepartidos {
            width: 25%;
            padding: 5px 5px 10px;
        }
        .div30Reportepartidos {
            width: 33.29%;
            padding: 5px 25px 10px;
            float: left;
        }
        .div25Reporte {
            width: 50%;
        }
        .div100Reporte,
        .div25Reporte,
        .div50Reporte,
        .div50ReporteSNF {
            padding: 10px;
        }
        .grafica_barras_horizontales {
            width: 100%;
            height: 88.5px;
            display: table;
        }
    }
    @media only screen and (max-width: 980px) and (min-width: 761px) {
        /* For mobile phones: */
        .div25Reportepartidos {
            width: 33%;
            padding: 5px 5px 10px;
        }
        .div30Reportepartidos {
            width: 33.29%;
            padding: 5px 25px 10px;
            float: left;
        }
        .div25Reporte {
            width: 50%;
        }
        .div100Reporte,
        .div25Reporte,
        .div50Reporte,
        .div50ReporteSNF {
            padding: 10px;
        }
        .grafica_barras_horizontales {
            width: 100%;
            height: 88.5px;
            display: table;
        }
    }

    @media only screen and (max-width: 760px) and (min-width: 600px) {
        /* For mobile phones: */
        .div25Reportepartidos {
            width: 50%;
            padding: 5px 5px 10px;
        }
        .div30Reportepartidos {
            width: 50%;
            padding: 5px 25px 10px;
            float: left;
        }
        .div25Reporte,
        .div50Reporte,
        .div50ReporteSNF,
        .totales {
            width: 100%;
        }
        .div100Reporte,
        .div25Reporte,
        .div50Reporte,
        .div50ReporteSNF {
            padding: 10px;
        }
        .grafica_barras_horizontales {
            width: 100%;
            height: 88.5px;
            display: table;
        }
    }
    @media only screen and (max-width: 620px) and (min-width: 6px) {
        /* For mobile phones: */
        .div25Reporte,
        .div25Reportepartidos,
        .div30Reportepartidos,
        .div50Reporte,
        .div50ReporteSNF,
        .totales {
            width: 100%;
        }
        .div100Reporte,
        .div25Reporte,
        .div50Reporte,
        .div50ReporteSNF {
            padding: 10px;
        }
        .grafica_barras_horizontales {
            width: 100%;
            height: 108.5px;
            display: block;
        }
    }
</style>
<div class="totales">
    <div
        style="width: 100%;display: table;padding: 5px 5px 5px 0px;background-color: white">
        <div style="background-color: white;padding: 5px;display: table;">
            <div class="div50Reporte">
                <table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td
                                style="text-align: center;padding: 10px;background-color: #191919;color: white"
                                colspan="2">Totales Votaciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Válidos:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($votos_validos, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Nulos:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($votos_nulos, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos CAN NREG:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($votos_can_nreg, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Totales:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($votos_totales, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Participación Ciudadana:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($participacion_ciudadana, 2, '.', ','); ?>%
                                </font>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="div50Reporte">
                <table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td
                                style="text-align: center;padding: 10px;background-color: #191919;color: white"
                                colspan="2">Cartografía</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Distritos Federales:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($distritos_federales, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Lista Nominal:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($total_lista_nominal, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Secciones:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($secciones, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Casillas:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($casillas, 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr style="width: 80%;border-top: 1px solid #333333;">
        <div style="background-color: white;padding: 5px;display: table;">
        <!--- fuerzas ----->
        <?php
        if(!empty($datos_partidos['orden_votos_individual']['sistema'])){
            $sistema = $datos_partidos['orden_votos_individual']['sistema'];
            $value = $datos_partidos['partidos'][$sistema];
        ?>
            <div style=" text-align: center;">
                <center>
                    <div class="div50ReporteSNF">
                        <table
                            style="table-layout: fixed; width: 100%"
                            cellspacing="0"
                            cellpadding="0"
                            border="1">
                            <thead>
                                <tr>
                                    <tr>
                                        <td
                                            style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white;font-weight: bold;"
                                            colspan="2">Sistema</td>
                                    </tr>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
                                        <img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 40%">
                                    </td>
                                    <td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
                                        <font class="fontDataReporte" style="font-size: 12px">
                                            <?= $value['nombre_corto'] ?>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                        <font class="fontLabelReporte">Votos individual:</font>
                                    </td>
                                    <td
                                        style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                        <font class="fontDataReporte" style="font-size: 12px">
                                            <?=number_format($value['votos_individual'], 0, '.', ','); ?>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px">
                                        <font class="fontLabelReporte">Coaliciones:</font>
                                        <?php
                                            if(!empty($value['coaliciones_orden_votos_individual'])){
                                                unset($value['coaliciones_orden_votos_individual'][$value['nombre_corto']]);
                                                ?>
                                                <table style="width: 100%;text-align: left;font-size: 10px;table-layout: fixed;">
                                                    <tr>
                                                        <td colspan="2" style="border:1px solid;padding: 2px;background-color: #dee3ed">Partido</td>
                                                        <td style="border:1px solid;padding: 2px;background-color: #dee3ed">Votos</td>
                                                        <td style="border:1px solid;padding: 2px;background-color: #dee3ed">Diff.</td>
                                                    </tr>
                                                    <?php
                                                    foreach ($value['coaliciones_orden_votos_individual'] as $partido => $votos) {
                                                        echo "<tr>";
                                                        echo "<td style='border:1px solid;padding: 2px 2px 2px 5px;text-align:center'><img src='images/logos_partidos/".$datos_partidos['partidos'][$partido]['logo']."'  style='width: 45%' ></td>";
                                                        $a= "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$datos_partidos[$valueL]['logo']."</td>";
                                                        echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$datos_partidos['partidos'][$partido]['nombre_corto']."</td>";
                                                        echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($votos, 0, '.', ',')."</td>";
                                                        echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($value['votos_individual']-$votos, 0, '.', ',')."</td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </table>
                                                <?php
                                            }else{
                                                echo ' <font class="fontDataReporte" style="font-size: 12px">';
                                                echo "No tiene.";
                                                echo ' </font>';
                                            }
                                            #echo "<pre>";
                                            #var_dump($value);
                                            #echo "</pre>";
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                        <font class="fontLabelReporte">Votos Coalición:</font>
                                    </td>
                                    <td
                                        style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                        <font class="fontDataReporte" style="font-size: 12px">
                                            <?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                        <font class="fontLabelReporte">Votos:</font>
                                    </td>
                                    <td
                                        style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                        <font class="fontDataReporte" style="font-size: 12px">
                                            <?=number_format($value['votos_totales'], 0, '.', ','); ?>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                        <font class="fontLabelReporte">Diferencia:</font>
                                    </td>
                                    <td
                                        style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                        <font class="fontDataReporte" style="font-size: 12px">
                                            <?=number_format($datos_partidos['orden_votos_individual']['diferencia_votos_sistema'], 0, '.', ','); ?>
                                        </font>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </center>
            </div>
        <?php
        }
        foreach ($datos_partidos['orden_votos_individual'] as $key => $value) {
            if ($key !='sistema' && $key !='partidos' && $key !='diferencia_votos_fuerzas' && $key !='diferencia_votos_sistema'){
                $value =$datos_partidos['partidos'][ $datos_partidos['orden_votos_individual'][$key]];
                ?>
            <div class="div50Reporte">
                <table
                    style="table-layout: fixed; width: 100%"
                    cellspacing="0"
                    cellpadding="0"
                    border="1">
                    <thead>
                        <tr>
                            <tr>
                                <td
                                    style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white;font-weight: bold;"
                                    colspan="2"><?= strtr($key, "_", " "); ?></td>
                            </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
                                <img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 40%">
                            </td>
                            <td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?= $value['nombre_corto'] ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos individual:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_individual'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px">
                                <font class="fontLabelReporte">Coaliciones:</font>
                                <?php
                                    if(!empty($value['coaliciones_orden_votos_individual'])){
                                        unset($value['coaliciones_orden_votos_individual'][$value['nombre_corto']]);
                                        ?>
                                        <table style="width: 100%;text-align: left;font-size: 10px;table-layout: fixed;">
                                            <tr>
                                                <td colspan="2" style="border:1px solid;padding: 2px;background-color: #dee3ed">Partido</td>
                                                <td style="border:1px solid;padding: 2px;background-color: #dee3ed">Votos</td>
                                                <td style="border:1px solid;padding: 2px;background-color: #dee3ed">Diff.</td>
                                            </tr>
                                            <?php
                                            foreach ($value['coaliciones_orden_votos_individual'] as $partido => $votos) {
                                                echo "<tr>";
                                                echo "<td style='border:1px solid;padding: 2px 2px 2px 5px;text-align:center'><img src='images/logos_partidos/".$datos_partidos['partidos'][$partido]['logo']."'  style='width: 45%' ></td>";
                                                $a= "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$datos_partidos[$valueL]['logo']."</td>";
                                                echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$datos_partidos['partidos'][$partido]['nombre_corto']."</td>";
                                                echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($votos, 0, '.', ',')."</td>";
                                                echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($value['votos_individual']-$votos, 0, '.', ',')."</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </table>
                                        <?php
                                    }else{
                                        echo ' <font class="fontDataReporte" style="font-size: 12px">';
                                        echo "No tiene.";
                                        echo ' </font>';
                                    }
                                    #echo "<pre>";
                                    #var_dump($value);
                                    #echo "</pre>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Coalición Ind:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_coaliciones_individual'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Coalición Boleta:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Totales:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_totales'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                                    <td
                                        style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                        <font class="fontLabelReporte">Diferencia:</font>
                                    </td>
                                    <td
                                        style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                        <font class="fontDataReporte" style="font-size: 12px">
                                            <?=number_format($datos_partidos['orden_votos_individual']['diferencia_votos_fuerzas'], 0, '.', ','); ?>
                                        </font>
                                    </td>
                                </tr>
                    </tbody>
                </table>
            </div>
                <?php
                }
            }
        ?>
        </div>
        <hr style="width: 80%;border-top: 1px solid #333333;">
        <?php


        foreach ($datos_partidos['orden_votos_individual']['partidos'] as $key => $valueT) {
            $value = $datos_partidos['partidos'][$key];
            $nombre_corto = str_replace("_"," - ",$value['nombre_corto']);
            //$total = $partido_votos_porcentaje + $total;
            ?>

            <div class="div30Reportepartidos" style="padding: 10px">
                <table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td colspan="3" style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white; height: 60px">
                                <?=  str_replace("_"," - ",$value['nombre_corto']) ?>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" style="text-align: center;padding: 5px 5px 5px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 60px">
                            </td>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Individual:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_individual'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Coalición Ind:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_coaliciones_individual'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                colspan="2"
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Coalición Boleta:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td
                                colspan="2"
                                style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
                                <font class="fontLabelReporte">Votos Totales:</font>
                            </td>
                            <td
                                style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
                                <font class="fontDataReporte" style="font-size: 12px">
                                    <?=number_format($value['votos_totales'], 0, '.', ','); ?>
                                </font>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php
        }
        //echo $total;
        ?>
        </div>
    </div>