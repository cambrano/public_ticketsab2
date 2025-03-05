<?php
	function openstreetmap($latitud = null,$longitud = null){
		if($latitud != ""){
			$ch = curl_init();
			$url = "https://nominatim.openstreetmap.org/reverse";
			$dataArray = 
				[
					'lat' => $latitud,
					'lon' => $longitud,
					'format' => 'jsonv2',
				];
			$data = http_build_query($dataArray);
			$getUrl = $url."?".$data;
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
			curl_setopt($ch, CURLOPT_URL, $getUrl);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($ch);
			$obj = json_decode($result,true);
			$logCombinacion['direccion_completa'] = $obj['display_name'];
			$logCombinacion['direccion_numero'] = $obj['address']['house_number'];
			$logCombinacion['direccion_calle'] = $obj['address']['road'];
			$logCombinacion['direccion_colonia'] = $obj['address']['quarter'];
			$logCombinacion['city'] = $obj['address']['city'];
			$logCombinacion['region'] = $obj['address']['state'];
			$logCombinacion['zip_code'] = $obj['address']['postcode'];
			$logCombinacion['country'] = strtoupper($obj['address']['country_code']);
			curl_close($ch);
		}else{
			$logCombinacion = false;
		}
		return $logCombinacion;
	}


	function mapquestapi($latitud = null,$longitud = null){
		if($latitud != ""){
			$json = file_get_contents("https://www.mapquestapi.com/geocoding/v1/reverse?key=Ngg8eq3WbrviuEolXYgj2g95Vvp3ZJgS&location={$latitud},{$longitud}");
			$jsondata = json_decode($json, true);
			$info = $jsondata['results'][0]['locations'][0];

			$data['direccion_completa'] = $jsondata['results'][0]['locations'][0]['street'].','.$jsondata['results'][0]['locations'][0]['adminArea6'].','.$jsondata['results'][0]['locations'][0]['adminArea5'].','.$jsondata['results'][0]['locations'][0]['adminArea4'].','.$jsondata['results'][0]['locations'][0]['adminArea3'].','.$jsondata['results'][0]['locations'][0]['postalCode'].','.$jsondata['results'][0]['locations'][0]['adminArea1'];
			$data['direccion_calle'] = $jsondata['results'][0]['locations'][0]['street'];
			$data['direccion_colonia'] = $jsondata['results'][0]['locations'][0]['adminArea6'];
			$data['city'] = $jsondata['results'][0]['locations'][0]['adminArea5'];
			$data['region'] = $jsondata['results'][0]['locations'][0]['adminArea4'];
			$data['zip_code'] = $jsondata['results'][0]['locations'][0]['postalCode'];
			$data['country'] = $jsondata['results'][0]['locations'][0]['adminArea1'];
		}else{
			$data = false;
		}
		return $data;
	}

	function api_opencagedata($latitud = null,$longitud = null){
		if($latitud != ""){
			$json = file_get_contents("https://api.opencagedata.com/geocode/v1/json?key=a0b69575324f4ef1b16f8d7bdddd01ee&q={$latitud},{$longitud}&pretty=1");
			$jsondata = json_decode($json, true);
			$info = $jsondata['results'][0]['formatted'];
			$array_index = array('road','residential','suburb','city','municipality','state','postcode','country');
			foreach ($array_index as $key => $value) {
				if(!empty($jsondata['results'][0]['components'][$value])){
					$direccion_completa[] = $jsondata['results'][0]['components'][$value];
				}
			}
			$data['direccion_completa'] = implode(", ", $direccion_completa);
			$data['direccion_calle'] = $jsondata['results'][0]['components']['road'];
			$data['direccion_colonia'] = $jsondata['results'][0]['components']['residential'];
			$data['city'] = $jsondata['results'][0]['components']['city'];
			$data['region'] = $jsondata['results'][0]['components']['state'];
			$data['zip_code'] = $jsondata['results'][0]['components']['postcode'];
			$data['country'] = $jsondata['results'][0]['components']['country'];
		}else{
			$data = false;
		}
		return $data;
	}
	function bingmapsportal($latitud = null,$longitud = null){
		if($latitud != ""){
			 $json = file_get_contents("https://dev.virtualearth.net/REST/v1/Locations/{$latitud},{$longitud}?o=json&key=Ard0nstLpyCI2zx4FoYHE6TOe2kPSjz2UVyorKTmoJqKuFikkYSOi3DweLXodU-J");
			$jsondata = json_decode($json, true);
			$info = $jsondata['results'][0]['formatted'];
			$array_index = array('road','residential','suburb','city','municipality','state','postcode','country');
			foreach ($array_index as $key => $value) {
				if(!empty($jsondata['results'][0]['components'][$value])){
					$direccion_completa[] = $jsondata['results'][0]['components'][$value];
				}
			}
			$data['direccion_completa'] = $jsondata['resourceSets'][0]['resources'][0]['address']['formattedAddress'];
			$data['direccion_calle'] = $jsondata['resourceSets'][0]['resources'][0]['address']['addressLine'];
			$data['city'] = $jsondata['resourceSets'][0]['resources'][0]['address']['locality'];
			$data['region'] = $jsondata['resourceSets'][0]['resources'][0]['address']['adminDistrict'];
			$data['zip_code'] = $jsondata['resourceSets'][0]['resources'][0]['address']['postalCode'];
			$data['country'] = $jsondata['resourceSets'][0]['resources'][0]['address']['countryRegion'];
		}else{
			$data = false;
		}
		return $data;
	}