<?php
		include '../functions/security.php'; 
		include '../functions/sub_dependencias.php'; 
		if(!empty($_POST)){
			if($_POST['tipo']=='list_select'){
				$id_dependencia=$_POST['id_dependencia'];
				if($id_dependencia !="x"){
					echo sub_dependencias('',$id_dependencia,'');
				}else{
					echo "<option value='' >Seleccione</option> ";
				}
			}
		}