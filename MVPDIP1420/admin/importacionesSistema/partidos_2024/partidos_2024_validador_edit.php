<?php
	if($_FILES['file']["type"]!="text/csv"){
		echo "<br><div class='mensajeError'>Archivo Incorrecto debe ser un .CSV </div>";
		die;
	}
	@session_start();
	unset($_SESSION['data_import']);
	unset($_SESSION['data_import_file']);
	include "../../functions/security.php";
	include "../../functions/claves_2.php";
	include "../../functions/usuarios.php";

	$tipo=$_POST['tipo_operacion'];
	$tabla=$_POST['tabla_operacion'];
	$tipo_vista=$_POST['tipo_vista'];
	if($tipo_vista==0){
		$tipo_vista="true";
	}else{
		$tipo_vista="false";
	}

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
		var ruta = "importacionesSistema/partidos_2024/partidos_2024_db_edit.php";
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
					$("#mensaje").html("&nbsp;");
					document.getElementById("sumbmitImport").disabled = true;
					document.getElementById("mensaje").classList.remove("mensajeError");
					$("#mensaje").html("Guardado con éxito");
					document.getElementById("mensaje").classList.add("mensajeSucces");
					$("#importacionArea").html("&nbsp;");
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


	function validarMoneda($moneda=null){
		$moneda=str_replace(',','',$moneda);
		return is_numeric($moneda);
	}

	function validarNumerico($numerico=null){
		$numerico=str_replace(',','',$numerico);
		return is_numeric($numerico);
	}


	function tipoValor($tipo_valor=null){
		if(strpos($tipo_valor, 'clave')!==false){
			return "uppercase";
		}
		if(strpos($tipo_valor, 'rfc')!==false){
			return "uppercase";
		}
	}



	if($tabla=="partidos_2024"){
		$columnData = array(
				'clave'=>array('alfanumerico','requerido','unique','unique_db','mayuscula'),
				'nombre_corto'=>array('alfanumerico',''),
				'nombre'=>array('alfanumerico',''),
				'icono'=>array('alfanumerico',''),
				'logo'=>array('alfanumerico',''),
				'color_border'=>array('alfanumerico',''),
				'color_background'=>array('alfanumerico',''),
				'principal'=>array('alfanumerico','principal'),
				'tipo'=>array('alfanumerico','alfanumerico'),
				'clave_partidos_coaliciones'=>array('alfanumerico','alfanumerico'),
			);
	}

	//echo "<pre>";
	//print_r($columnData);
	//echo "</pre>";

	$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
	fgetcsv($csvFile);
	if($tipo==2){
		$num=0;
		while(($line = fgetcsv($csvFile)) !== FALSE){
			$countdraw = $countdraw +1;
			if($countdraw > 1){
				$numx=0;
				//sacamos las columnas y verificmos si cumple
				foreach ($columnData as $key => $value) {
					//esto es el valor del csv
					$line_valor=trim($line[$numx]);
					//esto es el nombre de la dato
					$key;
					//este es el tipo de dato
					$value;
					//validaciones
					if(in_array("alfanumerico", $value)){
						
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

					 

					if(in_array("unique", $value)){
						//var_dump($unique[$key]);
						if(empty($unique[$key])){
							$unique[$key][]=$line_valor;
						}else{
							if(in_array($line_valor, $unique[$key])){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$unique_error[]=str_replace('_',' ',$clave_base.' - '.$key);
							}
							$unique[$key][]=$line_valor;
						}
					}


					if($key=='clave'){
						$clave_base=$line_valor;
						$id=clave2Id($line_valor,$tabla);
						if(!clave2ValidadorSistema($line_valor,$tabla)){
							//$id=clave2Id($line_valor,$tabla);
							$color[$num][$key]='background-color: #d9534f;color:white';
							$encontrado_error[]=$line_valor;
						}
					}

					if(in_array("principal", $value)){
						if($line_valor!=""){
							$line_valor = 1;
						}
					}


					//metemos el valor para el data
					$data[$num][$key]=$line_valor;
					//$data[$num][$key]=$line_valor;
					$numx=$numx+1;
				}
			}
			$num=$num+1;
		}
	}
	//cerramos el csvs
	fclose($csvFile);

	echo "<table id='importacion-tabla'   class='table table-striped table-bordered nowrap' width='100%' > ";
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

	$fields_pdo = strtoupper(implode(", ", array_unique($unique_db_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Existe(n) en el sistema: {$fields_pdo}</div>";
	}

	$fields_pdo = (implode(", ", array_unique($tipo_cuenta_error)));
	if(!empty($fields_pdo)){
		echo "<br><div class='mensajeError'>Error en Tipo de Cuenta : {$fields_pdo}</div>";
	}

	if(
		!empty($data) &&
		empty($tipo_error) &&
		empty($encontrado_error) &&
		empty($requerido_error) &&
		empty($unique_error) &&
		empty($unique_db_error) &&
		empty($tipo_error_operacion) &&
		empty($tipo_cuenta_error)
	){
		$_SESSION['data_import']=$data;
		$_SESSION['data_import_file']=$_FILES['file'];
		echo '<br><input type="button" id="sumbmitImport" onclick="guardarImportacion()" value="Importar">';
	}
?>