<?php
if($_GET['cot']!="sol88"){
    echo ":(";
    die;
}
date_default_timezone_set('America/Cancun');//!cambio de zona horaria
setlocale(LC_ALL,"es_ES");

include "../MVPDIP1420/admin/functions/db.php";



$_tipo_nivel['1'] = 'Distrital';
$_tipo_nivel['2'] = 'Zonal';
$_tipo_nivel['3'] = 'Seccional';
$_tipo_nivel['4'] = 'Activista';
$_tipo_nivel['5'] = 'Promovido';


$estructuras['3'] = array(1,2,3,4,5);
$estructuras['8'] = array(1,3,4,5);
$estructuras['12'] = array(1,2,3,5);
$estructuras['23'] = array(1,3,5);
$estructuras['6'] = array(1,3,4,5);
$estructuras['1'] = array(1,2,3,5);
$estructuras['11'] = array(1,3,5);
$estructuras['4'] = array(1,3,5);
$estructuras['14'] = array(1,3,5);
$estructuras['17'] = array(1,3,5);
$estructuras['9'] = array(1,2,3,4,5);
$estructuras['20'] = array(1,3,5);
$estructuras['10'] = array(1,3,5);
$estructuras['7'] = array(1,2,3,4,5);
$estructuras['33'] = array(1,2,3,4,5);
$estructuras['2'] = array(1,2,3,4,5);
$estructuras['5'] = array(1,2,3,4,5);
$estructuras['334'] = array(1,2,4,5);

$estructuras_metas['1'][1] = 1;
$estructuras_metas['1'][2] = 6;
$estructuras_metas['1'][3] = 60;
$estructuras_metas['1'][5] = 600;

$estructuras_metas['2'][1] = 1;
$estructuras_metas['2'][2] = 3;
$estructuras_metas['2'][3] = 10;
$estructuras_metas['2'][4] = 100;
$estructuras_metas['2'][5] = 1000;

$estructuras_metas['3'][1] = 1;
$estructuras_metas['3'][2] = 1;
$estructuras_metas['3'][3] = 15;
$estructuras_metas['3'][4] = 150;
$estructuras_metas['3'][5] = 1500;

$estructuras_metas['4'][1] = 1;
$estructuras_metas['4'][3] = 25;
$estructuras_metas['4'][5] = 500;

$estructuras_metas['5'][1] = 1;
$estructuras_metas['5'][2] = 1;
$estructuras_metas['5'][3] = 15;
$estructuras_metas['5'][4] = 285;
$estructuras_metas['5'][5] = 2850;

$estructuras_metas['6'][1] = 1;
$estructuras_metas['6'][3] = 5;
$estructuras_metas['6'][4] = 50;
$estructuras_metas['6'][5] = 500;

$estructuras_metas['7'][1] = 1;
$estructuras_metas['7'][2] = 2;
$estructuras_metas['7'][3] = 30;
$estructuras_metas['7'][4] = 300;
$estructuras_metas['7'][5] = 3000;

$estructuras_metas['8'][1] = 1;
$estructuras_metas['8'][3] = 10;
$estructuras_metas['8'][4] = 60;
$estructuras_metas['8'][5] = 600;

$estructuras_metas['9'][1] = 1;
$estructuras_metas['9'][2] = 1;
$estructuras_metas['9'][3] = 16;
$estructuras_metas['9'][4] = 67;
$estructuras_metas['9'][5] = 670;

$estructuras_metas['10'][1] = 1;
$estructuras_metas['10'][3] = 92;
$estructuras_metas['10'][5] = 920;

$estructuras_metas['11'][1] = 1;
$estructuras_metas['11'][3] = 25;
$estructuras_metas['11'][5] = 500;

$estructuras_metas['12'][1] = 1;
$estructuras_metas['12'][2] = 1;
$estructuras_metas['12'][3] = 49;
$estructuras_metas['12'][5] = 490;

$estructuras_metas['14'][1] = 1;
$estructuras_metas['14'][3] = 30;
$estructuras_metas['14'][5] = 300;

$estructuras_metas['17'][1] = 1;
$estructuras_metas['17'][3] = 20;
$estructuras_metas['17'][5] = 210;

$estructuras_metas['20'][1] = 1;
$estructuras_metas['20'][3] = 40;
$estructuras_metas['20'][5] = 400;

$estructuras_metas['23'][1] = 1;
$estructuras_metas['23'][3] = 18;
$estructuras_metas['23'][5] = 180;

$estructuras_metas['33'][1] = 1;
$estructuras_metas['33'][2] = 21;
$estructuras_metas['33'][3] = 21;
$estructuras_metas['33'][4] = 210;
$estructuras_metas['33'][5] = 2100;

$estructuras_metas['334'][1] = 1;
$estructuras_metas['334'][2] = 1;
$estructuras_metas['334'][4] = 50;
$estructuras_metas['334'][5] = 500;

// Obtener las keys del array
$keys = array_keys($estructuras_metas);
// Convertir las keys en una cadena separada por comas
$keys_string = implode(',', $keys);


