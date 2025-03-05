<?php
		function tipoTablas(){
			$arrayTablas = array(
				'Configuración'=>array(
					'configuracion'=>'Configuración Inicial',
					'claves'=>'Claves',
					'claves_2'=>'Claves 2',
					'administradores_sistema'=>'Administradores',
					'empleados'=>'Empleados',
				),

				'Perfiles'=>array(
					'tipos_actividades'=>'Tipos Actividades',
					'redes_sociales'=>'Redes Sociales',
					'servidores_correos'=>'Servidores Correos',

					'identidades'=>'identidades',
					'correos_electronicos'=>'Correos Electrónicos',
					'cuentas_redes_sociales'=>'Cuentas Redes Sociales',
					'cuentas_redes_sociales_actividades'=>'Actividades',
				),

				'Cartografía'=>array(
					'distritos_locales'=>'Distritos Locales',
					'distritos_locales_parametros'=>'Distritos Locales Polígonos',
					'distritos_federales'=>'Distritos Federales',
					'distritos_federales_parametros'=>'Distritos Federales Polígonos',
					'cuarteles'=>'Cuareles',
					'secciones_ine'=>'Secciones INE',
					'secciones_ine_parametros'=>'Secciones INE Polígonos',
				),

				'Electoral'=>array(
					'tipos_casillas'=>'Tipos Casillas',
					'partidos_2016'=>'Partidos Pasados 2016',
					'casillas_votos_2016'=>'Casillas Pasadas 2016',
					'partidos_2018'=>'Partidos Pasados 2018',
					'casillas_votos_2018'=>'Casillas Pasadas 2018',
					'casillas_votos_partidos_2018'=>'Votos Pasadas 2018',
					'partidos_2021'=>'Partidos 2021',
					'casillas_votos_2021'=>'Casillas 2021',
					'casillas_votos_partidos_2021'=>'Votos 2021',
					'partidos_2024'=>'Partidos 2024',
					'casillas_votos_2024'=>'Casillas 2024',
					'casillas_votos_partidos_2024'=>'Votos 2024',
				),

				'Ciudadanos'=>array(
					'tipos_ciudadanos'=>'Tipos Ciudadanos',
					'tipos_categorias_ciudadanos'=>'Tipos Categorías',
					'secciones_ine_ciudadanos'=>'Ciudadanos',
					'secciones_ine_ciudadanos_categorias'=>'Ciudadanos Categorías', 
				),

				'Encuestas'=>array(
					'encuestas'=>'Encuestas',
					'cuestionarios'=>'Cuestionarios',
					'cuestionarios_respuestas'=>'Respuestas',
					'secciones_ine_ciudadanos_encuestas'=>'Ciudadanos Encuestas',
					'secciones_ine_ciudadanos_encuestas_respuestas'=>'Ciudadanos Respuestas',
				),

				'Social'=>array(
					'tipos_territorios'=>'Tipos Territorios',
					'categorias_programas_apoyos'=>'Categorías Programas Apoyos',
					'dependencias'=>'Dependencias',
					'supervisores'=>'Supervisores',
					'empresas_adjudicadas'=>'Empresas Adjudicadas',
					'programas_apoyos'=>'Programas Apoyos',
					'programas_apoyos_territorios'=>'Programas Apoyos Territorios',
					'programas_apoyos_categorias'=>'Programas Apoyos Categorías',
					'programas_apoyos_dependencias'=>'Programas Apoyos Dependencias',
					'secciones_ine_ciudadanos_programas_apoyos'=>'Ciudadanos Programas Apoyos',
					'secciones_ine_actividades'=>'Acciones y Obras',
					'secciones_ine_giras'=>'Giras',

				),
				'Grupos'=>array(
					'partidos_legados'=>'Partidos Legados',
					'militantes_partidos'=>'Militantes Partidos',
					'secciones_ine_grupos'=>'Grupo de Interes',
					'secciones_ine_ciudadanos_grupos'=>'Grupos Miembros',
				),

				'Consultas Ciudadanas'=>array(
					'preguntas_2022_revocacion_mandato'=>'Preguntas 22 Revocación Mandato',
					'casillas_votos_2022_revocacion_mandato'=>'Casillas 22 Revocación Mandato',
					'casillas_preguntas_2022_revocacion_mandato'=>'Votos 22 Revocación Mandato',
				),

				
			);

			//'empleados_turnos', 
			$return .="<option  value='' >Seleccione</option> ";
			$num=0;
			
			foreach ($arrayTablas as $key => $value) {
				$nombre=str_replace("_"," ",$value);
				$return .="<optgroup label='".ucwords($key)."' data-max-options='2'>";
				foreach ($value as $keyT => $valueT) {
					$num=$num+1;
					$return .="<option  value='".$keyT."' >".$num.' - '.ucwords(str_replace("_", " ", $valueT))."</option> ";
				}
				$return .="</optgroup>";
			}
			return $return;
		}

		function importData($id,$tabla){
			include 'db.php'; 
			$sql=("SELECT * FROM {$tabla} WHERE id='$id'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row;
		}
?>