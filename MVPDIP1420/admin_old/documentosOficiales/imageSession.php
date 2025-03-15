<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/genid.php";
	if(!empty($_POST)){
		
		//var_dump($_FILES['imagen']["tmp_name"]);
		//var_dump($_POST['alt']);
		if($_POST['num']==""){
			//echo "1/";
			//echo "-";
			$num=$_SESSION['image_num'];
			//echo "-";
			if($num==""){
				//echo "1.1/";
				$num=0;
				$_SESSION['image_num']=$num;
			}
			if($num>=0){
				//echo "1.2/";
				$num=$num+1;
				$_SESSION['image_num']=$num;
			}
			$tipo_imagen=$_POST['tipo_imagen'];
			$name = $_FILES['imagen']['name']; 
			$type = $_FILES['imagen']['type']; 
			$file_size = $_FILES['imagen']['size']; 
			$image = file_get_contents($_FILES['imagen']['tmp_name']);
			$name_cod=$cod32.".png";
			$id_categoria_imagenes_hoteles=$_POST['id_categoria_imagenes_hoteles'];
			$url=$_POST['url'];
			// display in view
			//echo '<img src="data:image/jpeg;base64,'.base64_encode( $image ).'"/>';
			$_SESSION['image'][$num]= array(
				'name' => $name,
				'imagePrint'=>$image ,
				'tipo_imagen'=>$tipo_imagen,
				'status'=>'1',
				'file_name'=>$name_cod,
				'type'=>$type,
				'id'=>"",
				'file_size'=>$file_size,
			);
		}else{
			//editamos o eliminamos
			//echo "2/";
			$update=$_POST['update'];
			$tipo_imagen=$_POST['tipo_imagen'];
			$num=$_POST['num'];
			if($update ==""){
				$_SESSION['image'][$num]['status']=0;
			}else{
				if($_FILES['imagen']['tmp_name'] != ""){
					$tipo_imagen=$_POST['tipo_imagen'];
					$name = $_FILES['imagen']['name']; 
					$type = $_FILES['imagen']['type']; 
					$file_size = $_FILES['imagen']['size']; 
					$image = file_get_contents($_FILES['imagen']['tmp_name']);
					$name_cod=$cod32.".png";

					$_SESSION['image'][$num]['name'] = $name;
					$_SESSION['image'][$num]['imagePrint'] = $image;
					$_SESSION['image'][$num]['tipo_imagen'] = $tipo_imagen;
					$_SESSION['image'][$num]['file_name'] = $name_cod;
					$_SESSION['image'][$num]['type'] = $type;
					$_SESSION['image'][$num]['file_size'] = $file_size;
					$_SESSION['image'][$num]['status'] = 1;
				}else{
					$tipo_imagen=$_POST['tipo_imagen'];
					$_SESSION['image'][$num]['tipo_imagen'] = $tipo_imagen;
					$_SESSION['image'][$num]['status'] = 1;

				}
			}
		}

	}
	//echo "<pre>";
	//print_r($_SESSION['image']);
	//echo "</pre>";
?>
<script type="text/javascript">
	$(document).ready(function() {
		var dataTable = $('#imagen-tabla').DataTable( {
			"responsive": true,
			"ordering": false,
			"pageLength": 11,
			"retrieve": true,
			"info": false,
			"processing": true,
			"searching": false,
			"paging": false,
			"sPaginationType": "full_numbers",
			"order": [[ 0, "desc" ]],
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
<table id="imagen-tabla"   class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
	<thead>
		<tr>
			<th>Tipo</th> 
			<th>Imagen</th>
			<th>Opción</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($_SESSION['image'] as $key => $value) {
			if($value['status']==1){
				echo "<tr>";
				echo "<td style='font-size:8px'>".$value['tipo_imagen']."</td>"; 
				echo '<td> <img src="data:image/jpeg;base64,'.base64_encode( $value['imagePrint'] ).'" height="120px" /></td>';
				echo '<td> <input type="button" id="sumbmitImage" style="float: left;" onclick="editarImage('.$key.')" value="Editar">  <input type="button" id="sumbmitImage" style="float: left;" onclick="eliminarImage('.$key.')" value="Borrar"></td>';
				echo "</tr>";
			}
		}

		?>
	</tbody>
</table>