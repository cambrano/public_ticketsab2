<?php
	include '../functions/usuario_permisos.php';
?>
	<script>
		function locationEstado(){
			var id_estado = document.getElementById("id_estado").value;
			if(id_estado == ""){
				document.getElementById("id_estado").style.border= "1px solid red";
				document.getElementById("id_municipio").style.border= "";
				document.getElementById("id_municipio").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "municipios/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_municipio").html(data);
					}
				});
			}else{
				document.getElementById("id_estado").style.border= "";
				document.getElementById("id_municipio").style.border= "";
				var dataString = 'id_estado='+id_estado;
				$.ajax({
					type: "POST",
					url: "municipios/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_municipio").html(data);
					}
				});
			}
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Partido Legado<font color="#FF0004">*</font></label><br>
			<select id="id_partido_legado" class='myselect'>
				<?php echo partidos_legados(); ?>
			</select>
			<br>
		</div>


		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
			<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
			<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);" >  
				<?php
				echo estados();
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
			<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
			<select   name="id_municipio" id="id_municipio" class='myselect'>  
				<?php
				echo municipios($id_municipio);
				?>
			</select>
		</div>

		<div class="sucForm" >
			<label class="labelForm" id="labeltemaname">Distritos Locales</label><br>
			<select class='myselect' id="id_distrito_local" >
				<?php
				echo distritos_locales($id_distrito_local,'');
				?>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Distritos Federales</label><br>
			<select class='myselect' id="id_distrito_federal" >
				<?php
				echo distritos_federales($id_distrito_federal,'');
				?>
			</select>
		</div>

		<div style="width:100%; display:inline-block;">
			<pre>
				<?php
				$fp = fopen("keySistema/nf4WUJ1540838393iaHbsU1540838393.php", "r");
				$line_inicio = 1;
				$line_fin = 30;
				while (($line = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
					$line_inicio ++;
					if($line_inicio <=$line_fin){
						echo $line;
						echo "<br>";
					}
				}
				?>
			</pre>
		</div>
		


		<div class="sucForm" style="width: 100%">
			<br>
			<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
			<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>