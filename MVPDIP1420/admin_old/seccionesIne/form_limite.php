<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";


	if(!empty($_POST)){
		include __DIR__."/../functions/secciones_ine_parametros.php";
		$secciones_ine_parametrosDatos = secciones_ine_parametrosDatos('','',' id_seccion_ine,orden ASC');

		foreach ($secciones_ine_parametrosDatos as $key => $value) {
			$secciones_area[$value['id_seccion_ine']][] = $value ;
		}
		//var_dump($_FILES['limitesn']["tmp_name"]);
		//var_dump($_POST['alt']);
		if($_POST['seccion_limite'][0]['numero']=="" && $_POST['delete']=="" ){
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
			if( $_POST['delete'] !=""){
				$num = $_POST['numero'];
				$_SESSION['limites'][$num]['status']=0;
			}else{
				$latitud=$_POST['seccion_limite'][0]['latitud'];
				$longitud=$_POST['seccion_limite'][0]['longitud'];
				$orden=$_POST['seccion_limite'][0]['orden'];
				$id=$_POST['seccion_limite'][0]['id'];
				$numero=$_POST['seccion_limite'][0]['numero'];
				$_SESSION['limites'][$numero]= array(
					'numero' => $numero,
					'orden'=>$orden,
					'longitud'=>$longitud,
					'latitud'=>$latitud,
					'status'=>'1',
					'id'=>$id,
				);
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

?>

<br>
<script type="text/javascript">
	function myMapLimites() {
		latitud = document.getElementById("latitud").value; 
		longitud = document.getElementById("longitud").value; 
		zoom=8;
		var style = 
			[
				{
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#d6e2e6"
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#cfd4d5"
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#7492a8"
						}
					]
				},
				{
					"featureType": "administrative.neighborhood",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"lightness": 25
						}
					]
				},
				{
					"featureType": "landscape.man_made",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#dde2e3"
						}
					]
				},
				{
					"featureType": "landscape.man_made",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#cfd4d5"
						}
					]
				},
				{
					"featureType": "landscape.natural",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#dde2e3"
						}
					]
				},
				{
					"featureType": "landscape.natural",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#7492a8"
						}
					]
				},
				{
					"featureType": "landscape.natural.terrain",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#dde2e3"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#588ca4"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "labels.icon",
					"stylers": [
						{
							"saturation": -100
						}
					]
				},
				{
					"featureType": "poi.park",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#a9de83"
						}
					]
				},
				{
					"featureType": "poi.park",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#bae6a1"
						}
					]
				},
				{
					"featureType": "poi.sports_complex",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#c6e8b3"
						}
					]
				},
				{
					"featureType": "poi.sports_complex",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#bae6a1"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#41626b"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "labels.icon",
					"stylers": [
						{
							"saturation": -45
						},
						{
							"lightness": 10
						},
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#c1d1d6"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#a6b5bb"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "road.highway.controlled_access",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#9fb6bd"
						}
					]
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff"
						}
					]
				},
				{
					"featureType": "road.local",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff"
						}
					]
				},
				{
					"featureType": "transit",
					"elementType": "labels.icon",
					"stylers": [
						{
							"saturation": -70
						}
					]
				},
				{
					"featureType": "transit.line",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#b4cbd4"
						}
					]
				},
				{
					"featureType": "transit.line",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#588ca4"
						}
					]
				},
				{
					"featureType": "transit.station",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "transit.station",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#008cb5"
						},
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "transit.station.airport",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"saturation": -100
						},
						{
							"lightness": -5
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#a6cbe3"
						}
					]
				}
			];
		var myLatlng = new google.maps.LatLng(latitud,longitud); 
		var myOptions = {
			zoom: zoom,
			center: myLatlng,
			styles: style,
			panControl: true,
			zoomControl: true,
			mapTypeControl: true,
			streetViewControl: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: true,
			minZoom: zoom - 113,
			maxZoom: zoom + 113,
		}

		/////////////////////////////////
		//punto que se mueve para ajustar
		var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
		var mapa_secciones = new google.maps.Map(document.getElementById("googleMapLimite"), myOptions); 
		marker_punto = new google.maps.Marker({ 
			position: myLatlng,
			draggable: true,
			icon: pinImage,
		});

		google.maps.event.addListener(marker_punto, "dragend", function() { getCoordsLimites(marker_punto); });
		marker_punto.setMap(mapa_secciones); 
		getCoordsLimites(marker_punto);

		
		/////////////////////////////////
		/////////////////////////////////

		/////////////////////////////////
		/////PUNTOS DELIMITADOR DEL AREA
		<?php
			$num=1;
			foreach ($limites as $key => $value) {
				if($value['status']==1){
					?>
					var label = "<?= $value['orden'] ?>";
					var myLatlng = new google.maps.LatLng(<?= $value['latitud'] ?>,<?= $value['longitud'] ?>); 
					marker_areas = new google.maps.Marker({ 
						position: myLatlng,
						draggable: false,
						
						label: {
							text: label,
							color: '#000',
					        fontSize: '20px',
					        fontWeight: 'bold',
						},
					});
					marker_areas.setMap(mapa_secciones);
					<?php
					$num = $num+1;
				}
			}
		?>

		const seccion_coordenadas = [
			<?php
				$num=1;
				foreach ($limites as $key => $value) {
					if($value['status']==1){
						?>
						{ lat: <?= $value['latitud'] ?>, lng: <?= $value['longitud'] ?> },
						<?php
						$num = $num+1;
					}
				}
			?>
		];
		const seccion_area = new google.maps.Polygon({
			paths: seccion_coordenadas,
			strokeColor: "#FF0000",
			strokeOpacity: 0.8,
			strokeWeight: 1,
			fillColor: "#b3ecb8",
			fillOpacity: 0.35,
		});
		seccion_area.setMap(mapa_secciones);
		/////////////////////////////////

		/*
		var myLatlng = new google.maps.LatLng(20.962005395031248,-89.61703066442642); 
		var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/blue-dot.png');
		marker2 = new google.maps.Marker({ 
			position: myLatlng,
			draggable: false,
			icon: pinImage,
		});
		marker2.setMap(map1);

		var myLatlng_limite = new google.maps.LatLng( 20.96391521254444 , -89.61711381290588 ); 
		var pinImage_limite = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/yellow-dot.png');
		marker2 = new google.maps.Marker({ 
			position: myLatlng_limite,
			draggable: false,
			icon: pinImage_limite,
		});
		marker2.setMap(map1);
		*/
		/////////////////////////////////
		// SECCIONES OTRAS AREAS
		<?php
		foreach ($secciones_area as $key => $value) {
			echo "triangleCoords".$key." = [";
			foreach ($value as $keyT => $valueT) {
				echo "{ lat: ".$valueT['latitud'].", lng: ".$valueT['longitud']." },";
			}
			echo "];";
			?>
			secciones_areas<?= $key ?> = new google.maps.Polygon({
				paths: triangleCoords<?= $key ?>,
				strokeColor: "#FF0000b3ecb8",
				strokeOpacity: 0.8,
				strokeWeight: 1,
				fillColor: "#FF0000",
				fillOpacity: 0.35,
			});
			secciones_areas<?= $key ?>.setMap(mapa_secciones);
			<?php
		}
		?>
		/////////////////////////////////
		var marcadores = [
		<?php
		foreach ($secciones_area as $key => $value) {
			foreach ($value as $keyT => $valueT) {
				echo "['".$valueT['id']."', ".$valueT['latitud'].", ".$valueT['longitud'].",'SI_SCRIPT'],";
			}
		}
		?>
		];

		var infoWindowContent = [
		<?php
		foreach ($secciones_area as $key => $value) {
			foreach ($value as $keyT => $valueT) {
				$div = '<div style="width:100px;margin:0 0 20px 20px;height:60px;">
							<div class="info_content" style="width: 100%">
								<p>
									Latitud: <b>'.$valueT['latitud'].'</b><br>
									Longitud: <b>'.$valueT['longitud'].'</b><br>
								</p>
							</div>
						</div>';
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
				?>
				['<?= $div ?>'],
				<?php
			}
		}
		?>
		];

		var infowindow = new google.maps.InfoWindow();
		var marker_punto_1, i;
		pinImageYellow = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/yellow-dot.png');

		for (i = 0; i < marcadores.length; i++) {
			var pinImage = pinImageYellow;
			marker_punto_1 = new google.maps.Marker({
				position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
				map: mapa_secciones,
				icon: pinImage,
			});

			google.maps.event.addListener(marker_punto_1, 'click', (function(marker_punto_1, i) {
				return function() {
					infowindow.setContent(infoWindowContent[i][0]);
					infowindow.open(mapa_secciones, marker_punto_1);
				}
			})(marker_punto_1, i));

		}

		



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
				echo '<td> <input type="button" id="sumbmitImage" style="float: left;" onclick="editarLimite('.$value['numero'].')" value="Editar">  <input type="button" id="sumbmitImage" style="float: left;" onclick="eliminarLimite('.$value['numero'].')" value="Borrar"></td>';
				echo "</tr>";
			}
		}

		?>
	</tbody>
</table>