<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		</script>
		<?php
		die;
	}
	include __DIR__."/../functions/genid.php";
	$respuestaDatos['clave']="RESP-".$cod16M;
	$claveFRespuesta['input'] = 'disabled="disabled"';
?>	
	<script>
		function generarCadenaAleatoriaConTiempo() {
		const longitud = 6; // Longitud de la cadena
		const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Caracteres permitidos

		let cadenaAleatoria = '';

		// Genera la cadena aleatoria
		for (let i = 0; i < longitud; i++) {
			const caracterAleatorio = caracteres.charAt(Math.floor(Math.random() * caracteres.length));
			cadenaAleatoria += caracterAleatorio;
		}

		// Obtiene el tiempo actual en formato Unix (timestamp)
		const tiempoUnix = Math.floor(Date.now() / 1000); // Divide por 1000 para obtener segundos en lugar de milisegundos

		// Agrega el tiempo al final de la cadena aleatoria
		cadenaAleatoria += tiempoUnix;

		return 'RESP-'+cadenaAleatoria;
		}

	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Pregunta</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $cuestionarioDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Orden<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" type="text" name="orden" autocomplete="off"  id="orden" value="<?= $cuestionarioDatos['orden'] ?>" placeholder="" onkeypress="return CheckNumeric()" maxlength="4" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">No.Válidos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="cantidad" autocomplete="off"  id="cantidad" value="<?= $cuestionarioDatos['cantidad'] ?>" placeholder="" onkeypress="return CheckNumeric()" maxlength="4"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
				$select[$cuestionarioDatos['tipo']] = 'selected="selected"';
			?>
			<select name="tipo" id="tipo" class='myselect'>  
				<option <?= $select['multiple'] ?> value="multiple">Opcion Multiple</option>
				<option <?= $select['abierto'] ?> value="abierto">Abierto</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Campo<font color="#FF0004">*</font></label><br>
			<?php
				$select[$cuestionarioDatos['campo']] = 'selected="selected"';
			?>
			<select name="campo" id="campo" class='myselect'>  
				<option <?= $select[0] ?> value="">Seleccione</option>
				<option <?= $select['text'] ?> value="text">Texto</option>
				<option <?= $select['checkbox'] ?> value="checkbox">Checkbox &#x2611;</option>
				<option <?= $select['radio'] ?> value="radio">Radio  &#x26ac;</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Requerido<font color="#FF0004">*</font></label><br>
			<?php
				$select[$cuestionarioDatos['requerido']] = 'selected="selected"';
			?>
			<select name="requerido" id="requerido" class='myselect'>  
				<option <?= $select[1] ?> value="1">SI</option>
				<option <?= $select[0] ?> value="0">No</option>
			</select>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Pregunta<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" type="text" name="pregunta" autocomplete="off"  id="pregunta" value="<?= $cuestionarioDatos['pregunta'] ?>" placeholder="En comparación con el año anterior,¿La situación de su familia está mejor, peor o igual?" maxlength="255" /><br><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Respuesta(s)</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<div id="mensajeRespuesta" class="mensajeSolo" ><br></div>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveFRespuesta['input'] ?> type="text" style="width: 100%" name="respuesta_clave" autocomplete="off" value="<?= $respuestaDatos['clave'] ?>"  id="respuesta_clave" placeholder="Clave" onblur="aMays(event, this)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Orden<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" type="text" name="respuesta_orden" autocomplete="off"  id="respuesta_orden" placeholder="" onkeypress="return CheckNumeric()" maxlength="4" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Respuesta<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" type="text" name="respuesta_respuesta" autocomplete="off"  id="respuesta_respuesta"  placeholder="Peor" maxlength="255" /><br>
			<input  class="inputlogin" type="text" name="id_respuesta" autocomplete="off"  id="id_respuesta" disabled="disabled" /><br>
		</div>
		
		<div class="sucForm">
			<input type="button" id="sumbmitRespuesta" style="float: left;" value="Crear Respuesta">

		</div>

		<div class="sucForm" style="width:100%">
			<script type="text/javascript">
				var editedRowId = null; // Variable para almacenar temporalmente el ID de la fila editada
				$(document).ready(function() {
					var dataTable = $('#respuestan-tabla').DataTable( {
						"responsive": true,
						"ordering": true,
						"pageLength": 11,
						"retrieve": true,
						"info": false,
						"processing": true,
						"searching": false,
						"paging": false,
						"sPaginationType": "full_numbers",
						"order": [[ 0, "asc" ]],
						"fixedHeader": true,
						"fixedHeader": {
							header: true,
						},
						"aoColumnDefs": [
							{
								"bSortable": false,
								"aTargets": [1,2,3,4 ]
							}, 
							{
								"targets": [],
								"visible": false
							}
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
					$('#sumbmitRespuesta').click(function() {
						// Obtener los valores del formulario
						document.getElementById("sumbmitRespuesta").disabled = true;
						document.getElementById("mensajeRespuesta").classList.remove("mensajeSucces");
						document.getElementById("mensajeRespuesta").classList.remove("mensajeError");
						$("#mensajeRespuesta").html("&nbsp");

						var respuesta_clave = document.getElementById("respuesta_clave").value; 
						if(respuesta_clave == ""){
							document.getElementById("respuesta_clave").focus(); 
							document.getElementById("sumbmitRespuesta").disabled = false;
							$("#mensajeRespuesta").html("Clave requerido");
							document.getElementById("mensajeRespuesta").classList.add("mensajeError");
							return false;
						}

						var respuesta_orden = document.getElementById("respuesta_orden").value; 
						if(respuesta_orden == ""){
							document.getElementById("respuesta_orden").focus(); 
							document.getElementById("sumbmitRespuesta").disabled = false;
							$("#mensajeRespuesta").html("Orden requerido");
							document.getElementById("mensajeRespuesta").classList.add("mensajeError");
							return false;
						}

						var respuesta_respuesta = document.getElementById("respuesta_respuesta").value; 
						if(respuesta_respuesta == ""){
							document.getElementById("respuesta_respuesta").focus(); 
							document.getElementById("sumbmitRespuesta").disabled = false;
							$("#mensajeRespuesta").html("Respuesta requerido");
							document.getElementById("mensajeRespuesta").classList.add("mensajeError");
							return false;
						}

						var id_respuesta = document.getElementById("id_respuesta").value; 

						if (editedRowId !== null) {
							// Actualizar la fila existente con los valores editados
							var row = dataTable.row(editedRowId);
							row.data([respuesta_orden, id_respuesta, respuesta_clave, respuesta_respuesta, '<input class="editar" type="button" data-id="' + editedRowId + '" style="float: left;" value="Editar"> <input class="eliminar" type="button" data-id="' + editedRowId + '" style="float: left;" value="Borrar">']).draw();
							editedRowId = null; // Restablecer el ID de la fila editada
						} else {
							// Crear una nueva fila
							var rowNode = dataTable.row.add([respuesta_orden, id_respuesta, respuesta_clave, respuesta_respuesta, '<input class="editar" type="button" data-id="' + editedRowId + '" style="float: left;" value="Editar"> <input class="eliminar" type="button" data-id="' + editedRowId + '" style="float: left;" value="Borrar">']).draw().node();
							editedRowId = null; // Restablecer el ID de la fila editada
						}


						// Limpiar los campos del formulario
						const cadenaGenerada = generarCadenaAleatoriaConTiempo();
						document.getElementById("respuesta_orden").value = "";
						document.getElementById("id_respuesta").value = "";
						document.getElementById("respuesta_clave").value = cadenaGenerada;
						document.getElementById("respuesta_respuesta").value = "";
						document.getElementById("sumbmitRespuesta").disabled = false;

						// Agregar clases CSS a la nueva fila para DataTable
						$(rowNode).addClass('nueva-fila');
					});


					// Manejador de clic en el botón "Editar" en una fila existente
					$('#respuestan-tabla tbody').on('click', 'input.editar', function() {
						var data = dataTable.row($(this).parents('tr')).data();
						document.getElementById("respuesta_orden").value = data[0];
						document.getElementById("id_respuesta").value = data[1];
						document.getElementById("respuesta_clave").value = data[2];
						document.getElementById("respuesta_respuesta").value = data[3];
						editedRowId = dataTable.row($(this).parents('tr')).index(); // Almacena temporalmente el índice de la fila editada
						document.getElementById("sumbmitRespuesta").disabled = false;
					});

					// Manejador de clic en el botón "Eliminar" en una fila existente
					$('#respuestan-tabla tbody').on('click', 'input.eliminar', function() {
						var row = dataTable.row($(this).parents('tr'));
						row.remove().draw();
					});
				});
			</script>
			<table id="respuestan-tabla"   class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
				<thead>
					<tr>
						<th>Orden</th>
						<th>id</th>
						<th>Clave</th>
						<th>Respuesta</th>
						<th>Opción</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$num = 0;
						foreach ($cuestionario_respuestasDatos as $key => $value) {
							echo "<tr>";
							echo "<td>".$value['orden']."</td>";
							echo "<td>".$value['id']."</td>";
							echo "<td>".$value['clave']."</td>";
							echo "<td>".$value['respuesta']."</td>";
							echo "<td>";
							?>
							<input class="editar" type="button" data-id="<?= $num ?>" style="float: left;" value="Editar"> 
							<input class="eliminar" type="button" data-id="<?= $num ?>" style="float: left;" value="Borrar">
							<?php
							echo "</td>";
							echo "</tr>";
							$num = $num + 1;
						}
					?>
				</tbody>
			</table>
		</div>
		<br>
		<div class="sucForm" style="width: 100%" >
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>