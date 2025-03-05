<?php
		function equipos_softwares_licenciasDatos($id=null,$id_equipo=null,$id_software=null,$vigencia=null,$orden=null,$limit=null){
			include 'db.php'; 
			$sql="SELECT 
					esol.id, 
					esol.id_equipo, 
					esol.id_software, 
					(SELECT so.nombre FROM softwares so WHERE so.id = esol.id_software) software,
					esol.fecha_inicial, 
					esol.fecha_final, 
					esol.serial, 
					esol.vigencia, 
					esol.observaciones, 
					esol.fechaR, 
					esol.codigo_plataforma, 
					esol.status
					FROM equipos_softwares_licencias esol WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND esol.id = '{$id}' ";
			}
			if($id_equipo!=""){
				$sql.=" AND esol.id_equipo = '{$id_equipo}' ";
			}
			if($id_software!=""){
				$sql.=" AND esol.id_software = '{$id_software}' ";
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