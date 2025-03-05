<?php
		function statusGeneral() {  
			$return ="<option selected='selected' value='' >Seleccione</option> "; 
			$return .="<option value='1' >Activo</option> "; 
			$return .="<option value='x' >No Activo</option> "; 
			return $return;
		}

		function statusWebsiteGeneral($id = null) {  
			if($id==1){
				$selected[1]='selected="selected"';
			}else{
				$selected[2]='selected="selected"';
			}
			$return ="<option selected='selected' value='' >Seleccione</option> "; 
			$return .="<option ".$selected[1]." value='1' >Online</option> "; 
			$return .="<option ".$selected[0]." value='0' >No Offline</option> "; 

			return $return;
		}
		function statusMensajeGeneral() {  
			$return ="<option selected='selected' value='' >Seleccione</option> "; 
			$return .="<option value='1' >Leido</option> "; 
			$return .="<option value='0' >No Leido</option> "; 
			return $return;
		}

		 

		function statusAtendido($id=null) {  
			if($id==1){
				$selected[1]='selected="selected"';
			}else{
				$selected[2]='selected="selected"';
			}
			$return ="<option value='' >Seleccione</option> "; 
			$return .="<option ".$selected[1]." value='1' >Atendido</option> "; 
			$return .="<option ".$selected[2]." value='0' >Pendiente</option> "; 
			return $return;
		}

		function statusPago($id=null) {  
			if($id==1){
				$selected[1]='selected="selected"';
			}else{
				$selected[2]='selected="selected"';
			}
			$return ="<option value='' >Seleccione</option> "; 
			$return .="<option ".$selected[1]." value='1' >Devuelto</option> "; 
			$return .="<option ".$selected[2]." value='0' >Pendiente</option> "; 
			return $return;
		}

		function statusMensajeGeneralForm($id=null) {  
			if($id==1){
				$selected[1]='selected="selected"';
			}else{
				$selected[2]='selected="selected"';
			}
			$return ="<option value='' >Seleccione</option> "; 
			$return .="<option ".$selected[1]." value='1' >Leido</option> "; 
			$return .="<option ".$selected[2]." value='0' >No Leido</option> "; 
			return $return;
		}


		function statusGeneralNombre($id) {  
			if($id==1){
				$return="Activo";
			}else{
				$return="No Activo";
			}
			return $return;
		}

		function statusGeneralForm($id=null) {  
			if($id==1){
				$selected[1]='selected="selected"';
			}else{
				$selected[2]='selected="selected"';
			}
			$return ="<option value='' >Seleccione</option> "; 
			$return .="<option ".$selected[1]." value='1' >Activo</option> "; 
			$return .="<option ".$selected[2]." value='0' >No Activo</option> "; 
			return $return;
		}
		
		function statusMostrarForm($id=null) {  
			$selected[$id]='selected="selected"';
			$return .="<option ".$selected[1]." value='1' >Mostrar</option> "; 
			$return .="<option ".$selected[0]." value='0' >No Mostrar</option> "; 
			return $return;
		}

?>