$sql = "SELECT 
	sic.id,
	sic.nombre_completo,
	sic.clave_elector,
	(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
	(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
	sic.id_tipo_ciudadano
	/*FROM secciones_ine_ciudadanos sic WHERE id_tipo_ciudadano = 1 AND id IN ({$keys_string}) ;*/
	FROM secciones_ine_ciudadanos sic WHERE id_tipo_ciudadano = 1 AND id = 7
	";
$resultado = $conexion->query($sql);
//var_dump($resultado->num_rows);
while($row=$resultado->fetch_assoc()){
	$id_tipo_ciudadano = $row['id_tipo_ciudadano'];
	$id = $row['id'];
	$nivel1[$id] = $row['nombre_completo']."-".$row['id'];
	$ids[$row['id_tipo_ciudadano']][$row['id_seccion_ine_ciudadano_compartido']]=$row['id_seccion_ine_ciudadano_compartido'];
	//$totales[$id][$id_tipo_ciudadano] = $totales[$id][$id_tipo_ciudadano] + $resultado->num_rows;
	//nivel2
	$sql2 = "SELECT 
		sic.id,
		sic.nombre_completo,
		sic.clave_elector,
		(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
		(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
		sic.id_tipo_ciudadano,
		sic.id_seccion_ine_ciudadano_compartido
		FROM secciones_ine_ciudadanos sic WHERE id_seccion_ine_ciudadano_compartido = $id;";
	$resultado2 = $conexion->query($sql2);
	while($row2=$resultado2->fetch_assoc()){
		$id_tipo_ciudadano = $row2['id_tipo_ciudadano'];
		$id2 = $row2['id'];
		$totales[$id][$id_tipo_ciudadano] = $totales[$id][$id_tipo_ciudadano] + 1;
		$ids[$row2['id_tipo_ciudadano']][$row2['id_seccion_ine_ciudadano_compartido']]=$row2['id_seccion_ine_ciudadano_compartido'];

		//nivel3
		$sql3 = "SELECT 
			sic.id,
			sic.nombre_completo,
			sic.clave_elector,
			(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
			(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
			sic.id_tipo_ciudadano,
			sic.id_seccion_ine_ciudadano_compartido
			FROM secciones_ine_ciudadanos sic WHERE id_seccion_ine_ciudadano_compartido = $id2;";
		$resultado3 = $conexion->query($sql3);
		while($row3=$resultado3->fetch_assoc()){
			$id_tipo_ciudadano = $row3['id_tipo_ciudadano'];
			$id3 = $row3['id'];
			$totales[$id][$id_tipo_ciudadano] = $totales[$id][$id_tipo_ciudadano] + 1;
			$ids[$row3['id_tipo_ciudadano']][$row3['id_seccion_ine_ciudadano_compartido']]=$row3['id_seccion_ine_ciudadano_compartido'];
			//nivel4
			$sql4 = "SELECT 
				sic.id,
				sic.nombre_completo,
				sic.clave_elector,
				(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
				(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
				sic.id_tipo_ciudadano,
				sic.id_seccion_ine_ciudadano_compartido
				FROM secciones_ine_ciudadanos sic WHERE id_seccion_ine_ciudadano_compartido = $id3;";
			$resultado4 = $conexion->query($sql4);
			while($row4=$resultado4->fetch_assoc()){
				$id_tipo_ciudadano = $row4['id_tipo_ciudadano'];
				$id4 = $row4['id'];
				$totales[$id][$id_tipo_ciudadano] = $totales[$id][$id_tipo_ciudadano] + 1;
				$ids[$row4['id_tipo_ciudadano']][$row4['id_seccion_ine_ciudadano_compartido']]=$row4['id_seccion_ine_ciudadano_compartido'];
				//nivel5
				$sql5 = "SELECT 
					sic.id,
					sic.nombre_completo,
					sic.clave_elector,
					(SELECT LPAD(s.numero,4,0) FROM secciones_ine s WHERE s.id = sic.id_seccion_ine) seccion,
					(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo_ciudadano,
					sic.id_tipo_ciudadano,
					sic.id_seccion_ine_ciudadano_compartido
					FROM secciones_ine_ciudadanos sic WHERE id_seccion_ine_ciudadano_compartido = $id4;";
				$resultado5 = $conexion->query($sql5);
				while($row5=$resultado5->fetch_assoc()){
					$id_tipo_ciudadano = $row5['id_tipo_ciudadano'];
					$id5 = $row5['id'];
					$totales[$id][$id_tipo_ciudadano] = $totales[$id][$id_tipo_ciudadano] + 1;
					$ids[$row5['id_tipo_ciudadano']][$row5['id_seccion_ine_ciudadano_compartido']]=$row5['id_seccion_ine_ciudadano_compartido'];
				}
			}
		}
	}

}
foreach ($ids as $key => $value) {
	if($key==5){
		foreach ($value as $keyT => $valueT) {
			echo $valueT;
			echo ",<br>";
		}
	}
}
die;
?>
<style>
    td{
        padding: 10px;
    }
    .totales_h{
        text-align:center; 

    }
	.totales{
        text-align:center;
		color: #721c24;
		background-color: #f8d7da;
		border-color: #f5c6cb;

    }
	.totales_p{
        text-align:center;
		color: #856404;
		background-color: #fff3cd;
		border-color: #ffeeba;

    }
	.totales1{
        text-align:center;
		color: #155724;
		background-color: #d4edda;
		border-color: #c3e6cb;
    }
</style>
<?php
echo "<table border=1>";
echo "<tr>";
echo "<td rowspan='2'>No.</td>";
echo "<td rowspan='2'>Distrital</td>";
foreach ($_tipo_nivel as $keyT => $tipo) {
	echo "<td colspan='3' class='totales_h'>".$tipo."</td>";
}
echo "<td colspan='3' class='totales_h'>Totales</td>";
echo "</tr>";
echo "<tr>";
foreach ($_tipo_nivel as $keyT => $tipo) {
	echo "<td class='totales_h'>Sistema</td>";
	echo "<td class='totales_h'>Meta</td>";
	echo "<td class='totales_h'>Avance</td>";
}
echo "<td class='totales_h'>Sistema</td>";
echo "<td class='totales_h'>Meta</td>";
echo "<td class='totales_h'>Avance</td>";
echo "</td>";
$num = 1;
$totales_estructura;
foreach ($nivel1 as $key => $value) {
	echo "<tr>";
	echo "<td>".$num."</td>";
	echo "<td>".$value."</td>";
	$totales_sistema = 0;
	$totales_meta = 0;
	$totales_avance = 0;
	foreach ($_tipo_nivel as $keyT => $tipo) {
		if($keyT == 1){
			echo "<td class='totales'>1</td>";
			echo "<td class='totales1'>1</td>";
			echo "<td class='totales1'>100%</td>";
			$totales_sistema = $totales_sistema + 1;
			$totales_avance = $totales_avance + 1;
			$totales_estructura_reg[$keyT] = $totales_estructura_reg[$keyT] + 1;
			$totales_estructura_metas[$keyT] = $totales_estructura_metas[$keyT] + 1;
		}else{
			
			if(!empty($estructuras_metas[$key][$keyT]) && empty($totales[$key][$keyT])){
				$totales[$key][$keyT] = 0;
			}
			if($estructuras_metas[$key][$keyT]!=""){
				echo "<td class='totales'>".number_format($totales[$key][$keyT],0,'.','')."</td>";
				echo "<td class='totales1'>".number_format($estructuras_metas[$key][$keyT],0,'',',')."</td>";
			}else{
				echo "<td class='totales'></td>";
				echo "<td class='totales1'></td>";
			}
			$totales_estructura_reg[$keyT] = $totales_estructura_reg[$keyT] + $totales[$key][$keyT];
			$totales_estructura_metas[$keyT] = $totales_estructura_metas[$keyT] + $estructuras_metas[$key][$keyT];

			$totales_sistema = $totales[$key][$keyT] + $totales_sistema;
			$totales_meta = $estructuras_metas[$key][$keyT] + $totales_meta;
			if($estructuras_metas[$key][$keyT]==""){
				echo "<td class='totales_p'></td>";
			}else{
				$avance = $totales[$key][$keyT] / $estructuras_metas[$key][$keyT] * 100;
				echo "<td class='totales_p'>".number_format($avance,2,'.','')."%</td>";
			}
		}
	}

	echo "<td class='totales'>".number_format($totales_sistema,0,'',',') ."</td>";
	echo "<td class='totales1'>".number_format($totales_meta,0,'',',') ."</td>";
	$totales_avance = $totales_sistema / $totales_meta * 100;
	echo "<td class='totales_p'>".number_format($totales_avance,2,'.','')."%</td>";
	echo "</tr>";
	$num ++;
}
echo "<tr>";
echo "<td colspan='2'>Totales</td>";
foreach ($_tipo_nivel as $keyT => $tipo) {
	echo "<td class='totales_h'>". number_format($totales_estructura_reg[$keyT],0,'.',',')."</td>";
	echo "<td class='totales_h'>". number_format($totales_estructura_metas[$keyT],0,'.',',')."</td>";
	$avance_total = $totales_estructura_reg[$keyT] / $totales_estructura_metas[$keyT] * 100;
	if($keyT==1){
		echo "<td class='totales1'>".number_format($avance_total,2,'.','') ."%</td>";
	}else{
		echo "<td class='totales_p'>".number_format($avance_total,2,'.','') ."%</td>";
	}

	$totales_totales_sistema = $totales_estructura_reg[$keyT] + $totales_totales_sistema;
	$totales_totales_metas = $totales_estructura_metas[$keyT] + $totales_totales_metas;

}
$avance_total = 0;
echo "<td class='totales_h'>".number_format($totales_totales_sistema,0,'',',') ."</td>";
echo "<td class='totales_h'>".number_format($totales_totales_metas,0,'',',') ."</td>";
$avance_total = $totales_totales_sistema / $totales_totales_metas* 100;
echo "<td class='totales_p'>".number_format($avance_total,2,'.','') ."%</td>";
echo "<tr>";
echo "</table>";






















