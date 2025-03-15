<?php
	@session_start();

	$color1 = "#004c00";
	$color1_hover = "#4c814c";

	$color2 = "#ffcb01";
	$color2_hover = "#ffda4d";

	$color3 = "#ff0000";
	$color3_hover = "#ff4c4c";

?>
	<center>
		<canvas id="chartTotalesVotos" height="13px" width="43px" style="min-width: 150px; max-width: 100%;"></canvas>
	</center>
	<script type = "text/javascript" >
		 new Chart(document.getElementById("chartTotalesVotos"), {
				type: 'line',
				data: {
				labels: [18,19,'20 - 24','25 - 29','30 - 34','35 - 39','40 - 44','45 - 49','50 - 54','55 - 59','60 - 64','65 Más'],
				datasets: [{ 
					data: [
						<?= $totales_18_hombres ?>,
						<?= $totales_19_hombres ?>,
						<?= $totales_20_24_hombres ?>,
						<?= $totales_25_29_hombres ?>,
						<?= $totales_30_34_hombres ?>,
						<?= $totales_35_39_hombres ?>,
						<?= $totales_40_44_hombres ?>,
						<?= $totales_45_49_hombres ?>,
						<?= $totales_50_54_hombres ?>,
						<?= $totales_55_59_hombres ?>,
						<?= $totales_60_64_hombres ?>,
						<?= $totales_65_mas_hombres ?>,
						],
					label: "Hombres",
					borderColor: "#0000ff",
					fill: true,
					backgroundColor: "rgba(0, 0, 255, 0.2)",
					titleFontSize: 10,
					titleFontColor: '#fff',
					bodyFontColor: '#fff',
					bodyFontSize: 10,
					displayColors: true,
					borderWidth:1,
            		borderStyle:'dash'//has no effect

				  }, { 
					data: [
					<?= $totales_18_mujeres ?>,
					<?= $totales_19_mujeres ?>,
					<?= $totales_20_24_mujeres ?>,
					<?= $totales_25_29_mujeres ?>,
					<?= $totales_30_34_mujeres ?>,
					<?= $totales_35_39_mujeres ?>,
					<?= $totales_40_44_mujeres ?>,
					<?= $totales_45_49_mujeres ?>,
					<?= $totales_50_54_mujeres ?>,
					<?= $totales_55_59_mujeres ?>,
					<?= $totales_60_64_mujeres ?>,
					<?= $totales_65_mas_mujeres ?>,
					],
					label: "Mujeres",
					borderColor: "#e71837",
					fill: true,
					backgroundColor: "rgba(231, 24, 55, 0.2)",
					titleFontSize: 10,
					titleFontColor: '#fff',
					bodyFontColor: '#fff',
					bodyFontSize: 10,
					displayColors: true,
					borderWidth:1,
            		borderStyle:'dash'//has no effect

				  }
				]
			  },

			  
			  options: {
			  	scales: {
			      yAxes: [
			        {
			          ticks: {
			            callback: function(valor, index, valores) {
			              return Number(valor).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			            }
			          },
			          scaleLabel: {
			            display: true,
			            labelString: 'Ciudadanos'
			          }
			        }
			      ],
			      xAxes: [
			        {
			          scaleLabel: {
			            display: true,
			            labelString: 'Edades'
			          }
			        }
			      ]
			    },
				title: {
				  display: true,
				  text: 'Información Edades'
				}
			  }
			});
	</script>