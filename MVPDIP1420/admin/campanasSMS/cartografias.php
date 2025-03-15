<?php
	include __DIR__."/../functions/security.php";
	if(!empty($_POST)){
		include __DIR__."/../functions/municipios.php";
		include __DIR__."/../functions/distritos_locales.php";
		include __DIR__."/../functions/distritos_federales.php";
		include __DIR__."/../functions/secciones_ine.php";
		$tipo_cartografia = $_POST['cartografia'][0]['tipo_cartografia'];

		if($tipo_cartografia=='municipios'){
			echo municipios();
		}elseif($tipo_cartografia=='distritos_locales'){
			echo distritos_locales();
		}elseif($tipo_cartografia=='distritos_federales'){
			echo distritos_federales();
		}elseif($tipo_cartografia=='secciones_ine'){
			echo secciones_ine();
		}else{
			echo '<option value="">Seleccione</option>';
		}

	}