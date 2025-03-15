$key;
					//este es el tipo de dato
					$value;
					//validaciones
					if(in_array("fecha", $value)){
						if($line_valor!=""){
							if(!validarFecha($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$tipo_error[]=str_replace('_',' ',$key);
								$error_view = "ERROR";
							}
						}
					}
					if(in_array("telefono", $value)){
						if($line_valor!=""){
							if(!validarTelefono($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$tipo_error[]=str_replace('_',' ',$key);
								$error_view = "ERROR";
							}
						}
					}
					if(in_array("correo_electronico", $value)){
						if($line_valor!=""){
							if(!validarCorreoElectronico($line_valor)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$tipo_error[]=str_replace('_',' ',$key);
								$error_view = "ERROR";
							}
						}
					}
					if(in_array("requerido", $value)){
						if($line_valor==""){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$requerido_error[]=str_replace('_',' ',$key);
							$error_view = "ERROR";
						}
					}

					if(in_array("mayuscula", $value)){
						$line_valor=strtoupper($line_valor);
					}
					if(in_array("status", $value)){
						if($line_valor==1 || strtolower($line_valor)=='x'){
							$line_valor=strtolower($line_valor);
						}else{
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$tipo_error[]=str_replace('_',' ',$key);
							$error_view = "ERROR";
						}
					}
					if(in_array("unique", $value)){
						//var_dump($unique[$key]);
						if(empty($unique[$key])){
							$unique[$key][]=$line_valor;
						}else{
							if(in_array($line_valor, $unique[$key])){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$unique_error[]=$line_valor;
								$error_view = "ERROR";
							}
							$unique[$key][]=$line_valor;
						}
					}
					if(in_array("unique_db", $value)){
						$relacionado[] = $line_valor;
						//claveValidadorSistema($line_valor,$tabla);
						if(claveValidadorSistema($line_valor,$tabla)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$unique_db_error[]=$line_valor;
							$error_view = "ERROR";
						}
					}

					if(in_array("relacionado", $value)){
						claveValidadorSistema($line_valor,$tabla);
						if($line_valor !="" ){
							if(!claveValidadorSistema($line_valor,$tb)){
								if (in_array($line_valor, $relacionado)) {
								}else{
									$color[$num][$key]='background-color: #d9534f;color:white';
									$error=$unique_db_error[]=$line_valor;
									$error_view = "ERROR";
								}
								
							}
						}
					}


					if(in_array("mayuscula", $value)){
						$line_valor=strtoupper($line_valor);
					}
					if(in_array("status", $value)){
						if($line_valor==1 || strtolower($line_valor)=='x'){
							$line_valor=strtolower($line_valor);
						}else{
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$tipo_error[]=str_replace('_',' ',$key);
							$error_view = "ERROR";
						}
					}

					if(in_array("opcional_1", $value)){
						if($line_valor!=""){
							$requerido=1;
						}else{
							$requerido=0;
							$num_requerido=$num;
							$key_requerido=$key;
						}
					}

					if(in_array("opcional_2", $value)){
						if($line_valor=="" && $requerido==1){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.$key);
							$error_view = "ERROR";
						}
						if($line_valor=="" && $requerido==0){
							//no hace nada
							$gps=true;
						}
						if($line_valor!="" && $requerido==0){
							$color[$num_requerido][$key_requerido]='background-color: #d9534f;color:white';
							$error=$requerido_error[]=str_replace('_',' ',$clave_base.' - '.$key);
							$error_view = "ERROR";
							$gps=false;
						}
						if($line_valor!="" && $requerido==1){
							$gps=false;
						}
					}

					if(in_array("buscar_clave", $value)){
						//claveValidadorSistema($line_valor,$tabla);
						$tb=str_replace("clave_","",$key);

						if($buscar_clave[$tb][$line_valor]==""){
							if(!claveValidadorSistema($line_valor,$tb)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$unique_db_error_no_encontrado[]=$line_valor;
								$buscar_clave[$tb][$line_valor]="Error";
								$error_view = "ERROR";
							}else{
								$buscar_clave[$tb][$line_valor]=1;
							}
						}else{
							if($buscar_clave[$tb][$line_valor]=="Error"){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$unique_db_error_no_encontrado[]=$line_valor;
								$error_view = "ERROR";
							}
						}
					}

					if(in_array("unique_db", $value)){
						//claveValidadorSistema($line_valor,$tabla);
						if(claveValidadorSistema($line_valor,$tabla)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$unique_db_error[]=$line_valor;
							$error_view = "ERROR";
						}
					}
					if(in_array("unique_db_usuario", $value)){
						//claveValidadorSistema($line_valor,$tabla);
						if(usuarioValidadorSistema($line_valor)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$unique_db_error[]=$line_valor;
							$error_view = "ERROR";
						}
					}

					/*
					if($key=='pais'){
						$paisId=paisId($line_valor);
						if(empty($paisId)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$encontrado_error[]="pais";
						}
					}



					if($key=='estado'){
						if($line_valor=="México" || $line_valor=="méxico" ){
							$estadoId=estadoId($line_valor,1);
							if(empty($estadoId)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$encontrado_error[]="estado";
							}
						}else{
							$estadoId=estadoId($line_valor,"");
							if(empty($estadoId)){
								$color[$num][$key]='background-color: #d9534f;color:white';
								$error=$encontrado_error[]="estado";
							}
						}
						
					}*/

					$value['id_pais']=141;
					unset($value['pais']);
					//$value['id_estado']=$id_estado;
					$estadoId = $id_estado;
					unset($value['estado']);

					if($key=='municipio'){
						$municipioId=municipioId($line_valor,$estadoId);
						if(empty($municipioId)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$encontrado_error[]="municipio";
							$error_view = "ERROR";
						}
					}
					if($key=='localidad'){
						$localidadId=localidadId($line_valor,$estadoId,$municipioId);
						if(empty($localidadId)){
							$color[$num][$key]='background-color: #d9534f;color:white';
							$error=$encontrado_error[]="localidad";
							$error_view = "ERROR";
						}
					}
					if($key=='tipo'){
						$line_valor=strtolower($line_valor);
					}

					if($key=='sexo'){
						$line_valor = ucfirst(strtolower($line_valor));
					}

					if($key=='error'){
						$line_valor = $error_view;
					}