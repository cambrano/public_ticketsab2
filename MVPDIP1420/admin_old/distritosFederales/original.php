<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";


	if(!empty($_POST)){
		//var_dump($_FILES['limitesn']["tmp_name"]);
		//var_dump($_POST['alt']);
		if($_POST['num']==""){
			//echo "1/";
			//echo "-";
			$num=$_SESSION['limites_num'];
			//echo "-";
			if($num==""){
				//echo "1.1/";
				$num=0;
				$_SESSION['limites_num']=$num;
			}
			if($num>=0){
				//echo "1.2/";
				$num=$num+1;
				$_SESSION['limites_num']=$num;
			}
			$latitud=$_POST['seccion_limite'][0]['latitud'];
			$longitud=$_POST['seccion_limite'][0]['longitud'];
			$orden=$_POST['seccion_limite'][0]['orden'];

			$_SESSION['limites'][$num]= array(
				'numero' => $num,
				'orden'=>$orden,
				'longitud'=>$longitud,
				'latitud'=>$latitud,
				'status'=>'1',
				'id'=>"",
			);

		}else{
			//editamos o eliminamos
			//echo "2/";
			$update=$_POST['update'];
			$tipo_limitesn=$_POST['tipo_limitesn'];
			$num=$_POST['num'];
			if($update ==""){
				$_SESSION['limites'][$num]['status']=0;
			}else{
				if($_FILES['limitesn']['tmp_name'] != ""){
					$tipo_limitesn=$_POST['tipo_limitesn'];
					$name = $_FILES['limitesn']['name']; 
					$type = $_FILES['limitesn']['type']; 
					$file_size = $_FILES['limitesn']['size']; 
					$limites = file_get_contents($_FILES['limitesn']['tmp_name']);
					$name_cod=$cod32.".png";

					$_SESSION['limites'][$num]['name'] = $name;
					$_SESSION['limites'][$num]['limitesPrint'] = $limites;
					$_SESSION['limites'][$num]['tipo_limitesn'] = $tipo_limitesn;
					$_SESSION['limites'][$num]['file_name'] = $name_cod;
					$_SESSION['limites'][$num]['type'] = $type;
					$_SESSION['limites'][$num]['file_size'] = $file_size;
					$_SESSION['limites'][$num]['status'] = 1;
				}else{
					$tipo_limitesn=$_POST['tipo_limitesn'];
					$_SESSION['limites'][$num]['tipo_limitesn'] = $tipo_limitesn;
					$_SESSION['limites'][$num]['status'] = 1;

				}
			}
		}

	}
?>
<script type="text/javascript">
	$(document).ready(function() {
		var dataTable = $('#limites-tabla').DataTable( {
			"responsive": true,
			"ordering": true,
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
							{ "bSortable": false, "aTargets": [ 0 ] }
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
foreach ($_SESSION['limites'] as $key => $value) {
	$odenar[$value['orden']][$value['numero']]= array('orden' => $value['orden'],'numero' => $value['numero'],'latitud' => $value['latitud'],'longitud' => $value['longitud'],'status' => $value['status'], );
}
foreach ($odenar as $key => $value) {
	foreach ($value as $keyT => $valueT) {
		$limites[] = array('orden' => $valueT['orden'],'numero' => $valueT['numero'],'latitud' => $valueT['latitud'],'longitud' => $valueT['longitud'],'status' => $valueT['status'],  );
	}
}

echo "<pre>";
print_r($limites);
echo "</pre>";
?>
<table id="limites-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
	<thead>
		<tr>
			<th>Orden</th>
			<th>Latitud</th> 
			<th>longitud</th>
			<th>Opción</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($_SESSION['limites'] as $key => $value) {
			if($value['status']==1){
				echo "<tr>";
				echo "<td style='font-size:8px'>".$value['orden']."</td>"; 
				echo "<td style='font-size:8px'>".$value['latitud']."</td>"; 
				echo "<td style='font-size:8px'>".$value['longitud']."</td>"; 
				echo '<td> <input type="button" id="sumbmitImage" style="float: left;" onclick="editarImage('.$key.')" value="Editar">  <input type="button" id="sumbmitImage" style="float: left;" onclick="eliminarImage('.$key.')" value="Borrar"></td>';
				echo "</tr>";
			}
		}

		?>
	</tbody>
</table>
<br>
<script type="text/javascript">
	function myMapLimites(coordenadas=null,zoomCoordenada=null) {
		tipo_update="<?= $id ?>";
		if(coordenadas==null && zoomCoordenada==null){
			latitud=19.4978;
			longitud= -99.1269;
			zoom=5;
		}
		if(coordenadas==null && tipo_update != null){
			latitud=<?= $latitud ?>;
			longitud=<?= $longitud ?>;
			zoom=<?= $zoom ?>;
		}
		if(coordenadas != null ){
			latitud=coordenadas.lat;
			longitud=coordenadas.lng;
			zoom=zoomCoordenada;
		}
		var myLatlng = new google.maps.LatLng( latitud,longitud); 
		var myOptions = {
			zoom: zoom,
			center: myLatlng,
		} 
		var map1 = new google.maps.Map(document.getElementById("googleMapLimite"), myOptions); 
		marker1 = new google.maps.Marker({ 
			position: myLatlng,
			draggable: true,
		});

		<?php
			foreach ($limites as $key => $value) {
				
				?>
				var myLatlng = new google.maps.LatLng(latitud,longitud); 
				var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/blue-dot.png');
				marker_limite = new google.maps.Marker({ 
					position: myLatlng,
					draggable: false,
					icon: pinImage,
				});
				marker_limite.setMap(map1);
				<?php
			}
		?>


		google.maps.event.addListener(marker1, "dragend", function() { getCoordsLimites(marker1); });
		marker1.setMap(map1); 
		getCoordsLimites(marker1); 
	}
	function getCoordsLimites(marker1){ 
		document.getElementById("latitud_limite").value = marker1.getPosition().lat(); 
		document.getElementById("longitud_limite").value = marker1.getPosition().lng(); 
	}
</script>
<div id="mapa">
	<div id="googleMapLimite" style="width:100%;height:400px;"></div>
</div>
<?php
	if(!empty($_POST)){
		?>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMapLimites"></script>
		<?php
	}
?>