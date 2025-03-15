<?php
    $myObj = new stdClass();
    
	if(!empty($_POST)){
		include __DIR__.'/../functions/security.php'; 
		include __DIR__.'/../functions/db.php'; 
		include __DIR__.'/../functions/secciones_ine_ciudadanos.php'; 
		@session_start();
		$clave_elector = trim($_POST['search_clave_elector']);
		$clave_elector = mysqli_real_escape_string($conexion,$clave_elector);
        //bucamos en la lista_nominal
		$xcl = substr($clave_elector, 0, -1);
		$xcl = $clave_elector;

        $id_seccion_ine_ciudadano_compartido = trim($_POST['search_id_seccion_ine_ciudadano_compartido']);
        if(!empty($id_seccion_ine_ciudadano_compartido)){
            $id_seccion_ine_ciudadano_compartido = mysqli_real_escape_string($conexion,$id_seccion_ine_ciudadano_compartido);
            $where =  " AND sic.id ='{$id_seccion_ine_ciudadano_compartido}' ";
        }else{
            $where =  " AND sic.clave_elector ='{$xcl}' ";
        }
        
		$sql="
			SELECT  
                sic.nombre,
                sic.apellido_paterno,
                sic.apellido_materno,
                sic.nombre_completo,
                sic.clave,
                sic.folio,
				sic.id_seccion_ine_ciudadano_compartido,
                (SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano) tipo,
                sic.id
			FROM secciones_ine_ciudadanos sic
			WHERE 1
			";
        $sql = $sql.$where;
		$result = $conexion->query($sql);
		$row=$result->fetch_assoc();
        if(!empty($row)){
            $myObj->status = '1';
           // Recorrer cada clave y valor en el arreglo $row
            foreach ($row as $key => $value) {
                // Asignar dinámicamente cada clave y valor al objeto $myObj
                $myObj->$key = $value;
            }
        }else{
            $myObj->status = '1';
            $myObj->id = $row['id'];
            $myObj->mensaje = 'vacio';
        }
    }else{
        $myObj->status = '0';
        $myObj->mensaje = 'error';
    }
    header('Content-Type: application/json');
    $myJSON = json_encode($myObj);
	echo $myJSON;