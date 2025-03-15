<?php
	@session_start();
?>
	<div style="text-align: left;padding: 0px 0px 0px 5px;font-size: 8px;color: black;margin-bottom: 0px;width: 49%;float: left;">
		Lista Nominal: <b><?=number_format($total_lista_nominal_2016, 0, '.', ','); ?></b>
		<br>
		Reg. Ciudadanos: <b><?=number_format($ciudadanos_totales, 0, '.', ','); ?></b>
		<br>
		Porcentaje: <b><?=number_format( $ciudadanos_lista_nominal_porcentaje , 2, '.', ''); ?>%</b>
	</div>

	<div class="grafica_barras_horizontales">
		<canvas id="chartTotalesListaNominal"></canvas>
	</div>
	<script type="text/javascript">
		var ctx = document.getElementById("chartTotalesListaNominal").getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'horizontalBar',
			data: {
				labels : ["L.Nominal","Ciudadanos"],
				datasets: [
					 {
						label: "Total",
						steppedLine: 'before',
						data : ['<?= $total_lista_nominal_2016 ?>','<?= $ciudadanos_totales ?>'], 
						backgroundColor: [
							'#008b66',
							'#36a2eb',
						],
						borderColor: [
							'#008b66',
							'#36a2eb',
						],
						hoverBackgroundColor: [
							"#006147",
							"#2571a4",
						],
						borderWidth: 1
					},
					/*
					{
						fillColor : "rgba(151,187,205,0.5)",
						strokeColor : "rgba(151,187,205,1)",
						data : [2822,2248]
					}
					*/
				]
			},
			options: {
				maintainAspectRatio: false,
				responsive: true,
				legend: {
					display: false,
					responsive: true,
				},
				tooltips: {
					enabled: true,
					callbacks: {
						label: function(tooltipItem, data) {
							return Number(tooltipItem.xLabel).toFixed(0).replace(/./g, function(c, i, a) {
								return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
							});
						}
					},
					backgroundColor: 'rgba(0, 0, 0, 0.8)',
					titleFontSize: 10,
					titleFontColor: '#fff',
					bodyFontColor: '#fff',
					bodyFontSize: 10,
					displayColors: true
				},
				scales: {
					xAxes: [{
						id: 'y-axis-0',
						gridLines: {
							display: true,
							lineWidth: .2,
							color: "rgba(255,22,223,122.30)"
						},
						ticks: {
							fontSize: 8,
							beginAtZero:true,
							mirror:true,
							suggestedMin: 0,
							suggestedMax:50 ,//sugerimos el tipo
							/*
							callback: function(value, index, values) {
									return   Number(value).toFixed(0).replace(/./g, function(c, i, a) {
											return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
												});
							},
							*/
						},
						afterBuildTicks: function(chart) {}
					}],
					yAxes: [{
						scaleFontSize: 10,
						id: 'x-axis-0',
						gridLines: {
							display: false,
							lineWidth: .2,
							color: "rgba(255,22,223,122.30)"
						},
						ticks: {
							beginAtZero: false,
							fontSize: 8,
						}
					}]
				}
			}
		});
	</script>
	<br><br>