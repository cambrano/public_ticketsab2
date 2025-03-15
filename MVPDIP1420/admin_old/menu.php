<?php
	include __DIR__.'/functions/security.php';
	include __DIR__.'/functions/usuario_permisos.php';

	$seccionesPermiso = seccionesPermiso($_COOKIE["id_usuario"]);
?>
	<ul id="nav" class="nav navbar-nav">
		<li><a href='#' data-toggle="collapse" data-target=".navbar-collapse" id="home">Inicio</a></li>
	<!--<li><a href='#' id="setup_perfiles_personas">Perfiles</a></li>-->
	<?php
		if($seccionesPermiso['security'] || $seccionesPermiso['all'] ){
			?>
			<li><a href='#' data-toggle="collapse" data-target=".navbar-collapse" id="security">Seguridad</a></li>
			<?php
		}
	?>
	<?php
		if($seccionesPermiso['sistema_unico_beneficiarios'] || $seccionesPermiso['all'] ){
			?>
			<li><a href='#' data-toggle="collapse" data-target=".navbar-collapse" id="sistema_unico_beneficiarios">
					Sistema Único De Beneficiarios
				</a>
			</li>
			<?php
		}
	?>
	<?php
		if($seccionesPermiso['sistema_unico_beneficiarios'] || $seccionesPermiso['all'] ){
			?>
			<li style="display:none"><a href='#' data-toggle="collapse" data-target=".navbar-collapse" id="dia_d">Día D</a></li>
			<?php
		}
	?>
	<?php
		if($seccionesPermiso['reportes1'] || $seccionesPermiso['all1'] ){
			?>
			<li><a href='#' data-toggle="collapse" data-target=".navbar-collapse" id="reportes">Reportes</a></li>
			<?php
		}
	?>
	<?php
		if($seccionesPermiso['configuracion'] || $seccionesPermiso['all'] ){
			?>
			<li><a href='#' data-toggle="collapse" data-target=".navbar-collapse" id="settings">Configuración</a></li>
			<?php
		}
	?>
	<!--<li><a href='#' id="soporte">Soporte</a></li>-->
	<!--<li><a href='#' id='soporte' >Soporte Tecnico</a></li> -->
	</ul>