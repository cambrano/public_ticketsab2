<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start();

?>
	<input type="hidden" id="pagina_valor" value="<?= $pagina ?>">
	<div class="graphRight" style="background-color: rgba(197,197,197,0.3);text-align: left;padding: 10px">
		<?php
			if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
				?>
				<!--<button class="btn btn-primary bt_responsive" onclick="printPdf()" >Generar PDF <i class="fas fa-file-pdf"></i></button>--->
				<button class="btn btn-primary bt_responsive" onclick="downloadExcel()" >Generar Excel <i class="fas fa-file-excel"></i></button>
				<?php
			}
		?>
	</div>
	