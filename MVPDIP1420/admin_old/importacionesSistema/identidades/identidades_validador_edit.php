<?php
	if($_FILES['file']["type"]!="text/csv"){
		echo "<br><div class='mensajeError'>Archivo Incorrecto debe ser un .CSV </div>";
		die;
	}

	@session_start();
	include "../../functions/security.php";
	include "../../functions/paises.php";
	include "../../functions/estados.php";
	include "../../functions/municipios.php";
	include "../../functions/localidades.php";
	include "../../functions/claves.php";
	include "../../functions/usuarios_identidades.php";
	unset($_SESSION['data_import']);
	unset($_SESSION['data_import_file']);
	$tipo=$_POST['tipo_operacion'];
	$tabla=$_POST['tabla_operacion'];
	$tipo_vista=$_POST['tipo_vista'];
	if($tipo_vista==0){
		$tipo_vista="true";
	}else{
		$tipo_vista="false";
	}
	$api_maps="AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI";
?>
<meta charset="utf-8">
<style type="text/css">
	table {
		border-collapse: collapse;
		width: 100%;
	}

	th, td {
		text-align: left;
		padding: 8px;
	}

	tr:nth-child(even) {background-color: #f2f2f2;}
</style>
<script type="text/javascript">
	function guardarImportacion(){
		document.getElementById("sumbmitImport").disabled = true;
		document.getElementById("mensaje").classList.remove("mensajeSucces");
		document.getElementById("mensaje").classList.remove("mensajeError");
		$("#mensaje").html("&nbsp");
		document.getElementById('importacionArea').style.display = "none";
		var tipo_operacion = document.getElementById("tipo_operacion").value; 
		if(tipo_operacion == ""){
			document.getElementById("tipo_operacion").focus(); 
			document.getElementById("sumbmitImport").disabled = false;
			$("#mensaje").html("Tipo Operación requerido");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		} 
		var tabla_operacion = document.getElementById("tabla_operacion").value; 
		if(tabla_operacion == ""){
			document.getElementById("tabla_operacion").focus(); 
			document.getElementById("sumbmitImport").disabled = false;
			$("#mensaje").html("Tipo Información requerido");
			document.getElementById("mensaje").classList.add("mensajeError");
			return false;
		}
		var importacion = []; 
		var data = {  
				'tipo_operacion' : tipo_operacion,
				'tabla_operacion' : tabla_operacion,
			}
		importacion.push(data);
		var formData = new FormData($("#form")[0]);
		formData.append('tipo_operacion', tipo_operacion);
		formData.append('tabla_operacion', tabla_operacion);
		var ruta = "importacionesSistema/identidades/identidades_db_edit.php";
		document.getElementById('loadSistema').style.display = "inline-block";
		 $.ajax({
			url: ruta,
			type: "POST",
			data: formData, 
			contentType: false,
			processData: false,
			success: function(data){
				document.getElementById('loadSistema').style.display = "none";
				if(data=="SI"){ 
					$("#importacionArea").html("&nbsp;");
					$("#mensaje").html("&nbsp;");
					document.getElementById("mensaje").classList.remove("mensajeError");
					$("#mensaje").html("Guardado con éxito");  
					document.getElementById("mensaje").classList.add("mensajeSucces");
					//$("#homebody").load('hoteles/index.php');
				}else{
					document.getElementById('importacionArea').style.display = "block";
					document.getElementById("sumbmitImport").disabled = false;
					document.getElementById("mensaje").classList.add("mensajeError");
					$("#mensaje").html(data);
				}
			}
		});
	}

	$(document).ready(function() {
		var dataTable = $('#importacion-tabla').DataTable( {
			"responsive": <?= $tipo_vista ?>,
			"ordering": false,
			"pageLength": 11,
			"retrieve": true,
			"info": false,
			"processing": true,
			"searching": false,
			"paging": false,
			"sPaginationType": "full_numbers",
			"order": [[ 0, "desc" ]],
			"fixedHeader": true,
			"fixedHeader": {
				header: true,
			},
			"aoColumnDefs": [
							{ "bSortable": false, "aTargets": [ 1 ] }
							],
			"serverSide": false,
			"scrollY": "100%", 
			"scrollX": "100%",

			"language": {
				"sProcessing":     "Procesando...",
				//"sLengthMenu":     "Mostrar _MENU_ registros",
				"sLengthMenu": ' ',
				"sSearch":         "Buscar:",
				"sZeroRecords":    "Registro no encontrados",
				"sEmptyTable":     "No Existe Registros",
				"sInfo":           "Mostrar  (_START_ a _END_) de _TOTAL_ Registros",//
				"sInfoEmpty":      "Mostrando Registros del 0 al 0 de Total de 0 Registros",//
				"sInfoFiltered":   "(Filtrado de _MAX_ Total Registros)",//
				//"sInfoPostFix":    "",
				//"sUrl":            "",
				//"sInfoThousands":  ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst":    "<<",
					"sLast":     ">>",
					"sNext":     ">",
					"sPrevious": "<"
				},
				"oAria": {
					"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				},
			},
		});
	});
</script>
<?php

	function validarFecha($fecha=null){
		$fecha=trim($fecha);
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fecha)) {
			return true;
		} else {
			return false;
		}
	}
	function validarTelefono($telefono=null){
		
		if(is_numeric($telefono) && strlen($telefono)==10){
			return true;
		}else{
			return false;
		}
	}

	function validarCorreoElectronico($correo_electronico=null){
		if (filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
			return true;
		}else{
			return false;
		}
	}

	function tipoValor($tipo_valor=null){
		if(strpos($tipo_valor, 'clave')!==false){
			return "uppercase";
		}
		if(strpos($tipo_valor, 'rfc')!==false){
			return "uppercase";
		}
	}

	if($tabla=="identidades"){
		$columnData = array(
				'clave'=>array('alfanumerico','requerido','unique','unique_db','mayuscula'),
				'tipo' =>array('alfanumerico',''),
				'sexo' =>array('alfanumerico',''),
				'fecha_nacimiento'=>array('fecha',''),
				'nombre' =>array('alfanumerico',''),
				'apellido_paterno' =>array('alfanumerico',''),
				'apellido_materno' =>array('alfanumerico',''),
				'clave_elector' =>array('alfanumerico',''),
				'curp' =>array('alfanumerico',''),
				'rfc' =>array('alfanumerico',''),
				'pais'=>array('alfanumerico',''),
				'estado'=>array('alfanumerico',''),
				'municipio'=>array('alfanumerico',''),
				'localidad'=>array('alfanumerico',''),
				'calle'=>array('alfanumerico',''),
				'colonia'=>array('alfanumerico',''),
				'codigo_postal'=>array('numerico',''),
				'longitud'=>array('alfanumerico','opcional_1'),
				'latitud'=>array('alfanumerico','opcional_2'),
			);
	}

	$csvFile_chk = fopen($_FILES['file']['tmp_name'], 'r');
	fgetcsv($csvFile_chk);
	$numero_registros = 0;
	while(($line = fgetcsv($csvFile_chk)) !== FALSE){
			$countdraw = $countdraw +1;
			if($countdraw > 1){
				$numero_registros = $numero_registros + 1;
			}
		}

	$limite = 2;
	if($numero_registros > $limite){
		echo "<br><div class='mensajeError'>Error , No puede realizar la operación por que el limite de registros debe ser {$limite} por disposicón de Google LLC en sus plataformas y se esta subiendo {$numero_registros} registros. </div>";
		die;
	}


	$countdraw = 0;
	$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
	fgetcsv($csvFile);
	$gps_mode=true;
	if($tipo==2){
		$num=0;
		while(($line = fgetcsv($csvFile)) !== FALSE){
			$countdraw = $countdraw +1;
			if($countdraw > 1){
				$numx=0;
				$gps_requerido=false;
				//sacamos las columnas y verificmos si cumple
				foreach ($columnData as $key => $value) {
					//esto es el valor del csv
					$line_valor=trim($line[$numx]);
					//esto es el nombre de la dato
					$key;
					//este es el tipo de dato
					$value;
					//validaciones
					if(in_array("fecha", $value)){
						if(!validarFecha($line_valor)){
							if(in_array("requerido", $value) && empty($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$tipo_error[]=str_replace('_',' ',$key);
							}
							if(!empty($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$tipo_error[]=str_replace('_',' ',$key);
							}
						}
					}
					if(in_array("telefono", $value)){
						if(!validarTelefono($line_valor)){
							if(in_array("requerido", $value) && empty($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$tipo_error[]=str_replace('_',' ',$key);
							}
							if(!empty($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$tipo_error[]=str_replace('_',' ',$key);
							}
						}
					}
					if(in_array("correo_electronico", $value)){
						if(!validarCorreoElectronico($line_valor)){
							if(in_array("requerido", $value) && empty($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$tipo_error[]=str_replace('_',' ',$key);
							}
							if(!empty($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$tipo_error[]=str_replace('_',' ',$key);
							}
						}
					}
					if(in_array("unique_db_usuario", $value)){
						usuario_identidadValidadorSistema($line_valor,2,$id);
						if(usuario_identidadValidadorSistema($line_valor,2,$id)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$unique_db_error[]=$line_valor;
						}
					}
					
					if(in_array("unique", $value)){
						//var_dump($unique[$key]);
						if(empty($unique[$key])){
							$unique[$key][]=$line_valor;
						}else{
							if(in_array($line_valor, $unique[$key])){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$unique_error[]=str_replace('_',' ',$clave_base.' - '.$line_valor);
							}
							$unique[$key][]=$line_valor;
						}
					}
					if(in_array("requerido", $value)){
						if(empty($line_valor)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.$key);
						}
					}
					if(in_array("mayuscula", $value)){
						$line_valor=strtoupper($line_valor);
					}

					if($key=='clave'){
						$id=claveId($line_valor,$tabla);
						$clave_base=$line_valor;
						if(!claveValidadorSistema($line_valor,$tabla)){
							//$id=claveId($line_valor,$tabla);
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$encontrado_error[]=$line_valor;
						}
					}

					if(in_array("opcional_1", $value)){
						if($line_valor!=""){
							$requerido=1;
						}else{
							$requerido=0;
							$num_requerido=$num;
							$key_requerido=$key;
						}
					}

					if(in_array("opcional_2", $value)){
						if($line_valor=="" && $requerido==1){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.$key);
						}
						if($line_valor=="" && $requerido==0){
							//no hace nada
							$gps=true;
						}
						if($line_valor!="" && $requerido==0){
							$color[$num_requerido][$key_requerido]='background-color: #d9534f;color:white';
							$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.$key);
							$gps=false;
						}
						if($line_valor!="" && $requerido==1){
							$gps=false;
						}
					}
					if($key=='pais'){
						$paisId=paisId($line_valor);
						if(empty($paisId)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$encontrado_error[]=str_replace('_',' ',$clave_base.' - '.'pais');
						}
					}

					if($key=='estado'){
						if($line_valor=="México" || $line_valor=="méxico" ){
							$estadoId=estadoId($line_valor,1);
							if(empty($estadoId)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$encontrado_error[]="estado";
							}
						}else{
							$estadoId=estadoId($line_valor,"");
							if(empty($estadoId)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$encontrado_error[]="estado";
							}
						}
						
					}

					if($key=='municipio'){
						$municipioId=municipioId($line_valor,$estadoId);
						if(empty($municipioId)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$encontrado_error[]=str_replace('_',' ',$clave_base.' - '.'municipio');
						}
					}

					if($key=='localidad'){
						$localidadId=localidadId($line_valor,$estadoId,$municipioId);
						if(empty($localidadId)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$encontrado_error[]=str_replace('_',' ',$clave_base.' - '.'localidad');
						}
					}

					if($key=='tipo'){
						$line_valor=strtolower($line_valor);
					}
					if($key=='sexo'){
						$line_valor= ucfirst(strtolower($line_valor));
					}

					//metemos el valor para el data
					$data[$num][$key]=$line_valor;
					//$data[$num][$key]=$line_valor;
					$numx=$numx+1;
				}
				//var_dump($gps);
				if(
					$data[$num]['codigo_postal']=="" &&
					$data[$num]['pais']=="" &&
					$data[$num]['estado']=="" &&
					$data[$num]['municipio']=="" &&
					$data[$num]['localidad']=="" &&
					$data[$num]['calle']=="" &&
					$data[$num]['colonia']==""
				){
					$gps_mode=false;
				}else{
					if($data[$num]['pais']==""){
						$color[$num]['pais']='background-color: #d9534f;color:white';
						$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.'pais');
					}
					if($data[$num]['estado']==""){
						$color[$num]['estado']='background-color: #d9534f;color:white';
						$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.'estado');
					}
					if($data[$num]['municipio']==""){
						$color[$num]['municipio']='background-color: #d9534f;color:white';
						$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.'municipio');
					}
					if($data[$num]['localidad']==""){
						$color[$num]['localidad']='background-color: #d9534f;color:white';
						$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.'localidad');
					}
					if($data[$num]['calle']==""){
						$color[$num]['calle']='background-color: #d9534f;color:white';
						$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.'calle');
					}
					if($data[$num]['colonia']==""){
						$color[$num]['colonia']='background-color: #d9534f;color:white';
						$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.'colonia');
					}
					if($data[$num]['codigo_postal']==""){
						$color[$num]['codigo_postal']='background-color: #d9534f;color:white';
						$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.'colonia');
					}
				}
				if(!$gps){
					$gps_mode=false;
				}
				if($gps_mode && empty($error)){
					$dataString=$data[$num]['codigo_postal']."+".$data[$num]['pais']."+".$data[$num]['municipio']."+".$data[$num]['localidad']."+".$data[$num]['calle']."+".$data[$num]['colonia'];
					$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($dataString)."&key=".$api_maps;
					$json = file_get_contents($url);
					$obj = json_decode($json);
					$latitud=$obj->results[0]->geometry->location->lat;
					$longitud=$obj->results[0]->geometry->location->lng;
					$latitud = str_replace(",", ".", $latitud);
					$longitud = str_replace(",", ".", $longitud);

					$data[$num]['longitud']=$longitud;
					$data[$num]['latitud']=$latitud;
					sleep(1);
				}
			}
			$num=$num+1;
		}
	}
	//cerramos el csvs
	fclose($csvFile);

	echo "<table id='importacion-tabla'   class='table table-striped table-bordered nowrap' width='100%' style='text-transform:none' > ";
	echo "<thead>";
	echo "<tr>";
	foreach ($columnData as $key => $value) {
		//echo "<td style='padding: 5px 5px 5px 10px;background-color: #0f9ed6;color:white;'>".strtoupper(str_replace('_',' ',$key))."</td>";
		echo "<th>".strtoupper(str_replace('_',' ',$key))."</th>";
	}
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	$num=1;
	foreach ($data as $key => $atribute) {
		echo "<tr>";
		foreach ($atribute as $key => $valor) {
			tipoValor($key);
			if(tipoValor($key)=="uppercase"){
				$valor=strtoupper($valor);
			}
			$color[$num][$key];
			if($valor==""){
				$valor="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			echo "<td><div style='text-transform: none;".$color[$num][$key]."' >{$valor}</div></td>";
		}
		echo "</tr>";
		$num=$num+1;
	}
	echo "</tbody>";
	echo "</table>";
	//var_dump($tipo_error);
	$fields_pdo = strtoupper(implode(", ", array_unique($tipo_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Campo(s) no valido(s): {$fields_pdo}</div>";
	}

	$fields_pdo = strtoupper(implode(", ", array_unique($encontrado_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Campo(s) no encontrado(s): {$fields_pdo}</div>";
	}

	$fields_pdo = strtoupper(implode(", ", array_unique($requerido_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Campo(s) requerido(s): {$fields_pdo}</div>";
	}

	$fields_pdo = strtoupper(implode(", ", array_unique($unique_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Repetido(s): {$fields_pdo}</div>";
	}

	$fields_pdo = (implode(", ", array_unique($unique_db_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Existe(n) en el sistema: {$fields_pdo}</div>";
	}

	$fields_pdo = (implode(", ", array_unique($unique_db_error_no_encontrado)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>No Existe(n) en el sistema: {$fields_pdo}</div>";
	}
	$fields_pdo = (implode(", ", array_unique($tipo_error_operacion)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Resultado de Operaciones no son correctas en : {$fields_pdo}</div>";
	}
	$fields_pdo = (implode(", ", array_unique($tipo_cuenta_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Error en Tipo de Cuenta : {$fields_pdo}</div>";
	}
	$fields_pdo = (implode(", ", array_unique($tipo_error_SI_NO)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Error , Debe introducir SI o NO : {$fields_pdo}</div>";
	}

	

	
	if(
		!empty($data) &&
		empty($tipo_error) &&
		empty($encontrado_error) &&
		empty($requerido_error) &&
		empty($unique_error) &&
		empty($unique_db_error) &&
		empty($tipo_error_operacion) &&
		empty($tipo_cuenta_error) &&
		empty($tipo_error_SI_NO)
		
	){
		$_SESSION['data_import']=$data;
		$_SESSION['data_import_file']=$_FILES['file'];
		echo '<br><input type="button" id="sumbmitImport" onclick="guardarImportacion()" value="Importar">';
	}
?>