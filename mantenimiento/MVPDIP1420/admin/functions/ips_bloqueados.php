<?php
		function ips_bloqueados($id=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM ips_bloqueados WHERE 1 = 1 ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}
		function ip_bloqueadoDatos($id=null,$ip=null,$status=null){
			include 'db.php';
			$sql=("SELECT * FROM ips_bloqueados WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($ip!=""){
				$sql.=" AND ip='{$ip}' ";
			}
			if($status!=""){
				$sql.=" AND status='{$status}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}
		function ips_bloqueadosDatos($id=null,$ip=null,$status=null){
			include 'db.php';
			$sql="SELECT * FROM ips_bloqueados WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($ip!=""){
				$sql.=" AND ip='{$ip}' ";
			}
			if($status!=""){
				$sql.=" AND status='{$status}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function ip_bloqueadoInsert($ip=null,$fechaH=null){
			include 'db.php';
			$ip= mysqli_real_escape_string($conexion,$ip);
			$_POST["ip_bloqueado"][0]['fechaR']=$fechaH; 
			$_POST["ip_bloqueado"][0]['status']=1;
			$_POST["ip_bloqueado"][0]['ip']=$ip;
			$_POST["ip_bloqueado"][0]['observaciones']='IP Sospechosa Automatico';
			$fields_pdo = "`".implode('`,`', array_keys($_POST['ip_bloqueado'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['ip_bloqueado'][0])."'";
			$inset_ips_bloqueados= "INSERT INTO ips_bloqueados ($fields_pdo) VALUES ($values_pdo);";
			$inset_ips_bloqueados=$conexion->query($inset_ips_bloqueados);
			$num=$conexion->affected_rows;
			$success=true;
			$conexion->autocommit(FALSE);
			if(!$inset_ips_bloqueados || $num=0){
				$success=false;
				var_dump($conexion->error);
				return "ERROR inset_ips_bloqueados"; 
			}
			$id=$_POST['ip_bloqueado'][0]['id_ip_bloqueado']=$conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['ip_bloqueado'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['ip_bloqueado'][0])."'";
			$inset_ips_bloqueados_historicos= "INSERT INTO ips_bloqueados_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_ips_bloqueados_historicos=$conexion->query($inset_ips_bloqueados_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_ips_bloqueados_historicos || $num=0){
				$success=false;
				var_dump($conexion->error);
				return "ERROR inset_ips_bloqueados_historicos"; 
			}
			if($success){
				return "SI";
				$conexion->commit();
				$conexion->close();
			}else{
				return "NO";
				$conexion->rollback();
				$conexion->close();
			}
		}


?>