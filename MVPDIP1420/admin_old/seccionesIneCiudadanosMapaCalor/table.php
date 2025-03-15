<?php
	include __DIR__.'/../functions/security.php';
	@session_start();
	if(!empty($_POST)){
		include '../functions/secciones_ine_ciudadanos.php';
		foreach ($_POST['searchTable'][0] as $key => $value) {
			$_SESSION['searchTable'][$key] = trim(mysqli_real_escape_string($conexion,$value));
		}
	}

	if($_POST['mapa'][0]['order']==""){
		$order =0;
	}
	if($_POST['mapa'][0]['order_tipo']==""){
		$order_tipo ="desc";
	}
?>
	<script>
		function downloadExcel(){
			<?php
			if($pageService != $_COOKIE["pageService"]){
				if($pageService!=''){
					$_COOKIE["pageService"] = $pageService ;
				}
			}
			?>
			var link="seccionesIneCiudadanos/excel/index.php?cot=<?=$_COOKIE['pageService']?>"; 
			//window.open(link);
			//window.open(link,'newWindow','width=1280, height=460'); return false;
			//document.location = link;
			window.open(link); 
		}
	</script>