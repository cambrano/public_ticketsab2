<?php
	include __DIR__.'/../functions/security.php';
	@session_start();
?>
	<script type='text/javascript'>
		function searchTable(){
			var whatsapp = document.getElementById('whatsapp').value;
			var searchTable = [];
			var data = {
				'whatsapp' : whatsapp,
			}
			searchTable.push(data);
			$.ajax({
				type: 'POST',
				url: 'apiWhatsappMensajes/table.php',
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$('#dataTable').html(data);
				}
			});
		}
	</script>
	<div class='sucForm'>
		<label class='labelForm' id='labeltemaname'>Whatsapp</label><br>
		<input data-column='1' id='whatsapp' autocomplete='off' type='text' onkeyup='searchTable();' > <br>
	</div>
	<style type='text/css'>
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple {
			box-shadow: 0 0 10px #c5c5f2;
			-webkit-box-shadow: 0 0 10px #c5c5f2;
			-moz-box-shadow: 0 0 10px #c5c5f2;
			border: 1px solid #DDDDDD;
			width: 100%;
		}
		input[type=text] {
			height: 38px;
		}
		.select2-container--default .select2-selection--single {
			background-color: #fff;
			border: 1px solid #aaa;
			border-radius: 4px;
			height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 32px;
			position: absolute;
			top: 1px;
			right: 1px;
			width: 20px;
		}
		.bs-actionsbox .btn-group button {
			width: 48%;
			font-size: 12px;
		}
	</style>