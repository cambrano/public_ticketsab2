<style>
			/* Contenedor del semáforo en forma horizontal */
			.semaforo {
				display: flex;
				justify-content: center;
				align-items: center;
				gap: 10px; /* Espacio entre círculos */
				background-color: #333;
				padding: 10px;
				border-radius: 10px;
				width: max-content;
				margin: 0 auto;
			}
			/* Estilos para cada círculo reducido */
			.circulo {
				width: 50px;
				height: 50px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 10px;
				font-weight: bold;
				color: white;
			}
			.verde {
				background-color: green;
			}
			.amarillo {
				background-color: yellow;
				color: black;
			}
			.rojo {
				background-color: red;
			}
		</style>
		<div class="sucForm50" style="text-align:center">
			<label class="labelForm" id="labeltemaname" >Semaforo Coalción</label>
			<br>
			<div class="semaforo">
				<div class="circulo verde">50<br>>=</div>
				<div class="circulo amarillo">45<br>>=</div>
				<div class="circulo rojo">0.1<br>>=</div>
			</div>
		</div>
		<div class="sucForm50" style="text-align:center">
			<label class="labelForm" id="labeltemaname">Semaforo Individual</label>
			<br>
			<div class="semaforo">
				<div class="circulo verde">40<br>>=</div>
				<div class="circulo amarillo">30<br>>=</div>
				<div class="circulo rojo">0.1<br>>=</div>
			</div>
		</div>