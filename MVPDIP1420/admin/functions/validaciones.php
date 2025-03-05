<?php
	function validar_file_sql_id($id,$id_session){
		echo $id;
		echo "-----";
		echo $id_session;
		if($id != $id_session){
			echo "Error,identificador incorrecto.Refresque el navegador";
			die;
		}
		die;
	}