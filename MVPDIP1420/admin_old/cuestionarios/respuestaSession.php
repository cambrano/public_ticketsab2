<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/genid.php";


	if(!empty($_POST)){
		
		//var_dump($_FILES['respuestan']["tmp_name"]);
		//var_dump($_POST['alt']);
		if($_POST['respuesta'][0]['num']==""){
			//echo "1/";
			//echo "-";
			$num=$_SESSION['respuesta_num'];
			//echo "-";
			if($num==""){
				//echo "1.1/";
				$num=0;
				$_SESSION['respuesta_num']=$num;
			}
			if($num>=0){
				//echo "1.2/";
				$num=$num+1;
				$_SESSION['respuesta_num']=$num;
			}
			$orden=$_POST['respuesta'][0]['orden'];
			$clave=$_POST['respuesta'][0]['clave'];
			$respuesta=$_POST['respuesta'][0]['respuesta'];

			$_SESSION['respuesta'][$num]= array(
				'orden' => $orden,
				'clave'=>$clave ,
				'respuesta'=>$respuesta,
				'status'=>'1',
				'id'=>"",
			); 
		}else{
			//editamos o eliminamos
			//echo "2/";
			$update=$_POST['respuesta'][0]['update'];
			$num=$_POST['respuesta'][0]['num'];
			$clave=$_POST['respuesta'][0]['clave'];
			$orden=$_POST['respuesta'][0]['orden'];
			$respuesta=$_POST['respuesta'][0]['respuesta'];
			if($update ==""){
				$_SESSION['respuesta'][$num]['status']=0;
			}else{
				$_SESSION['respuesta'][$num]['orden'] = $orden;
				$_SESSION['respuesta'][$num]['clave'] = $clave;
				$_SESSION['respuesta'][$num]['respuesta'] = $respuesta;
				$_SESSION['respuesta'][$num]['status'] = 1;
			}
		}

	}
	//echo "<pre>";
	//print_r($_SESSION['respuesta']);
	//echo "</pre>";
?>
<script type="text/javascript">
	$(document).ready(function() {
		var dataTable = $('#respuestan-tabla').DataTable( {
			"responsive": true,
			"ordering": false,
			"pageLength": 11,
			"retrieve": true,
			"info": false,
			"processing": true,
			"searching": false,
			"paging": false,
			"sPaginationType": "full_numbers",
			"order": [[ 0, "asc" ]],
			"fixedHeader": true,
			"fixedHeader": {
				header: true,
			},
			"aoColumnDefs": [
							{ "bSortable": false, "aTargets": [ 2 ] }
							],
			"serverSide": false,
			"scrollY": "100%", 
			"scrollX": "100%",

			"language": {
				"sProcessing":     "Procesando...",
				//"sLengthMenu":     "Mostrar _MENU_ registros",
				"sLengthMenu": ' ',
				"sSearch":         "Buscar:",
				"sZeroRecords":    "Registro no encontrados",
				"sEmptyTable":     "No Existe Registros",
				"sInfo":           "Mostrar  (_START_ a _END_) de _TOTAL_ Registros",//
				"sInfoEmpty":      "Mostrando Registros del 0 al 0 de Total de 0 Registros",//
				"sInfoFiltered":   "(Filtrado de _MAX_ Total Registros)",//
				//"sInfoPostFix":    "",
				//"sUrl":            "",
				//"sInfoThousands":  ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst":    "<<",
					"sLast":     ">>",
					"sNext":     ">",
					"sPrevious": "<"
				},
				"oAria": {
					"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				},
			},
		});
	});
</script>
<table id="respuestan-tabla"   class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
	<thead>
		<tr>
			<th>Orden</th> 
			<th>Clave</th> 
			<th>Respuesta</th>
			<th>Opción</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($_SESSION['respuesta'] as $key => $value) {
			if($value['status']==1){
				echo "<tr>";
				echo "<td style='font-size:8px'>".$value['orden']."</td>"; 
				echo "<td style='font-size:8px'>".$value['clave']."</td>"; 
				echo "<td style='font-size:8px'>".$value['respuesta']."</td>"; 
				echo '<td> <input type="button" id="sumbmitRespuesta" style="float: left;" onclick="editarRespuestaNumero('.$key.')" value="Editar">  <input type="button" id="sumbmitRespuesta" style="float: left;" onclick="eliminarRespuesta('.$key.')" value="Borrar"></td>';
				echo "</tr>";
			}
		}

		?>
	</tbody>
</table>