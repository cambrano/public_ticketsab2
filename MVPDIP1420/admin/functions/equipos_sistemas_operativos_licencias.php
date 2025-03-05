<?php
		function equipos_sistemas_operativos_licenciasDatos($id=null,$id_equipo=null,$id_sistema_operativo=null,$vigencia=null,$orden=null,$limit=null){
			include 'db.php'; 
			$sql="SELECT 
					esol.id, 
					esol.id_equipo, 
					esol.id_sistema_operativo, 
					(SELECT so.nombre FROM sistemas_operativos so WHERE so.id = esol.id_sistema_operativo) sistema_operativo,
					esol.fecha_inicial, 
					esol.fecha_final, 
					esol.serial, 
					esol.vigencia, 
					esol.observaciones, 
					esol.fechaR, 
					esol.codigo_plataforma, 
					esol.status
					FROM equipos_sistemas_operativos_licencias esol WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND esol.id = '{$id}' ";
			}
			if($id_equipo!=""){
				$sql.=" AND esol.id_equipo = '{$id_equipo}' ";
			}
			if($id_sistema_operativo!=""){
				$sql.=" AND esol.id_sistema_operativo = '{$id_sistema_operativo}' ";
			}
			if($vigencia!=""){
				$sql.=" AND esol.vigencia = '{$vigencia}' ";
			}

			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}
?>