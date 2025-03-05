<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_permisos',$_COOKIE["id_usuario"]);
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
?>
	<script type="text/javascript">
		function id_seccion() {
			var id_seccion = document.getElementById("id_seccion").value;
			var id_seccion = id_seccion.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("id_seccion").value=id_seccion;
			var id_empleado = '<?= $id_empleado ?>';
			if(id_seccion == ""){
				document.getElementById("id_modulo").value="";
				var dataString = 'id_seccion=x';
				$.ajax({
					type: "POST",
					url: "modulos/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_modulo").html(data);
					}
				});
				document.getElementById("permisos").value="";
				var dataString = 'id_modulo=x';
				$.ajax({
					type: "POST",
					url: "permisos/ajax.php",
					data: dataString,
					success: function(data) {
						$("#permisos").html(data);
					}
				});
			}else{
				var dataString = 'id_seccion='+id_seccion+'&id_empleado='+id_empleado;
				$.ajax({
					type: "POST",
					url: "modulos/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_modulo").html(data);
					}
				});
			}
		}
		function id_modulo(){
			var id_modulo = document.getElementById("id_modulo").value;
			var id_modulo = id_modulo.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("id_modulo").value=id_modulo;
			if(id_modulo == ""){
				document.getElementById("permisos").value="";
				var dataString = 'id_modulo=x';
				$.ajax({
					type: "POST",
					url: "permisos/ajax.php",
					data: dataString,
					success: function(data) {
						$("#permisos").html(data);
					}
				});
			}else{
				var dataString = 'id_modulo='+id_modulo+'&tipo=chbox';
				$.ajax({
					type: "POST",
					url: "permisos/ajax.php",
					data: dataString,
					success: function(data) {
						$("#permisos").html(data);
					}
				});
			}
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Permiso Empleado</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Seccion<font color="#FF0004">*</font></label><br>
			<select class="myselect" name="id_seccion" id="id_seccion" onchange="id_seccion()" <?= $disabled ?> >  
				<?php
				echo secciones($usuario_moduloDatos['id_seccion']);
				?>
			</select><br>
		</div> 
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Modulo<font color="#FF0004">*</font></label><br>
			<select class="myselect" name="id_modulo" id="id_modulo" onchange="id_modulo()" <?= $disabled ?> >  
				<?php
				echo modulosold($usuario_moduloDatos['id_modulo'],$usuario_moduloDatos['id_seccion']);
				?>
			</select><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Permisos<font color="#FF0004">*</font></label><br>
			<div id="permisos">
				<?php
					echo permisosChk($usuario_moduloDatos['id_modulo'],'',$ids_permiso);
				?>
			</div>
		</div>

		<div class="sucForm" style="width: 100%">
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