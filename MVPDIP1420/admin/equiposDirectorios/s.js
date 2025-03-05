// Rellenar los campos del formulario con los datos de la fila seleccionada
			$('#id_equipo_sistema_operativo_licencia').val(data[0]);
			$('#sistema_operativo_licencia_id_equipo').val(data[1]);
			$('#id_sistema_operativo').val(data[2]).trigger('change'); 
			$('#sistema_operativo_licencia_fecha_inicial').val(data[4]);
			$('#sistema_operativo_licencia_fecha_final').val(data[5]);
			$('#sistema_operativo_licencia_serial').val(data[6]);
			$('#sistema_operativo_licencia_vigencia').val(data[7]).trigger('change'); 
			$('#sistema_operativo_licencia_observaciones').val(data[8]);

			// Elimina la fila actual para que pueda ser reemplazada después de modificar
			dataTableSistemasOperativos.row(row).remove().draw();