<?php
	include '../functions/usuario_permisos.php';
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_nacimiento" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_nacimiento").style.border= "";
				}
			}); 
		}); 
	</script>

	<div style=" width: 100%; display:inline-block; text-align: left;">

		<?php
			foreach ($paqueteSistemaDatos as $key => $value) {
				?>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname"><?= $key ?><font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="<?= $key ?>" autocomplete="off"  id="<?= $key ?>" value="<?= $value ?>" placeholder="" /><br>
				</div>
				<?php
			}

		?>
 

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if(
				moduloAccion('clientes','pasajeros',$_COOKIE["id_usuario"],$permiso) ||
				moduloAccion('clientes','pasajeros',$_COOKIE["id_usuario"],'All') ){
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