<?php
	if(!empty($_POST) && $_POST['datos_validador'][0]['sub']==1 && !empty($_POST['datos_validador']) ){
		include __DIR__.'/../functions/security.php'; 
		include __DIR__.'/../functions/db.php'; 
		include __DIR__.'/../functions/secciones_ine_ciudadanos.php'; 
		@session_start();
		echo date("H:i:s");

		$sql = "SELECT id,nombre FROM tipos_ciudadanos;";
		$resultado = $conexion->query($sql);
		//var_dump($resultado->num_rows);
		while($row=$resultado->fetch_assoc()){
			$_tipo_nivel[$row['id']] = $row['nombre'];
		}

		$estructuras['1'] = array(1,2,3,4,5);
		$estructuras_metas['1'][1] = 1;
		$estructuras_metas['1'][2] = 600;
		$estructuras_metas['1'][3] = 600;
		$estructuras_metas['1'][4] = 600;
		$estructuras_metas['1'][5] = 600;

		foreach ($estructuras as $id => $niveles) {
			$error = false;
			$id_seccion_ine_ciudadano1 = $id;
			//! Nivel 1
			$sql1 = "SELECT 
						ea.id,
						ea.clave,
						ea.nombre_completo,
						ea.nombre,
						ea.apellido_paterno,
						ea.apellido_materno,
						ea.whatsapp,
						ea.folio,
						ea.id_tipo_ciudadano,
						(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = ea.id_tipo_ciudadano ) tipo_ciudadano ,
						id_seccion_ine_ciudadano_compartido,
						(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='Insert' LIMIT 1 ) usuario_insert,
						(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='UPDATE' LIMIT 1  ) usuario_update
						FROM secciones_ine_ciudadanos ea 
						WHERE ea.id = '{$id_seccion_ine_ciudadano1}' ;
					";
			$resultado1 = $conexion->query($sql1);
			$row1=$resultado1->fetch_assoc();
			$nivel1_nombre_completo=$row1['nombre_completo'];
			$nivel1_id_tipo_ciudadano=$row1['id_tipo_ciudadano'];
			$nivel1_tipo=$row1['tipo_ciudadano'];
			$id_seccion_ine_ciudadano2 = $nivel1_id=$row1['id'];
			$nivel1_clave=$row1['clave'];
			$nivel1_folio=$row1['folio'];
			if($nivel1_id_tipo_ciudadano != $niveles[0] ){
				$error = true;
				echo "<table border=1>";
				echo "<tr>";
				echo "<td>id1</td><td>clave".$id."</td><td>folio</td><td>tipo_reg</td><td>tipo_real</td><td>nombre_completo</td><td>INSERT</td><td>UPDATE</td>";
				echo "</tr>";
				echo "<tr><td>".$nivel1_id."</td><td>".$nivel1_clave."</td><td>".$nivel1_folio."</td><td>".$nivel1_tipo."</td><td>".$_tipo_nivel[$niveles[0]]."</td><td>".$nivel1_nombre_completo."</td><td>".$row1['usuario_insert']."</td><td>".$row1['usuario_update']."</td></tr>";
				echo "</table><br>";
			}else{
				//! Nivel 2
				$sql2 = "SELECT 
							ea.id,
							ea.clave,
							ea.nombre_completo,
							ea.nombre,
							ea.apellido_paterno,
							ea.apellido_materno,
							ea.whatsapp,
							ea.folio,
							ea.id_tipo_ciudadano,
							(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = ea.id_tipo_ciudadano ) tipo_ciudadano ,
							id_seccion_ine_ciudadano_compartido,
							(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='Insert' LIMIT 1 ) usuario_insert,
							(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='UPDATE' LIMIT 1  ) usuario_update
						FROM secciones_ine_ciudadanos ea 
						WHERE ea.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano2}'";
					$resultado2 = $conexion->query($sql2);
					while($row2=$resultado2->fetch_assoc()){
						$nivel2_nombre_completo=$row2['nombre_completo'];
						$nivel2_id_tipo_ciudadano=$row2['id_tipo_ciudadano'];
						$nivel2_tipo=$row2['tipo_ciudadano'];
						$id_seccion_ine_ciudadano3 = $nivel2_id=$row2['id'];
						$nivel2_clave=$row2['clave'];
						$nivel2_folio=$row2['folio'];
						if($nivel2_id_tipo_ciudadano != $niveles[1] ){
							$error = true;
							echo "<table border=1>";
							echo "<tr>";
							echo "<td>id2</td><td>clave</td><td>folio</td><td>tipo_reg</td><td>tipo_real</td><td>nombre_completo</td><td>INSERT</td><td>UPDATE</td>";
							echo "</tr>";
							echo "<tr><td>".$nivel1_id."</td><td>".$nivel1_clave."</td><td>".$nivel1_folio."</td><td>".$nivel1_tipo."</td><td>".$_tipo_nivel[$niveles[0]]."</td><td>".$nivel1_nombre_completo."</td></tr>";
							echo "<tr><td>".$nivel2_id."</td><td>".$nivel2_clave."</td><td>".$nivel2_folio."</td><td>".$nivel2_tipo."</td><td>".$_tipo_nivel[$niveles[1]]."</td><td>".$nivel2_nombre_completo."</td><td>".$row2['usuario_insert']."</td><td>".$row2['usuario_update']."</td></tr>";
							echo "</table><br>";
						}else{
							//! Nivel 2
							$sql3 = "SELECT 
									ea.id,
									ea.clave,
									ea.nombre_completo,
									ea.nombre,
									ea.apellido_paterno,
									ea.apellido_materno,
									ea.whatsapp,
									ea.folio,
									ea.id_tipo_ciudadano,
									(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = ea.id_tipo_ciudadano ) tipo_ciudadano ,
									id_seccion_ine_ciudadano_compartido,
									(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='Insert' LIMIT 1 ) usuario_insert,
									(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='UPDATE' LIMIT 1  ) usuario_update
								FROM secciones_ine_ciudadanos ea 
								WHERE ea.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano3}'";
							$resultado3 = $conexion->query($sql3);
							while($row3=$resultado3->fetch_assoc()){
								$nivel3_nombre_completo=$row3['nombre_completo'];
								$nivel3_id_tipo_ciudadano=$row3['id_tipo_ciudadano'];
								$nivel3_tipo=$row3['tipo_ciudadano'];
								$id_seccion_ine_ciudadano4 = $nivel3_id=$row3['id'];
								$nivel3_clave=$row3['clave'];
								$nivel3_folio=$row3['folio'];
								if($nivel3_id_tipo_ciudadano != $niveles[2] ){
									$error = true;
									echo "<table border=1>";
									echo "<tr>";
									echo "<td>id3</td><td>clave</td><td>folio</td><td>tipo_reg</td><td>tipo_real</td><td>nombre_completo</td><td>INSERT</td><td>UPDATE</td>";
									echo "</tr>";
									echo "<tr><td>".$nivel1_id."</td><td>".$nivel1_clave."</td><td>".$nivel1_folio."</td><td>".$nivel1_tipo."</td><td>".$_tipo_nivel[$niveles[0]]."</td><td>".$nivel1_nombre_completo."</td></tr>";
									echo "<tr><td>".$nivel2_id."</td><td>".$nivel2_clave."</td><td>".$nivel2_folio."</td><td>".$nivel2_tipo."</td><td>".$_tipo_nivel[$niveles[1]]."</td><td>".$nivel2_nombre_completo."</td></tr>";
									echo "<tr><td>".$nivel3_id."</td><td>".$nivel3_clave."</td><td>".$nivel3_folio."</td><td>".$nivel3_tipo."</td><td>".$_tipo_nivel[$niveles[2]]."</td><td>".$nivel3_nombre_completo."</td><td>".$row3['usuario_insert']."</td><td>".$row3['usuario_update']."</td></tr>";
									echo "</table><br>";
									
								}else{
									//! Nivel 3
									$sql4 = "SELECT 
											ea.id,
											ea.clave,
											ea.nombre_completo,
											ea.nombre,
											ea.apellido_paterno,
											ea.apellido_materno,
											ea.whatsapp,
											ea.folio,
											ea.id_tipo_ciudadano,
											(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = ea.id_tipo_ciudadano ) tipo_ciudadano ,
											id_seccion_ine_ciudadano_compartido,
											(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='Insert' LIMIT 1 ) usuario_insert,
											(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='UPDATE' LIMIT 1  ) usuario_update
										FROM secciones_ine_ciudadanos ea 
										WHERE ea.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano4}'";
									$resultado4 = $conexion->query($sql4);
									while($row4=$resultado4->fetch_assoc()){
										$nivel4_nombre_completo=$row4['nombre_completo'];
										$nivel4_id_tipo_ciudadano=$row4['id_tipo_ciudadano'];
										$nivel4_tipo=$row4['tipo_ciudadano'];
										$id_seccion_ine_ciudadano5 = $nivel4_id=$row4['id'];
										$nivel4_clave=$row4['clave'];
										$nivel4_folio=$row4['folio'];
										if($nivel4_id_tipo_ciudadano != $niveles[3] ){
											$error = true;
											echo "<table border=1>";
											echo "<tr>";
											echo "<td>id4</td><td>clave</td><td>folio</td><td>tipo_reg</td><td>tipo_real</td><td>nombre_completo</td><td>INSERT</td><td>UPDATE</td>";
											echo "</tr>";
											echo "<tr><td>".$nivel1_id."</td><td>".$nivel1_clave."</td><td>".$nivel1_folio."</td><td>".$nivel1_tipo."</td><td>".$_tipo_nivel[$niveles[0]]."</td><td>".$nivel1_nombre_completo."</td></tr>";
											echo "<tr><td>".$nivel2_id."</td><td>".$nivel2_clave."</td><td>".$nivel2_folio."</td><td>".$nivel2_tipo."</td><td>".$_tipo_nivel[$niveles[1]]."</td><td>".$nivel2_nombre_completo."</td></tr>";
											echo "<tr><td>".$nivel3_id."</td><td>".$nivel3_clave."</td><td>".$nivel3_folio."</td><td>".$nivel3_tipo."</td><td>".$_tipo_nivel[$niveles[2]]."</td><td>".$nivel3_nombre_completo."</td></tr>";
											echo "<tr><td>".$nivel4_id."</td><td>".$nivel4_clave."</td><td>".$nivel4_folio."</td><td>".$nivel4_tipo."</td><td>".$_tipo_nivel[$niveles[3]]."</td><td>".$nivel4_nombre_completo."</td><td>".$row4['usuario_insert']."</td><td>".$row4['usuario_update']."</td></tr>";
											echo "</table><br>";
										}else{
											
											//! Nivel 4
											$sql5 = "SELECT 
													ea.id,
													ea.clave,
													ea.nombre_completo,
													ea.nombre,
													ea.apellido_paterno,
													ea.apellido_materno,
													ea.whatsapp,
													ea.folio,
													ea.id_tipo_ciudadano,
													(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = ea.id_tipo_ciudadano ) tipo_ciudadano ,
													id_seccion_ine_ciudadano_compartido,
													(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='Insert' LIMIT 1 ) usuario_insert,
													(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='UPDATE' LIMIT 1  ) usuario_update
												FROM secciones_ine_ciudadanos ea 
												WHERE ea.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano5}'";
											$resultado5 = $conexion->query($sql5);
											while($row5=$resultado5->fetch_assoc()){
												$nivel5_nombre_completo=$row5['nombre_completo'];
												$nivel5_id_tipo_ciudadano=$row5['id_tipo_ciudadano'];
												$nivel5_tipo=$row5['tipo_ciudadano'];
												$id_seccion_ine_ciudadano6 = $nivel5_id=$row5['id'];
												$nivel5_clave=$row5['clave'];
												$nivel5_folio=$row5['folio'];
												if($nivel5_id_tipo_ciudadano != $niveles[4] ){
													$error = true;
													echo "<table border=1>";
													echo "<tr>";
													echo "<td>id5</td><td>clave</td><td>folio</td><td>tipo_reg</td><td>tipo_real</td><td>nombre_completo</td><td>INSERT</td><td>UPDATE</td>";
													echo "</tr>";
													echo "<tr><td>".$nivel1_id."</td><td>".$nivel1_clave."</td><td>".$nivel1_folio."</td><td>".$nivel1_tipo."</td><td>".$_tipo_nivel[$niveles[0]]."</td><td>".$nivel1_nombre_completo."</td></tr>";
													echo "<tr><td>".$nivel2_id."</td><td>".$nivel2_clave."</td><td>".$nivel2_folio."</td><td>".$nivel2_tipo."</td><td>".$_tipo_nivel[$niveles[1]]."</td><td>".$nivel2_nombre_completo."</td></tr>";
													echo "<tr><td>".$nivel3_id."</td><td>".$nivel3_clave."</td><td>".$nivel3_folio."</td><td>".$nivel3_tipo."</td><td>".$_tipo_nivel[$niveles[2]]."</td><td>".$nivel3_nombre_completo."</td></tr>";
													echo "<tr><td>".$nivel4_id."</td><td>".$nivel4_clave."</td><td>".$nivel4_folio."</td><td>".$nivel4_tipo."</td><td>".$_tipo_nivel[$niveles[3]]."</td><td>".$nivel4_nombre_completo."</td></tr>";
													echo "<tr><td>".$nivel5_id."</td><td>".$nivel5_clave."</td><td>".$nivel5_folio."</td><td>".$nivel5_tipo."</td><td>".$_tipo_nivel[$niveles[4]]."</td><td>".$nivel5_nombre_completo."</td><td>".$row5['usuario_insert']."</td><td>".$row5['usuario_update']."</td></tr>";
													echo "</table><br>";
												}else{
													//! Nivel 5
													$sql6 = "SELECT 
															ea.id,
															ea.clave,
															ea.nombre_completo,
															ea.nombre,
															ea.apellido_paterno,
															ea.apellido_materno,
															ea.whatsapp,
															ea.folio,
															ea.id_tipo_ciudadano,
															(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = ea.id_tipo_ciudadano ) tipo_ciudadano ,
															id_seccion_ine_ciudadano_compartido,
															(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='Insert' LIMIT 1 ) usuario_insert,
															(SELECT CONCAT(lg.nombre_usuario,' - ',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='UPDATE' LIMIT 1  ) usuario_update
														FROM secciones_ine_ciudadanos ea 
														WHERE ea.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano6}'";
													$resultado6 = $conexion->query($sql6);
													while($row6=$resultado6->fetch_assoc()){
														$nivel6_nombre_completo=$row6['nombre_completo'];
														$nivel6_id_tipo_ciudadano=$row6['id_tipo_ciudadano'];
														$nivel6_tipo=$row6['tipo_ciudadano'];
														$id_seccion_ine_ciudadano7 = $nivel6_id=$row6['id'];
														$nivel6_clave=$row6['clave'];
														$nivel6_folio=$row6['folio'];
														if($nivel6_id_tipo_ciudadano != $niveles[5] ){
															$error = true;
															echo "<table border=1>";
															echo "<tr>";
															echo "<td>id6</td><td>clave</td><td>folio</td><td>tipo_reg</td><td>tipo_real</td><td>nombre_completo</td><td>INSERT</td><td>UPDATE</td>";
															echo "</tr>";
															echo "<tr><td>".$nivel1_id."</td><td>".$nivel1_clave."</td><td>".$nivel1_folio."</td><td>".$nivel1_tipo."</td><td>".$_tipo_nivel[$niveles[0]]."</td><td>".$nivel1_nombre_completo."</td></tr>";
															echo "<tr><td>".$nivel2_id."</td><td>".$nivel2_clave."</td><td>".$nivel2_folio."</td><td>".$nivel2_tipo."</td><td>".$_tipo_nivel[$niveles[1]]."</td><td>".$nivel2_nombre_completo."</td></tr>";
															echo "<tr><td>".$nivel3_id."</td><td>".$nivel3_clave."</td><td>".$nivel3_folio."</td><td>".$nivel3_tipo."</td><td>".$_tipo_nivel[$niveles[2]]."</td><td>".$nivel3_nombre_completo."</td></tr>";
															echo "<tr><td>".$nivel4_id."</td><td>".$nivel4_clave."</td><td>".$nivel4_folio."</td><td>".$nivel4_tipo."</td><td>".$_tipo_nivel[$niveles[3]]."</td><td>".$nivel4_nombre_completo."</td></tr>";
															echo "<tr><td>".$nivel5_id."</td><td>".$nivel5_clave."</td><td>".$nivel5_folio."</td><td>".$nivel5_tipo."</td><td>".$_tipo_nivel[$niveles[4]]."</td><td>".$nivel5_nombre_completo."</td></tr>";
															echo "<tr><td>".$nivel6_id."</td><td>".$nivel6_clave."</td><td>".$nivel6_folio."</td><td>".$nivel6_tipo."</td><td>".$_tipo_nivel[$niveles[5]]."</td><td>".$nivel6_nombre_completo."</td><td>".$row6['usuario_insert']."</td><td>".$row6['usuario_update']."</td></tr>";
															echo "</table><br>";
														}
													}
												}
											}
		
		
		
		
		
		
		
										}
									}
								}
							}
						}
					}
			}
			if($error == true){
				echo "<br>";
				echo "<br>";
			}
		}

	    $sql_sin_relacion = "SELECT 
	            ea.id,
	            ea.clave,
	            ea.nombre_completo,
	            ea.nombre,
	            ea.apellido_paterno,
	            ea.apellido_materno,
	            ea.whatsapp,
	            ea.folio,
	            ea.id_tipo_ciudadano,
	            (SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = ea.id_tipo_ciudadano ) tipo_ciudadano ,
	            id_seccion_ine_ciudadano_compartido,
	            (SELECT lg.nombre_usuario FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='Insert' LIMIT 1 ) usuario_insert,
	            (SELECT lg.nombre_usuario FROM log_usuarios lg WHERE lg.id_columna = ea.id AND operacion ='UPDATE' LIMIT 1  ) usuario_update
	        FROM secciones_ine_ciudadanos ea 
	        WHERE ea.id_tipo_ciudadano > 1 AND ea.id_seccion_ine_ciudadano_compartido IS NULL  ";
	    $resultado = $conexion->query($sql_sin_relacion);
	    while($row=$resultado->fetch_assoc()){
	        $nivel0_nombre_completo=$row['nombre_completo'];
	        $nivel0_id_tipo_ciudadano=$row['id_tipo_ciudadano'];
	        $nivel0_tipo=$row['tipo_ciudadano'];
	        $nivel0_clave=$row['clave'];
	        $nivel0_folio=$row['folio'];
	        $nivel0_id=$row['id'];
	        echo "<table border=1>";
	        echo "<tr>";
	        echo "<td>id6</td><td>clave</td><td>folio</td><td>tipo_reg</td><td>nombre_completo</td><td>INSERT</td><td>UPDATE</td>";
	        echo "</tr>";
	        echo "<tr><td>".$nivel0_id."</td><td>".$nivel0_clave."</td><td>".$nivel0_folio."</td><td>".$nivel0_tipo."</td><td>".$nivel0_nombre_completo."</td><td>".$row['usuario_insert']."</td><td>".$row['usuario_update']."</td></tr>";
	        echo "</table><br>";
	    }
	  
	}