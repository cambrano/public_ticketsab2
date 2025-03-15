<?php
/*
Descripción: Cálculo de la distancia entre 2 puntos en función de su latitud/longitud
Autor: Michaël Niessen (2014)
Sito web: AssemblySys.com
 
Si este código le es útil, puede mostrar su
agradecimiento a Michaël ofreciéndole un café ;)
PayPal: https://www.paypal.me/MichaelNiessen
 
Mientras estos comentarios (incluyendo nombre y detalles del autor) estén
incluidos y SIN ALTERAR, este código se puede usar y distribuir libremente.
*/
 
function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'm', $decimals = 2) {
	// Cálculo de la distancia en grados
	$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
 
	// Conversión de la distancia en grados a la unidad escogida (kilómetros, millas o millas naúticas)
	switch($unit) {
		case 'km':
			$distance = $degrees * 111.13384; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
			break;
		case 'm':
			//$distance = $degrees * 111133.84; // 1 grado = 111133.84 m, basándose en el diametro promedio de la Tierra (12.735 km)
			$distance = $degrees * 111133.84; // 1 grado = 111133.84 m, basándose en el diametro promedio de la Tierra (12.735 km)
			break;
		case 'mi':
			$distance = $degrees * 69.05482; // 1 grado = 69.05482 millas, basándose en el diametro promedio de la Tierra (7.913,1 millas)
			break;
		case 'nmi':
			$distance =  $degrees * 59.97662; // 1 grado = 59.97662 millas naúticas, basándose en el diametro promedio de la Tierra (6,876.3 millas naúticas)
	}
	$distancia = number_format($distance, $decimals, '.', ',');
	return $distancia;
}


//$point1 = array("lat" => "20.96680108", "long" => '-89.56740792'); // París (Francia)
//$point2 = array("lat" => "20.96676319", "long" => '-89.56740088'); // Ciudad de México (México)
 
//$m = distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']); // Calcular la distancia en kilómetros (por defecto) 
//echo "La distancia es de $m m ";
?>