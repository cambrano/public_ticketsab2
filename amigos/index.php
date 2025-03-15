<?php
    include "comp.php";
    include "functions.php";

    $url = 'https://'.$_SERVER['HTTP_HOST'];

    $municipios = municipios($conexion, '', $id_estado);
    $localidades = localidades($conexion, '', '');

    $referido = $_GET['referido'];
    // Genera un token anti-CSRF y guárdalo en la sesión
    if (!isset($_SESSION['hash_user'])) {
        $_SESSION['hash_user'] = bin2hex(random_bytes(32));
    }
    $_POST['clave_elector'] = strtoupper($_POST['clave_elector']);
    
    //! visitas
    $data['hash_user'] = $_SESSION['hash_user'];
    $data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $data['http_sec_ch_ua_platform'] = $_SERVER['HTTP_SEC_CH_UA_PLATFORM'];
    $data['http_origin'] = $_SERVER['HTTP_ORIGIN'];
    $data['server_name'] = $_SERVER['SERVER_NAME'];
    $data['script_name'] = $_SERVER['SCRIPT_NAME'];
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    $data['fecha'] = $fechaSF;
    $data['hora'] = $fechaSH;
    $data['fecha_hora'] = $fechaH;
    $data['tipo'] = 'index';
    $visitas = visitas($conexion,$data);
    $visitasConteo = visitasConteo($conexion,'50',$data['ip'],'','60',$data['fecha_hora']);
    if($visitasConteo['bloqueo'] == 1 ){
        $data['descripcion'] = 'multiples peticiones a save.php';
        $bloquearIP = bloquearIP($conexion,'5-D',$data);
        $ips_bloqueadosHtaccess = ips_bloqueadosHtaccess($conexion,'',$data['ip'],1,'');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Abierto</title>
    <link rel="shortcut icon" href="icon.png">
    <!-- Incluye los estilos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .form-label{
            color: #2c3345;
        } 
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="mb-3">
            <?php
                include "head.php";
            ?>
            <div id= "formulario">
                <div class="row">
                    <div class="mb-3">
                        <label for="referido" class="form-label"><b>Referido</b></label>
                        <input type="text" class="form-control" id="referido" value="<?= $referido ?>" placeholder="" required maxlength="120">
                        <div style="font-size:11px;color:#856404;padding-left:5px"><font><b>* Opcional</b></font></div>
                        <div id = "referido_mensaje"></div>
                        <br>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label"><b>Nombre(s)</b></label>
                        <input type="text" class="form-control" id="nombre" value="" placeholder="ejemplo : Javier" required maxlength="120">
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "nombre_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_paterno" class="form-label"><b>Apellido Paterno</b></label>
                        <input type="text" class="form-control" id="apellido_paterno" value="" placeholder="ejemplo : Perez" required maxlength="120">
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "apellido_paterno_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_materno" class="form-label"><b>Apellido Materno</b></label>
                        <input type="text" class="form-control" id="apellido_materno" value="" placeholder="ejemplo : Ocaña" required maxlength="120">
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "apellido_materno_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="ano" class="form-label"><b>Año</b></label>
                        <select class="form-control" id="ano" style="width: 100%;" onchange="validadorFecha()" required> 
                            <!--<option value = "" >Seleccione</option>-->
                            <?php
                            for ($i = 2006; $i >= 1914; $i--) {
                                ?>
                                <option value="<?= $i ?>" ><?= $i ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "ano_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="mes" class="form-label"><b>Mes</b></label>
                        <select class="form-control" id="mes" style="width: 100%;" onchange="validadorFecha()" required> 
                            <!--<option value = "" >Seleccione</option>-->
                            <option value="01">Enero</option>
                            <option value="02">Febrero</option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Novimebre</option>
                            <option value="12">Diciembre</option>
                        </select>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = mes_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="dia" class="form-label"><b>Día</b></label>
                        <select class="form-control" id="dia" style="width: 100%;" onchange="validadorFecha()" required> 
                            <!--<option value = "" >Seleccione</option>-->
                            <?php
                            for ($i = 1; $i <= 31; $i++) {
                                ?>
                                <option value = "<?= str_pad($i,2,0,STR_PAD_LEFT) ?>" ><?= str_pad($i,2,0,STR_PAD_LEFT) ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "dia_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="clave_elector" class="form-label"><b>Clave de elector</b></label>
                        <input type="text" class="form-control" id="clave_elector" value="GTHZIS81011409M300" placeholder="ejemplo : GTHZIS81011409M300" required maxlength="18">
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "clave_elector_mensaje"></div>
                    </div>

                    <div class="mb-3" style="padding:20px 0px 10px 20px;border: 1px solid rgba(0, 0, 0, 0.2);background-color: #e5e7f2;box-shadow: 0 1px 0 rgba(255,255,255,0.5) inset, 0 1px 0 rgba(0,0,0,0.2);color: #2c3345;">
                        <label for="telefono" class="form-label"><b>Datos de contacto</b></label>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label"><b>Número de contacto o de Whatsapp</b></label>
                        <input type="text" class="form-control" id="telefono" value="" placeholder="ejemplo : 9995781545" required>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "telefono_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="correo_electronico" class="form-label"><b>Correo Electrónico</b></label>
                        <input type="text" class="form-control" id="correo_electronico" value="" placeholder="ejemplo : ejemplo@gmail.com" required>
                        <div style="font-size:11px;color:#856404;padding-left:5px"><font><b>* Opcional</b></font></div>
                        <div id = "correo_electronico_mensaje"></div>
                    </div>

                    <div class="mb-3" style="padding:20px 0px 10px 20px;border: 1px solid rgba(0, 0, 0, 0.2);background-color: #e5e7f2;box-shadow: 0 1px 0 rgba(255,255,255,0.5) inset, 0 1px 0 rgba(0,0,0,0.2);color: #2c3345;">
                        <label for="telefono" class="form-label"><b>Dirección actual</b></label>
                    </div>
                    <div class="mb-3">
                        <label for="id_municipio" class="form-label"><b>Municipio</b></label>
                        <select class="form-control" id="id_municipio" style="width: 100%;" onchange="locationMunicipio(this.value)" required> 
                            <?php
                            echo $municipios;
                            ?>
                        </select>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "id_municipio_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="id_localidad" class="form-label"><b>Localidad</b></label>
                        <select class="form-control" id="id_localidad" style="width: 100%;" onchange="locationLocalidad(this.value)" required> 
                            <?php
                            //echo $localidades;
                            ?>
                            <option value="">Seleccione</option>
                        </select>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "id_localidad_mensaje"></div>
                    </div> 
                    <div class="mb-3">
                        <label for="calle" class="form-label"><b>Calle</b></label>
                        <input type="text" class="form-control" id="calle" value="" placeholder="ejemplo : C. 84" required>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "calle_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="num_ext" class="form-label"><b>No. Ext.</b></label>
                        <input type="text" class="form-control" id="num_ext" value="" placeholder="ejemplo : 84A" required>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "num_ext_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="num_int" class="form-label"><b>No. Int.</b></label>
                        <input type="text" class="form-control" id="num_int" value="" placeholder="ejemplo : 8">
                        <div style="font-size:11px;color:#856404;padding-left:5px"><font><b>* Opcional</b></font></div>
                        <div id = "num_int_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="colonia" class="form-label"><b>Colonia</b></label>
                        <input type="text" class="form-control" id="colonia" value="" placeholder="ejemplo : San Roque II" required>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "colonia_mensaje"></div>
                    </div>
                    <div class="mb-3">
                        <label for="codigo_postal" class="form-label"><b>Código Postal</b></label>
                        <input type="text" class="form-control" id="codigo_postal" value="" placeholder="ejemplo : 55001" required>
                        <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                        <div id = "codigo_postal_mensaje"></div>
                    </div>

                    <div style="background-color:#e9e9e9;padding:10px;margin:10px 0px 10px 0px">
                        <div class="col-lg-12">
                            <div id = "imagen_mensaje"></div>
                        </div>
                        <div class="mb-3">
                            <label for="foto_ine_front" class="form-label">Foto INE parte frontal</label>
                            <input type="file" class="form-control" id="foto_ine_front" name="foto_ine_front" accept="image/*" required placeholder="Seleccionar INE" >
                            <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                            <img class="preview-image-ine mt-2" id="previewfoto_ine_front" src="ine_sample/Credencial-Anverso.jpg" alt="Vista previa." style="width: 50%;">
                        </div>
                        <div class="mb-3">
                            <label for="foto_ine_back" class="form-label">Foto INE parte trasera</label>
                            <input type="file" class="form-control" id="foto_ine_back" name="foto_ine_back" accept="image/*" required placeholder="Seleccionar INE" >
                            <div style="font-size:11px;color:red;padding-left:5px"><font><b>* Requerido</b></font></div>
                            <img class="preview-image-ine mt-2" id="previewfoto_ine_back" src="ine_sample/Credencial-Reverso.jpg" alt="Vista previa." style="width: 50%;">
                        </div>

                        <div class="d-grid gap-2 d-md-block" id="btn_submit">
                            <button type="button" onclick="guardar()" id="sumbmit" class="btn btn-primary btn-lg">
                                <i class="fas fa-vote-yea"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-lg-12 text-center" id="mensaje"></div>
        <div id="info_referido" class="container mt-5" style="display:none">
            <div class="input-group">
                <div class="form-control" id="link_referido" contenteditable="false" placeholder="Link referido">
                    <a id="link_href" href="" target="_blank" ></a>
                </div>
                <div class="input-group-append">
                    <button class="btn btn-warning" type="button" onclick="copiarTexto()">Copiar enlace</button>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="load" style="display:none">
            <center>
                <img class="preview-image-ine mt-2"  src="img/service-single.gif" style="width:100%" >
            </center>
        </div>
        <div class="col-lg-12" id="load_file" style="display:none">
            <center>
                <img class="preview-image-ine mt-2"  src="img/load_file.gif" style="width:auto" >
            </center>
        </div>
        <div class="accordion" id="miAcordeon">
            <div class="accordion-item">
            <h2 class="accordion-header" id="seccion1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contenido1" aria-expanded="false" aria-controls="contenido1">
                    Aviso de privacidad
                </button>
            </h2>
            <div id="contenido1" class="accordion-collapse collapse" aria-labelledby="seccion1" data-bs-parent="#miAcordeon">
                <div class="accordion-body">
                <p>El presente Aviso de Privacidad establece los términos y condiciones en virtud de los cuales el Formulario recolecta y trata datos personales, según lo previsto en la Ley General de Transparencia y Acceso a la Información Pública, Ley General de Protección de Datos Personales en Posesión de Sujetos Obligados y la Ley General de Partidos Políticos.</p>
                <p>Los datos que serán recolectados a través del presente formulario son los siguientes:</p>
                <ul>
                    <li>Nombre completo</li>
                    <li>Fecha y lugar de nacimiento</li>
                    <li>Domicilio completo</li>
                    <li>Clave de elector</li>
                    <li>Municipio y localidad</li>
                    <li>Correo electrónico</li>
                    <li>Número telefónico</li>
                    <li>Fecha de nacimiento</li>
                    <li>Fotografías</li>
                    <li>Cookies</li>
                </ul>

                <p>Los datos personales recolectados serán utilizados para las siguientes finalidades:</p>

                <ul>
                    <li>Identificar y contactar a los simpatizantes del partido.</li>
                    <li>Enviar información sobre las actividades del partido.</li>
                    <li>Invitar a participar en eventos del partido.</li>
                    <li>Realizar encuestas y sondeos de opinión.</li>
                </ul>

                <p>Los titulares de los datos personales tienen los siguientes derechos:</p>

                <ul>
                    <li>Acceder a sus datos personales.</li>
                    <li>Rectificar sus datos personales.</li>
                    <li>Cancelar sus datos personales.</li>
                    <li>Oponerse al tratamiento de sus datos personales.</li>
                    <li>Revocar su consentimiento para el tratamiento de sus datos personales.</li>
                </ul>

                <p>Los datos personales recolectados podrán ser transferidos a terceros, en los siguientes casos:</p>

                <ul>
                    <li>A empresas o personas contratadas por el partido para el procesamiento de datos.</li>
                    <li>A personas o entidades que requieran acceder a los datos personales para cumplir con una obligación legal.</li>
                </ul>

                <p>Para conocer los terceros a los que se les transfieren los datos personales, los titulares de los datos pueden contactarse con el partido a través de los medios mencionados anteriormente.</p>

                <p>El [nombre del partido] implementará las medidas de seguridad necesarias para proteger los datos personales de los titulares, de acuerdo con los estándares establecidos por la Ley General de Protección de Datos Personales en Posesión de Sujetos Obligados.</p>

                <p>El partido se reserva el derecho de modificar el presente Aviso de Privacidad en cualquier momento, en cuyo caso se publicará la versión actualizada en su sitio web.</p>

                <p>Al proporcionar sus datos personales a través del presente formulario, usted acepta los términos y condiciones establecidos en el presente Aviso de Privacidad
                </div>
            </div>
            </div>
        </div>


    </div>

    <!-- Incluye jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Incluye los scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="script.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.0.6/compressor.min.js"></script>
    <!-- Select --->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    

    <script type="text/javascript">
        function locationMunicipio(valor){
            id_municipio = valor;
            var id_municipio = id_municipio.replace(/^\s+|\s+$/g, "");
            if(id_municipio == ""){
                $("#id_localidad").html("<option value=''>Seleccione</option>");
                $("#id_localidad_mensaje").html("");
                // Cambiar el color de fondo a white
                $("#id_localidad_mensaje").css("background-color", "white");
                // Cambiar el color del texto a blanco
                $("#id_localidad_mensaje").css("color", "white");
                // Aplicar el relleno de 0px
                $("#id_localidad_mensaje").css("padding", "0px");
                $("#mensaje").html("");
                document.getElementById("id_municipio").focus(); 
                $("#id_municipio_mensaje").html("Municipio requerido");
                // Cambiar el color de fondo a rojo
                $("#id_municipio_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#id_municipio_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#id_municipio_mensaje").css("padding", "10px");
                document.getElementById("load_file").style.display = "none";
                return false;
            }else{
                $("#id_municipio_mensaje").html("");
                // Cambiar el color de fondo a white
                $("#id_municipio_mensaje").css("background-color", "white");
                // Cambiar el color del texto a blanco
                $("#id_municipio_mensaje").css("color", "white");
                // Aplicar el relleno de 0px
                $("#id_municipio_mensaje").css("padding", "0px");
                $("#id_localidad_mensaje").html("");
                // Cambiar el color de fondo a white
                $("#id_localidad_mensaje").css("background-color", "white");
                // Cambiar el color del texto a blanco
                $("#id_localidad_mensaje").css("color", "white");
                // Aplicar el relleno de 0px
                $("#id_localidad_mensaje").css("padding", "0px");
                var dataString = 'tipo=select&id_municipio='+id_municipio;
                $.ajax({
					type: "POST",
					url: "localidades.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
            }
        }

        function locationLocalidad(valor){
            id_localidad = valor;
            if(id_localidad == ""){
                $("#mensaje").html("");
                document.getElementById("id_localidad").focus(); 
                $("#id_localidad_mensaje").html("Localidad requerido");
                // Cambiar el color de fondo a rojo
                $("#id_localidad_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#id_localidad_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#id_localidad_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }else{
                $("#id_localidad_mensaje").html("");
                // Cambiar el color de fondo a white
                $("#id_localidad_mensaje").css("background-color", "white");
                // Cambiar el color del texto a blanco
                $("#id_localidad_mensaje").css("color", "white");
                // Aplicar el relleno de 0px
                $("#id_localidad_mensaje").css("padding", "0px");
            }
        }

        // Función para previsualizar imágenes antes de enviar el formulario
        function previsualizarImagen(input, preview, maxSize) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var image = new Image();
                    image.src = e.target.result;

                    // Comprime y ajusta el tamaño de la imagen antes de mostrarla
                    image.onload = function () {
                        var width = image.width;
                        var height = image.height;
                        var ratio = width / height;
                        if (width > maxSize) {
                            width = maxSize;
                            height = width / ratio;
                        }
                        var canvas = document.createElement("canvas");
                        canvas.width = width;
                        canvas.height = height;
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(image, 0, 0, width, height);
                        var dataURL = canvas.toDataURL("image/webp", 0.8);

                        // Actualiza la vista previa con la imagen comprimida
                        $(preview).attr('src', dataURL);
                    };
                };
                reader.readAsDataURL(input.files[0]);
            }
        }


        // Función para validar que los archivos sean de tipo imagen
        function validarTipoDeArchivo(input) {
            var allowedTypes = ["image/png", "image/jpeg", "image/jpg", "image/gif"];
            if (input.files.length > 0 && allowedTypes.indexOf(input.files[0].type) === -1) {
                alert("Por favor, asegúrese de que el archivo sea una imagen válida (png, jpg, jpeg, gif).");
                $(input).val(""); // Limpiar el campo de entrada
            }
        }

        // Llamar a las funciones de previsualización y validación al cambiar el archivo
        $("#foto_ine_front").change(function () {
            previsualizarImagen(this, "#previewfoto_ine_front");
            validarTipoDeArchivo(this);
        });

        $("#foto_ine_back").change(function () {
            previsualizarImagen(this, "#previewfoto_ine_back");
            validarTipoDeArchivo(this);
        });
        function validarClaveElector(clave) {
            // Expresión regular para validar la clave de elector
            var regex = /^[A-Z]{6}\d{8}[A-Z]\d{3}$/;
            return regex.test(clave);
        }

        function guardar() {
            document.getElementById("sumbmit").disabled = true;
            document.getElementById("load_file").style.display = "block";
            var espacios_invalidos= /\s+/g;
            let inputs_name = [
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'ano',
                'mes',
                'dia',
                'clave_elector',
                'telefono',
                'correo_electronico',
                'id_municipio',
                'id_localidad',
                'calle',
                'num_ext',
                'num_int',
                'colonia',
                'codigo_postal',
            ];
            // Usar forEach para iterar sobre el array
            inputs_name.forEach(function(input) {
                // Construir el identificador del mensaje usando el contenido del array
                let mensajeId = "#" + input + "_mensaje";

                // Limpiar el contenido del mensaje
                $(mensajeId).html("");

                // Cambiar el color de fondo a blanco
                $(mensajeId).css("background-color", "white");

                // Cambiar el color del texto a blanco
                $(mensajeId).css("color", "white");

                // Aplicar el relleno de 0px
                $(mensajeId).css("padding", "0px");
            });
            // Aplicar el relleno de 10px
            $("#mensaje").css("padding", "10px");
            // Agregamos el contenido del mensaje
            $("#mensaje").html("Espere por su información esta siendo procesada...");
            // Cambiar el color del texto
            $("#mensaje").css("background-color", "#ffc000");
            // Limpiar el contenido del mensaje
            $("#imagen_mensaje").html("");
            // Cambiar el color de fondo a white
            $("#imagen_mensaje").css("background-color", "white");
            // Cambiar el color del texto a blanco
            $("#imagen_mensaje").css("color", "white");
            // Aplicar el relleno de 0px
            $("#imagen_mensaje").css("padding", "0px");
            
            var referido = document.getElementById("referido").value;
            var nombre = document.getElementById("nombre").value;
            nombrex = nombre.replace(espacios_invalidos, '').toUpperCase();
            if(nombrex == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("nombre").focus(); 
                $("#nombre_mensaje").html("Nombre requerido.");
                // Cambiar el color de fondo a rojo
                $("#nombre_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#nombre_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#nombre_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            if (nombrex.length > 120) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("nombre").focus();
                $("#nombre_mensaje").html("El nombre debe tener menos de 120 caracteres.");
                // Cambiar el color de fondo a rojo
                $("#nombre_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#nombre_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#nombre_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var apellido_paterno = document.getElementById("apellido_paterno").value;
            apellido_paternox = apellido_paterno.replace(espacios_invalidos, '').toUpperCase();
            if(apellido_paternox == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("apellido_paterno").focus(); 
                $("#apellido_paterno_mensaje").html("Apellido paterno requerido.");
                // Cambiar el color de fondo a rojo
                $("#apellido_paterno_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#apellido_paterno_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#apellido_paterno_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            if (apellido_paternox.length > 120) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("apellido_paterno").focus();
                $("#apellido_paterno_mensaje").html("El apellido paterno debe tener menos de 120 caracteres.");
                // Cambiar el color de fondo a rojo
                $("#apellido_paterno_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#apellido_paterno_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#apellido_paterno_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var apellido_materno = document.getElementById("apellido_materno").value;
            apellido_maternox = apellido_materno.replace(espacios_invalidos, '').toUpperCase();
            if(apellido_maternox == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("apellido_materno").focus(); 
                $("#apellido_materno_mensaje").html("Apellido materno requerido.");
                // Cambiar el color de fondo a rojo
                $("#apellido_materno_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#apellido_materno_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#apellido_materno_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            if (apellido_maternox.length > 120) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("apellido_materno").focus();
                $("#apellido_materno_mensaje").html("El apellido materno debe tener menos de 120 caracteres.");
                // Cambiar el color de fondo a rojo
                $("#apellido_materno_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#apellido_materno_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#apellido_materno_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var ano = document.getElementById("ano").value;
            anox = ano.replace(espacios_invalidos, ''); 
            if(anox == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("ano").focus(); 
                $("#ano_mensaje").html("Año requerido.");
                // Cambiar el color de fondo a rojo
                $("#ano_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#ano_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#ano_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            var mes = document.getElementById("mes").value;
            mesx = mes.replace(espacios_invalidos, ''); 
            if(mesx == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("mes").focus(); 
                $("#mes_mensaje").html("Mes requerido.");
                // Cambiar el color de fondo a rojo
                $("#mes_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#mes_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#mes_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            var dia = document.getElementById("dia").value;
            diax = dia.replace(espacios_invalidos, ''); 
            if(diax == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("dia").focus(); 
                $("#dia_mensaje").html("Día requerido.");
                // Cambiar el color de fondo a rojo
                $("#dia_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#dia_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#dia_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            // Crear una instancia de fecha
            var fecha = new Date(ano, mes - 1, dia);
            // Verificar si la fecha es válida
            if (
                fecha.getFullYear() == ano &&
                fecha.getMonth() == mes - 1 &&
                fecha.getDate() == dia
            ) {
                //alert("Fecha válida");
            } else {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("ano").focus(); 
                $("#ano_mensaje").html("Año Invalido.");
                // Cambiar el color de fondo a rojo
                $("#ano_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#ano_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#ano_mensaje").css("padding", "10px");

                document.getElementById("mes").focus(); 
                $("#mes_mensaje").html("Mes Invalido.");
                // Cambiar el color de fondo a rojo
                $("#mes_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#mes_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#mes_mensaje").css("padding", "10px");

                document.getElementById("dia").focus(); 
                $("#dia_mensaje").html("Día Invalido.");
                // Cambiar el color de fondo a rojo
                $("#dia_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#dia_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#dia_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
            }
            var fecha_nacimiento = ano + '-' + mes + '-' + dia;

            var clave_elector = document.getElementById("clave_elector").value;
            clave_electorx = clave_elector.replace(espacios_invalidos, '').toUpperCase();
            if(clave_electorx == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("clave_elector").focus(); 
                $("#clave_elector_mensaje").html("Clave de Elector requerido.");
                // Cambiar el color de fondo a rojo
                $("#clave_elector_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#clave_elector_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#clave_elector_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            if (clave_electorx.length != 18) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("clave_elector").focus();
                $("#clave_elector_mensaje").html("La Clave de Elector debe tener 18 caracteres.");
                // Cambiar el color de fondo a rojo
                $("#clave_elector_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#clave_elector_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#clave_elector_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            if (!validarClaveElector(clave_electorx)) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                var estructuraRegex = "Estructura de la Clave de Elector esperada: ABCDEF - 123456 - 78 - G -789<br>";
                var estructuraUsuario = "Estructura de la Clave de Elector ingresada por el usuario:<br>";
                estructuraUsuario += "Primeros 6 caracteres: " + clave_electorx.substring(0, 6) + "<br>";
                estructuraUsuario += "Siguientes 6 dígitos: " + clave_electorx.substring(6, 12) + "<br>";
                estructuraUsuario += "Siguientes 2 dígitos: " + clave_electorx.substring(12, 14) + "<br>";
                estructuraUsuario += "Carácter siguiente: " + clave_electorx.substring(14, 15) + "<br>";
                estructuraUsuario += "Últimos 3 dígitos: " + clave_electorx.substring(15, 18) + "<br>";
                document.getElementById("clave_elector").focus();
                $("#clave_elector_mensaje").html("Clave de Elector inválida. " + estructuraRegex + estructuraUsuario + ".");
                // Cambiar el color de fondo a rojo
                $("#clave_elector_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#clave_elector_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#clave_elector_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var telefono = document.getElementById("telefono").value; 
            telefonox = telefono.replace(espacios_invalidos, ''); 
            if(telefonox == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("telefono").focus(); 
                $("#telefono_mensaje").html("Número de contacto o de whatsapp requerido.");
                // Cambiar el color de fondo a rojo
                $("#telefono_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#telefono_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#telefono_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var correo_electronico = document.getElementById("correo_electronico").value; 
            correo_electronicox = correo_electronico.replace(espacios_invalidos, ''); 
            if(correo_electronicox!=''){
                if(!validarEmail(correo_electronicox)){
                    $("#mensaje").html("");
                    $("#mensaje").css("padding", "0px");
					document.getElementById("correo_electronico").focus();
					$("#correo_electronico_mensaje").html("El correo electrónico debe ser valido.");
                    // Cambiar el color de fondo a rojo
                    $("#correo_electronico_mensaje").css("background-color", "red");
                    // Cambiar el color del texto a blanco
                    $("#correo_electronico_mensaje").css("color", "white");
                    // Aplicar el relleno de 10px
                    $("#correo_electronico_mensaje").css("padding", "10px");
                    document.getElementById("sumbmit").disabled = false;
                    document.getElementById("load_file").style.display = "none";
                    return false;
				}
            }

            var id_municipio = document.getElementById("id_municipio").value; 
            id_municipiox = id_municipio.replace(espacios_invalidos, ''); 
            if(id_municipiox == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("id_municipio").focus(); 
                $("#id_municipio_mensaje").html("Municipio requerido.");
                // Cambiar el color de fondo a rojo
                $("#id_municipio_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#id_municipio_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#id_municipio_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var id_localidad = document.getElementById("id_localidad").value; 
            id_localidadx = id_localidad.replace(espacios_invalidos, ''); 
            if(id_localidadx == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("id_localidad").focus(); 
                $("#id_localidad_mensaje").html("Localidad requerido.");
                // Cambiar el color de fondo a rojo
                $("#id_localidad_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#id_localidad_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#id_localidad_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var calle = document.getElementById("calle").value; 
            callex = calle.replace(espacios_invalidos, ''); 
            if(callex == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("calle").focus(); 
                $("#calle_mensaje").html("Calle requerido.");
                // Cambiar el color de fondo a rojo
                $("#calle_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#calle_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#calle_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var num_ext = document.getElementById("num_ext").value; 
            num_extx = num_ext.replace(espacios_invalidos, ''); 
            if(num_extx == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("num_ext").focus(); 
                $("#num_ext_mensaje").html("Número exterior requerido.");
                // Cambiar el color de fondo a rojo
                $("#num_ext_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#num_ext_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#num_ext_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var num_int = document.getElementById("num_int").value; 
            num_intx = num_int.replace(espacios_invalidos, ''); 

            var colonia = document.getElementById("colonia").value; 
            coloniax = colonia.replace(espacios_invalidos, ''); 
            if(coloniax == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("colonia").focus(); 
                $("#colonia_mensaje").html("Colonia requerido.");
                // Cambiar el color de fondo a rojo
                $("#colonia_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#colonia_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#colonia_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var codigo_postal = document.getElementById("codigo_postal").value;
            codigo_postalx = codigo_postal.replace(espacios_invalidos, ''); 
            if(codigo_postalx == ""){
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                document.getElementById("codigo_postal").focus(); 
                $("#codigo_postal_mensaje").html("Código postal requerido.");
                // Cambiar el color de fondo a rojo
                $("#codigo_postal_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#codigo_postal_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#codigo_postal_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            var foto_ine_front = $("#foto_ine_front")[0].files[0];
            var foto_ine_back = $("#foto_ine_back")[0].files[0];
            if (!foto_ine_front) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                // Hacer un desplazamiento suave hasta el elemento con ID "imagen_mensaje"
                $('html, body').animate({
                    scrollTop: $("#imagen_mensaje").offset().top
                }, 10); // Ajusta la duración de la animación según tus preferencias
                $("#imagen_mensaje").html("Ine frontal requerido.");
                // Cambiar el color de fondo a rojo
                $("#imagen_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#imagen_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#imagen_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            if (!foto_ine_back) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                // Hacer un desplazamiento suave hasta el elemento con ID "imagen_mensaje"
                $('html, body').animate({
                    scrollTop: $("#imagen_mensaje").offset().top
                }, 10); // Ajusta la duración de la animación según tus preferencias
                $("#imagen_mensaje").html("Ine trasero requerido.");
                // Cambiar el color de fondo a rojo
                $("#imagen_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#imagen_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#imagen_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            // Verificar que los archivos sean de tipo imagen (png, jpg, jpeg, gif)
            var allowedTypes = ["image/png", "image/jpeg", "image/jpg", "image/gif"];
            if (allowedTypes.indexOf(foto_ine_front.type) === -1) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                // Hacer un desplazamiento suave hasta el elemento con ID "imagen_mensaje"
                $('html, body').animate({
                    scrollTop: $("#imagen_mensaje").offset().top
                }, 10); // Ajusta la duración de la animación según tus preferencias
                $("#imagen_mensaje").html("Ine frontal valido debe ser (png, jpg, jpeg, gif).");
                // Cambiar el color de fondo a rojo
                $("#imagen_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#imagen_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#imagen_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }
            if (allowedTypes.indexOf(foto_ine_back.type) === -1) {
                $("#mensaje").html("");
                $("#mensaje").css("padding", "0px");
                // Hacer un desplazamiento suave hasta el elemento con ID "imagen_mensaje"
                $('html, body').animate({
                    scrollTop: $("#imagen_mensaje").offset().top
                }, 10); // Ajusta la duración de la animación según tus preferencias
                $("#imagen_mensaje").html("Ine trasero valido debe ser (png, jpg, jpeg, gif).");
                // Cambiar el color de fondo a rojo
                $("#imagen_mensaje").css("background-color", "red");
                // Cambiar el color del texto a blanco
                $("#imagen_mensaje").css("color", "white");
                // Aplicar el relleno de 10px
                $("#imagen_mensaje").css("padding", "10px");
                document.getElementById("sumbmit").disabled = false;
                document.getElementById("load_file").style.display = "none";
                return false;
            }

            const formData = new FormData();
            formData.append('referido', referido);
            formData.append('nombre', nombre);
            formData.append('apellido_paterno', apellido_paterno);
            formData.append('apellido_materno', apellido_materno);
            formData.append('fecha_nacimiento', fecha_nacimiento);
            formData.append('clave_elector', clave_electorx);
            formData.append('telefono', telefono);
            formData.append('correo_electronico', correo_electronicox);
            formData.append('id_municipio', id_municipio);
            formData.append('id_localidad', id_localidad);
            formData.append('calle', calle);
            formData.append('num_ext', num_ext);
            formData.append('num_int', num_int);
            formData.append('colonia', colonia);
            formData.append('codigo_postal', codigo_postal);
            formData.append('hash_user', '<?= $_SESSION['hash_user'] ?>');
                // Comprimir y agregar la primera imagen al FormData
                const compressor = new Compressor(foto_ine_front, {
                    quality: 0.6,
                    success(result) {
                        formData.append('foto_ine_front', result, foto_ine_front.name);

                        // Comprimir y agregar la segunda imagen al FormData
                        const compressor2 = new Compressor(foto_ine_back, {
                            quality: 0.6,
                            success(foto_ine_back) {
                                formData.append('foto_ine_back', foto_ine_back, foto_ine_back.name);

                                // Enviar la solicitud AJAX
                                $.ajax({
                                    url: "save.php", // Cambia esto a la URL de tu script de procesamiento
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(data){ 
                                        if(data=="SI"){
                                            $("#mensaje").html("Su registro se guardo con exito. Gracias.");
                                            $("#mensaje").css("background-color", "#ffc000");
                                            // Cambiar el color del texto a blanco
                                            $("#mensaje").css("color", "black");
                                            $("#mensaje").css("padding", "10px");
                                            $("#mensaje").css("font-size", "25px");
                                            document.getElementById("load").style.display = "block";
                                            document.getElementById("info_referido").style.display = "block";


                                            // Obtén el elemento div por su ID
                                            var divLinkReferido = document.getElementById("link_referido");

                                            // Crea un nuevo enlace si no existe
                                            var enlace = document.getElementById("link_href");
                                            if (!enlace) {
                                                enlace = document.createElement("a");
                                                enlace.id = "link_href";  // Establece el ID del enlace
                                            }

                                            url = "<?= $url ?>" + "/open/index.php?referido="+clave_electorx;
                                            // Establece el URL del enlace
                                            enlace.href = url; // Reemplaza con tu URL deseado

                                            // Establece el texto del enlace
                                            enlace.innerText = url; // Reemplaza con el texto deseado

                                            // Establece el atributo target="_blank"
                                            enlace.target = "_blank";

                                            // Agrega o actualiza el enlace dentro del div "link_referido"
                                            divLinkReferido.appendChild(enlace);

                                            document.getElementById("btn_submit").style.display = "none";
                                            document.getElementById("formulario").style.display = "none";
                                            document.getElementById("load_file").style.display = "none";
                                            document.getElementById("info_referido").style.display = "block";
                                        }else{
                                            $("#mensaje").html(data);
                                            $("#mensaje").css("background-color", "red");
                                            // Cambiar el color del texto a blanco
                                            $("#mensaje").css("color", "white");
                                            $("#mensaje").css("padding", "10px");
                                            $("#mensaje").css("font-size", "25px");
                                            document.getElementById("sumbmit").disabled = false;
                                            document.getElementById("load_file").style.display = "none";
                                        }
                                    },
                                    error: function(error){
                                        console.log(error);
                                    },
                                });
                            },
                            error(err2) {
                                console.error(err2.message);
                            },
                        });
                    },
                    error(err) {
                        console.error(err.message);
                    },
                });
        }
    function validarEmail(valor) {
		expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if ( !expr.test(valor) ){
			return false;
		}else{
			return true;
		}
	}
    $(document).ready(function() {
        $('#id_municipio').select2();
        $('#id_localidad').select2();
        $('#ano').select2();
        $('#mes').select2();
        $('#dia').select2();
        
    });
    function validadorFecha(){
        $("#mensaje").html("");
        $("#mensaje").css("padding", "0px");

        $("#ano_mensaje").html("");
        // Cambiar el color de fondo a blanco
        $("#ano_mensaje").css("background-color", "white");
        // Cambiar el color del texto a blanco
        $("#ano_mensaje").css("color", "white");
        // Aplicar el relleno de 0px
        $("#ano_mensaje").css("padding", "0px");

        $("#mes_mensaje").html("");
        // Cambiar el color de fondo a blanco
        $("#mes_mensaje").css("background-color", "white");
        // Cambiar el color del texto a blanco
        $("#mes_mensaje").css("color", "white");
        // Aplicar el relleno de 0px
        $("#mes_mensaje").css("padding", "0px");

        $("#dia_mensaje").html("");
        // Cambiar el color de fondo a blanco
        $("#dia_mensaje").css("background-color", "white");
        // Cambiar el color del texto a blanco
        $("#dia_mensaje").css("color", "white");
        // Aplicar el relleno de 0px
        $("#dia_mensaje").css("padding", "0px");

        var espacios_invalidos= /\s+/g;
        var ano = document.getElementById("ano").value;
        anox = ano.replace(espacios_invalidos, ''); 
        if(anox == ""){
            $("#mensaje").html("");
            $("#mensaje").css("padding", "0px");
            document.getElementById("ano").focus(); 
            $("#ano_mensaje").html("Año requerido.");
            // Cambiar el color de fondo a rojo
            $("#ano_mensaje").css("background-color", "red");
            // Cambiar el color del texto a blanco
            $("#ano_mensaje").css("color", "white");
            // Aplicar el relleno de 10px
            $("#ano_mensaje").css("padding", "10px");
            document.getElementById("sumbmit").disabled = false;
            document.getElementById("load_file").style.display = "none";
            return false;
        }
        var mes = document.getElementById("mes").value;
        mesx = mes.replace(espacios_invalidos, ''); 
        if(mesx == ""){
            $("#mensaje").html("");
            $("#mensaje").css("padding", "0px");
            document.getElementById("mes").focus(); 
            $("#mes_mensaje").html("Mes requerido.");
            // Cambiar el color de fondo a rojo
            $("#mes_mensaje").css("background-color", "red");
            // Cambiar el color del texto a blanco
            $("#mes_mensaje").css("color", "white");
            // Aplicar el relleno de 10px
            $("#mes_mensaje").css("padding", "10px");
            document.getElementById("sumbmit").disabled = false;
            document.getElementById("load_file").style.display = "none";
            return false;
        }
        var dia = document.getElementById("dia").value;
        diax = dia.replace(espacios_invalidos, ''); 
        if(diax == ""){
            $("#mensaje").html("");
            $("#mensaje").css("padding", "0px");
            document.getElementById("dia").focus(); 
            $("#dia_mensaje").html("Día requerido.");
            // Cambiar el color de fondo a rojo
            $("#dia_mensaje").css("background-color", "red");
            // Cambiar el color del texto a blanco
            $("#dia_mensaje").css("color", "white");
            // Aplicar el relleno de 10px
            $("#dia_mensaje").css("padding", "10px");
            document.getElementById("sumbmit").disabled = false;
            document.getElementById("load_file").style.display = "none";
            return false;
        }
        // Crear una instancia de fecha
        var fecha = new Date(ano, mes - 1, dia);
        // Verificar si la fecha es válida
        if (
            fecha.getFullYear() == ano &&
            fecha.getMonth() == mes - 1 &&
            fecha.getDate() == dia
        ) {
            //alert("Fecha válida");
        } else {
            document.getElementById("ano").focus(); 
            $("#ano_mensaje").html("Año Invalido.");
            // Cambiar el color de fondo a rojo
            $("#ano_mensaje").css("background-color", "red");
            // Cambiar el color del texto a blanco
            $("#ano_mensaje").css("color", "white");
            // Aplicar el relleno de 10px
            $("#ano_mensaje").css("padding", "10px");

            document.getElementById("mes").focus(); 
            $("#mes_mensaje").html("Mes Invalido.");
            // Cambiar el color de fondo a rojo
            $("#mes_mensaje").css("background-color", "red");
            // Cambiar el color del texto a blanco
            $("#mes_mensaje").css("color", "white");
            // Aplicar el relleno de 10px
            $("#mes_mensaje").css("padding", "10px");

            document.getElementById("dia").focus(); 
            $("#dia_mensaje").html("Día Invalido.");
            // Cambiar el color de fondo a rojo
            $("#dia_mensaje").css("background-color", "red");
            // Cambiar el color del texto a blanco
            $("#dia_mensaje").css("color", "white");
            // Aplicar el relleno de 10px
            $("#dia_mensaje").css("padding", "10px");
        }
    }
    function copiarTexto() {
        // Obtén el contenido del div
        var contendo = document.getElementById("link_referido").innerText;
        // Crea un rango y selecciona el contenido del div
        var rango = document.createRange();
        rango.selectNodeContents(document.getElementById("link_referido"));
        // Copia el contenido al portapapeles
        navigator.clipboard.writeText(contendo)
            .then(function() {
                console.log("Contenido copiado al portapapeles con éxito");
            })
            .catch(function(err) {
                console.error("Error al copiar el contenido al portapapeles", err);
            });
    }

    </script>
</body>
</html>
