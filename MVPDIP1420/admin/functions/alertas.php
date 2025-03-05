<?php
		function alertas($tabla=null,$id_empleado=null) {
			include 'db.php'; 
			
			if($tabla=='empleados'){
				//checamos los empleados que no tienen un turno asigando correctamente
				$sql="SELECT e.id,CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno) nombreEmpleado,e.clave FROM empleados e WHERE NOT EXISTS (SELECT * FROM sucursales_horarios sh WHERE sh.id_sucursal = e.id_sucursal AND e.id_turno=sh.id_turno) AND e.id_sucursal IS NOT NULL   ";
				$result = $conexion->query($sql);
				$num=0;
				while($row=$result->fetch_assoc()){
					if($num==0){
						echo '<div style="width: 100%;text-align: left; display: table;" class="flashAzul"> ';
						echo "Empleados que tienen Turno diferente a la sucursal asignada<br>";
					}
					$num=$num+1; 
					echo '<div class="alertaDiv" onclick="edit('.$row['id'].')">'.$row['clave'].'-'.$row['nombreEmpleado'].'</div>';
				} 
				if($num !=0){
					echo '</div> ';
				}
			}
			$conexion->close();
		}
?>