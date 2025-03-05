<?php
	include __DIR__.'/../functions/security.php';
	@session_start();
	if(!empty($_POST)){
		include '../functions/switch_operaciones.php';
		include '../functions/secciones_ine_ciudadanos_permisos.php';
		$seccion_ine_ciudadano_permisosDatos = seccion_ine_ciudadano_permisosDatos('','',$_COOKIE["id_usuario"]);
		$switch_operacionesPermisos = switch_operacionesPermisos();
		if($switch_operacionesPermisos['entrega'] && $seccion_ine_ciudadano_permisosDatos['entrega'] == "1"){
			$entrega = true;
		}else{
			$entrega = false;
		}

		if($switch_operacionesPermisos['recibe'] && $seccion_ine_ciudadano_permisosDatos['recibe'] == "1"){
			$recibe = true;
		}else{
			$recibe = false;
		}
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$escapedValue = mysqli_real_escape_string($conexion, $value);
			$_POST['searchTable'][0][$key] = $escapedValue;
		}
		$postData = json_encode($_POST);
	}else{
		$postData = "''";
	}
?>
	<script type="text/javascript" language="javascript" >
		$(document).ready(function() {
			var responsive=true;
			$(window).resize(function() {
				var widthBrowser =$(window).width();
				var heightBrowser =$(window).height();
				//console.log("Tamaño de la pantalla del navegador: width="+widthBrowser +" height="+heightBrowser );
				if(widthBrowser<820){
					var responsive=true;
				}else{
					var responsive=false;
				}
			});
			var dataTable = $('#secciones_ine_ciudadanos_entrega-tabla').DataTable( {
				"destroy": true,
				"responsive": responsive,
				"pageLength": 11,
				"retrieve": true,
				"info": true,
				"processing": true,
				"sPaginationType": "full_numbers", 
				"fixedHeader": true,
				"fixedHeader": {
					header: true,
				},
				"order": [[ 2, "desc" ]],
				"ordering": true,
				"searching": false,/* para el buscador*/
				"paging": true,/* para la paginacion y todo salga en una sola hora*/
				"aoColumnDefs": [
								/*{ "bSortable": false, "aTargets": [ 2 ] }*/
								],
				"serverSide": true,
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
				"ajax":{
					url :"seccionesIneCiudadanosEntrega/secciones_ine_ciudadanos_entrega.php", // json datasource
					type: "post",  // method  , by default get
					data: {
						postData: <?php echo $postData; ?>
					},
					error: function(){  // error handling
						$(".secciones_ine_ciudadanos_entrega-tabla-error").html("");
						$("#secciones_ine_ciudadanos_entrega-tabla").append('<tbody class="secciones_ine_ciudadanos_entrega-tabla-error"><tr><th colspan="8">Registros no encontrados</th></tr></tbody>');
						$("#secciones_ine_ciudadanos_entrega-tabla_processing").css("display","none");
						
					}
				}
			});

			$('#secciones_ine_ciudadanos_entrega-tabla').css( 'display', 'table' );
			$('#secciones_ine_ciudadanos_entrega-tabla').resize();
			$('#secciones_ine_ciudadanos_entrega-tabla').DataTable().columns.adjust().responsive.recalc();
			$("#secciones_ine_ciudadanos_entrega-tabla_filter").css("display","none");  // hiding global search box
			$('#selectCheckbox').on("click", function(event){ // triggering delete one by one
				if( $('.checkselected:checked').length > 0 ){  // at-least one checkbox checked
					var ids = [];
					$('.checkselected').each(function(){
						if($(this).is(':checked')){ 
							ids.push($(this).val());
						}
					});
					var ids_string = ids.toString();  // array to string conversion   
					var link="seccionesIneCiudadanos/index.php?cot="+ids_string;   
					var link2="seccionesIneCiudadanos/index.php";
					dataString = 'urlink='+link2;  
					$.ajax({
						type: "POST",
						url: "functions/backarray.php",
						data: dataString,
						success: function(data) { 	}
					});
					////
					$("#homebody").load(link); 
				}
			});

		});
		function entrega(value){
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			document.getElementById("entrega_"+value).disabled = false;

			var id_casilla_voto_2024 = document.getElementById("id_casilla_voto_2024").value;
			if(id_casilla_voto_2024==""){
				document.getElementById("entrega_"+value).disabled = true;
				$("#mensaje").html("Casilla requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var id_seccion_ine_ciudadano = value;

			var casilla_voto = [];
			var data = {
					'id_casilla_voto_2024' : id_casilla_voto_2024,
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
				}
			casilla_voto.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosEntrega/entrega_ciudadano.php",
				data: {casilla_voto: casilla_voto},
				async: true,
				success: function(data) {
					var res = data.substr(0,2);
					var hora = data.substr(2);
					if(res=="SI"){
						document.getElementById("entrega_"+value).classList.remove("btn-warning");
						document.getElementById("entrega_"+value).classList.add("btn-success");
						document.getElementById("entrega_img_"+value).src = "img/pasajero20.png";
						document.getElementById("entrega_"+value).disabled = true;
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#entrega_hora_"+value).html(hora);
						document.getElementById("entrega_hora_"+value).style.backgroundColor="green";
						$("#mensaje").html("Gracias.");
					}else{
						document.getElementById("entrega_"+value).disabled = false;
						document.getElementById("mensaje").classList.add("mensajeError");
						$("#mensaje").html("Error, refresque gracias.");
						$("#mensaje").html(data);
					}
					
				}
			});
		}
		function recibe(value){
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			document.getElementById("recibe_"+value).disabled = false;
			var id_seccion_ine_ciudadano = value;
			var casilla_voto = [];
			var data = {
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
				}
			casilla_voto.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosEntrega/recibe_ciudadano.php",
				data: {casilla_voto: casilla_voto},
				async: true,
				success: function(data) {
					var res = data.substr(0,2);
					var hora = data.substr(2);
					if(res=="SI"){
						document.getElementById("recibe_"+value).classList.remove("btn-warning");
						document.getElementById("recibe_"+value).classList.add("btn-success");
						document.getElementById("recibe_img_"+value).src = "img/pasajero20.png";
						document.getElementById("recibe_"+value).disabled = true;
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#recibe_hora_"+value).html(hora);
						document.getElementById("recibe_hora_"+value).style.backgroundColor="green";
						$("#mensaje").html("Gracias.");
					}else{
						document.getElementById("recibe_"+value).disabled = false;
						document.getElementById("mensaje").classList.add("mensajeError");
						$("#mensaje").html("Error, refresque gracias.");
						$("#mensaje").html(data);
					}
					
				}
			});
		}
	</script> 
	<table id="secciones_ine_ciudadanos_entrega-tabla" class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
		<thead>
			<tr>
				<th>Clave</th>
				<th>Sección</th> 
				<th>Manzana</th> 
				<th>Nombre Completo</th> 
				<th>Whatsapp</th>
				<th>Celular</th>
				<th>Dirección</th>
				<?php
					if($recibe){
						echo "<th>Check OUT</th>";
					}
				?> 
			</tr> 
		</thead> 
	</table>  
