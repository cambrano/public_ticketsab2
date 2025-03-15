<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
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
	<script>
		$( function() {
			$( '#monstar_contraseña' ).on( 'click', function() {
				if( $(this).is(':checked') ){
					// Hacer algo si el checkbox ha sido seleccionado
					$('#labelMostrar').html("Ocultar");
					document.getElementById("password").type = "text";
					document.getElementById("password1").type = "text";
				} else {
					// Hacer algo si el checkbox ha sido deseleccionado
					$('#labelMostrar').html("Mostrar");
					document.getElementById("password").type = "password";
					document.getElementById("password1").type = "password";
				}
			});
			$("#usuario").keyup(function(){              
					var ta      =   $("#usuario");
					letras      =   ta.val().replace(/ /g, "");
					ta.val(letras)
			}); 
			$("#password").keyup(function(){              
					var ta      =   $("#password");
					letras      =   ta.val().replace(/ /g, "");
					ta.val(letras)
			}); 
			$("#password1").keyup(function(){              
					var ta      =   $("#password1");
					letras      =   ta.val().replace(/ /g, "");
					ta.val(letras)
			});
		})
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Usuario</label>
		</div>
		<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="usuario" autocomplete="off"  id="usuario" value="<?= $usuarioDatos['usuario']  ?>" placeholder="" maxlength="45" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="password" autocomplete="off"  id="password" value="<?= $usuarioDatos['password'] ?>" placeholder="" maxlength="10" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Repetir Password<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="password1" autocomplete="off"  id="password1" value="<?= $usuarioDatos['password'] ?>" placeholder="" maxlength="10" />
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Mostrar</label><input type="checkbox"  id="monstar_contraseña" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
					<select id="status" class="myselect" name="status" >
					<?php	echo statusGeneralForm($usuarioDatos['status']); ?>
				</select><br><br>
			</div>
			<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Permisos Usuario</label>
			</div>

			<?php
				if($seccion_ine_ciudadano_permisosDatos['entrega'] == 1){
					$chkEntrega = 'checked="checked"';
				}

				if($seccion_ine_ciudadano_permisosDatos['recibe'] == 1){
					$chkRecibe = 'checked="checked"';
				}

				if($seccion_ine_ciudadano_permisosDatos['casilla'] == 1){
					$chkCasilla = 'checked="checked"';
				}
			?>

			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Lleva Ciudadano</label><input type="checkbox" <?= $chkEntrega ?> id="entrega" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Recibe Ciudadano</label><input type="checkbox" <?= $chkRecibe ?> id="recibe" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>
			<div class="sucForm">
				<label class="labelForm" id="labelMostrar">Info. Casilla</label><input type="checkbox" <?= $chkCasilla ?> id="casilla" value="1"><br>
			</div>
			<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
			<input type="button" value="Cancelar" onclick="cerrar()" >
		</div>
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>