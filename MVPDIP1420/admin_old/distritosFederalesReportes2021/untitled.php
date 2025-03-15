<?php
			if(empty($datos_distritos_federales[$row['id']]['orden_votos_individual']['primera_fuerza'])){
				$datos_distritos_federales[$row['id']]['segunda_fuerza'] = $datos_distritos_federales[$row['id']]['primera_fuerza'] = array(
					'id' => 'NoData',
					'clave' => 'No Data',
					'nombre_corto' => 'No Data',
					'principal' => 0,
					'logo' => 'no_data.png',
					'color_border' => '',
					'color_background' => '',
					'votos_individual' => 0,
					'coaliciones' => '',
					'votos_coaliciones' => 0,
					'votos_totales' => 0,
				);
			}