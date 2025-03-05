<?php
		@session_start();
		include "../functions/error.php"; 
		
		include "../functions/security.php"; 
		include "functions/security.php"; 

		include "../functions/timemex.php"; 
		include "functions/timemex.php";

		 

		include "../functions/usuarios_ciudadanos.php"; 
		include "functions/usuarios_ciudadanos.php";  

	?> 
	<title>Dashboard</title>
	<style type="text/css">
		.moduloAzulBlack{ 
			/*background-color: #f2dede;*/ 
			/*color: #c24d42;*/
			border-style: solid; 
			border-width: 1px;
			/*border-color: #ebccd1;*/ 
			text-transform: uppercase;
			letter-spacing: 5px;
			font-weight: 10px;
			font-size: 12px;
			color: #a1bddb;
			background-color: #0099CC;
			border-color: #ffffff;
			font-family: 'Avenir Next';
		}
		
		.moduloAzulBlack .moduloTitulo{
			 
			padding: 10px;
			text-align: center;
			letter-spacing: 5px;
			/*line-height:5px; */
			background-color: #397cb5;
			color: #fff;
			font-family: 'Avenir Next';
		}
		.moduloSColor{ 
				/*background-color: #f2dede;*/ 
				/*color: #c24d42;*/
				border-style: solid; 
				border-width: 1px;
				/*border-color: #ebccd1;*/ 
				text-transform: uppercase;
				letter-spacing: 5px;
				font-weight: 10px;
				font-size: 12px;
				color: #a1bddb; 
				border-color: #fafbfd;
				font-family: 'Avenir Next';
			}
		.moduloSColor .moduloTitulo{
			 
			padding: 10px;
			text-align: center;
			letter-spacing: 5px;
			line-height:5px; 
			font-family: 'Avenir Next';
		}


		.moduloAmarillo{ 
			/*background-color: #f2dede;*/ 
			/*color: #c24d42;*/
			border-style: solid; 
			border-width: 1px;
			/*border-color: #ebccd1;*/ 
			text-transform: uppercase;
			letter-spacing: 5px;
			font-weight: 10px;
			font-size: 12px;
			background-color: #f9c33d;
			border-color: #f9c33d;
			font-family: 'Avenir Next';
		}
		
		.moduloAmarillo .moduloTitulo{
			 
			padding: 10px;
			text-align: center;
			letter-spacing: 5px;
			line-height:5px; 
			background-color: #f39c12;
			color: #fff;
			font-family: 'Avenir Next';
		}

		.moduloAmarillo hr{
			width: 90%;
			border-top: 1px solid #a1bddb;
			padding: 0px;
			font-size: 3px;
			margin :10px;
			font-family: 'Avenir Next';
		}

		.moduloAzul{ 
			/*background-color: #f2dede;*/ 
			/*color: #c24d42;*/
			border-style: solid; 
			border-width: 1px;
			/*border-color: #ebccd1;*/ 
			text-transform: uppercase;
			letter-spacing: 5px;
			font-weight: 10px;
			font-size: 12px;
			color: #a1bddb;
			background-color: #578EBE;
			border-color: #ffffff;
			font-family: 'Avenir Next';
		}
		
		.moduloAzul .moduloTitulo{
			 
			padding: 10px;
			text-align: center;
			letter-spacing: 5px;
			line-height:5px; 
			background-color: #397cb5;
			color: #fff;
			font-family: 'Avenir Next';
		}

		.moduloAzul hr{
			width: 90%;
			border-top: 1px solid #a1bddb;
			padding: 0px;
			font-size: 3px;
			margin :10px;
			font-family: 'Avenir Next';
		}

		.moduloRojo{ 
			/*background-color: #f2dede;*/ 
			/*color: #c24d42;*/
			border-style: solid; 
			border-width: 1px;
			/*border-color: #ebccd1;*/ 
			text-transform: uppercase;
			letter-spacing: 5px;
			font-weight: 10px;
			font-size: 12px;
			color: #a1bddb;
			background-color: #e54b4a;
			border-color: #ffffff;
			font-family: 'Avenir Next';
		}
		.moduloRojo .moduloTitulo{
			 
			padding: 10px;
			text-align: center;
			letter-spacing: 5px;
			line-height:5px; 
			background-color: #ca1f1e;
			color: #fff;
			font-family: 'Avenir Next';
		}

		.moduloRojo hr{
			width: 90%;
			border-top: 1px solid #e39c9c;
			padding: 0px;
			font-size: 3px;
			margin :10px;
			font-family: 'Avenir Next';
		}

		.moduloVerde{ 
			/*background-color: #f2dede;*/ 
			/*color: #c24d42;*/
			border-style: solid; 
			border-width: 1px;
			/*border-color: #ebccd1;*/ 
			text-transform: uppercase;
			letter-spacing: 5px;
			font-weight: 10px;
			font-size: 12px;
			color: #a1bddb;
			background-color: #44b6ae;
			border-color: #ffffff;
			font-family: 'Avenir Next';
		}
		.moduloVerde .moduloTitulo{
			 
			padding: 10px;
			text-align: center;
			letter-spacing: 5px;
			line-height:5px; 
			background-color: #13a096;
			color: #fff;
			font-family: 'Avenir Next';
		}

		.moduloVerde hr{
			width: 90%;
			border-top: 1px solid #50cdc4;
			padding: 0px;
			font-size: 3px;
			margin :10px;
			font-family: 'Avenir Next';
		}

		.moduloMorado{ 
			/*background-color: #f2dede;*/ 
			/*color: #c24d42;*/
			border-style: solid; 
			border-width: 1px;
			/*border-color: #ebccd1;*/ 
			text-transform: uppercase;
			letter-spacing: 5px;
			font-weight: 10px;
			font-size: 12px;
			color: #a1bddb;
			background-color: #8775a7;
			border-color: #ffffff;
			font-family: 'Avenir Next';
		}
		.moduloMorado .moduloTitulo{
			 
			padding: 10px;
			text-align: center;
			letter-spacing: 5px;
			line-height:5px; 
			background-color: #6c4da3;
			color: #fff;
			font-family: 'Avenir Next';
		}

		.moduloMorado hr{
			width: 90%;
			border-top: 1px solid #ab8fde;
			padding: 0px;
			font-size: 3px;
			margin :10px;
			font-family: 'Avenir Next';
		}
		.moduloBlanco{ 
			/*background-color: #f2dede;*/ 
			/*color: #c24d42;*/
			border-style: solid; 
			border-width: 1px;
			/*border-color: #ebccd1;*/ 
			text-transform: uppercase;
			letter-spacing: 5px;
			font-weight: 10px;
			font-size: 12px;
			color: black;
			background-color: white;
			border-color: #ffffff;
			font-family: 'Avenir Next';
			width: 100%;
		}
		.moduloBlanco .moduloTitulo{
			 
			padding: 10px;
			text-align: left;
			letter-spacing: 5px; 
			color: black;
			font-size: 8px;
			font-weight: bold;
			font-family: 'Avenir Next';
		}

		.moduloBlanco hr{
			width: 90%;
			border-top: 1px solid #ab8fde;
			padding: 0px;
			font-size: 3px;
			margin :10px;
			font-family: 'Avenir Next';
		}

		.moduloBlanco .fontSubBody{
			padding: 15px; 
			font-size: 8px;
			color: black;
			text-shadow: 1px 1px 1px #a1bddb;
			font-family: 'Avenir Next';
		}



		.moduloBody{
			 
			padding: 5px;
			text-align: left;
			letter-spacing: 5px;  
			border:none;
			font-size: 9px;
			color: white;
			text-shadow: 1px 1px 1px #a1bddb;
			font-family: 'Avenir Next'; 
			display: table; 
			width: 100%
		}
		.moduloBodyScroll{
			 
			padding: 5px;
			text-align: left;
			letter-spacing: 5px;  
			border:none;
			font-size: 9px;
			color: white;
			text-shadow: 1px 1px 1px #a1bddb;
			font-family: 'Avenir Next';  
			width: 100%
		}
		.moduloBodyNormal{
			 
			padding: 5px;
			text-align: left;
			letter-spacing: 5px;  
			border:none;
			font-size: 9px;  
			font-family: 'Avenir Next'; 
			display: table; 
			width: 100%
		}
		.fontBody {
			 
			padding: 1px; 
			font-size: 18px;
			color: white;
			text-shadow: 1px 1px 1px #a1bddb;
			font-family: 'Avenir Next';
		}
		.fontBody15 {
			 
			padding: 1px; 
			font-size: 15px;
			color: white;
			text-shadow: 1px 1px 1px #a1bddb;
			font-family: 'Avenir Next';
		}
		.fontSubBody {
			 
			padding: 1px; 
			font-size: 8px;
			color: white;
			text-shadow: 1px 1px 1px #a1bddb;
			font-family: 'Avenir Next';
		}
		
		.div25{
			width: 25%;
			float: left;
			padding: 5px
		}
		.div37_5{
			width: 37.5%;
			float: left;
			padding: 5px
		}
		.div100{
			width: 100%;
			float: left;
			padding: 5px
		}
		.div50{
			width: 50%;
			float: left;
			padding: 5px
		}
		.div75{
			width: 75%;  
			padding: 5px ;
			float: left;

		}
		.div60Block{
			width: 60%; 
			display: table;
			padding: 5px ;
			text-align: left;
		}
		.div55Block{
			width: 55%; 
			display: inline-block;
			padding: 5px ;
			text-align: left;
		}
		.div45Block{
			width: 45%; 
			display: inline-block;
			padding: 5px ;
			text-align: left;
		}

		.div100Block{
			width: 100%; 
			display: inline-block;
			padding: 5px ;
			text-align: left;
		}

		.div50Block{
			width: 50%; 
			display: inline-block;
			padding: 5px ;
			text-align: left;
		}


		.div40{
			width: 40%;
			float: left;
			padding: 5px
		}

		.grafica{
			height:620px; 
			width:95vw;
		}

		@media only screen and (max-width:1230px) {
		/* For mobile phones: */
			.div25,.div37_5,.div100,.div100Block,.div50,.div50Block,.div75,.div60Block,.div55Block,.div40,.div45Block{
				width: 100%
			}
			.grafica{
				height:20px; 
				width:55vw;
			}
			.moduloAmarillo .moduloTitulo,.moduloVerde .moduloTitulo,.moduloMorado .moduloTitulo,.moduloAzul .moduloTitulo,.moduloRojo .moduloTitulo,.moduloBlanco.moduloTitulo{
				line-height:14px; 
			}
			.moduloSColor{
				display: none;
			}
		}
	</style>
	<div class="bodymanager" id="bodymanager" style="display: inline-block;"> 
		<div  class ="div100">
			<div style="clear: both;"></div> 
			<div class="moduloAzulBlack" style="display: block;">
				<div class="moduloTitulo" >
					Bienvenido : <?php $usuarioWeb=usuario_ciudadanosDatos($_COOKIE["id_usuario"],''); echo $usuarioWeb['nombre_usuario'] ?>
				</div>
			</div>
		</div>
	</div>