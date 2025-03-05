<?php
    $moduloAccionPermisos = moduloAccionPermisos('operatividad', 'documentos_oficiales', $_COOKIE["id_usuario"]);
    if(empty($moduloAccionPermisos)){
        ?>
        <script type="text/javascript">
            document.getElementById("mensaje").classList.add("mensajeError");
            $("#mensaje").html("No tiene permiso");
            urlink="home.php";
            dataString = 'urlink='+urlink; 
            $.ajax({
                type: "POST",
                url: "functions/backarray.php",
                data: dataString,
                success: function(data) { 	}
            });
            $("#homebody").load(urlink);
        </script>
        <?php
        die;
    }
?>
<script type="text/javascript">
    $(function () {
        $("#fecha_emision").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function (date) {
                document.getElementById("fecha_emision").style.border = "";
            }
        });
        $("#fecha_vigencia").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function (date) {
                document.getElementById("fecha_vigencia").style.border = "";
            }
        });
    });
</script>
<div style=" width: 100%; display:inline-block; text-align: left;">
    <div class="sucFormTitulo">
        <label class="labelForm" id="labeltemaname">Datos Documento Oficial</label>
    </div>
    <div class="sucForm">
        <label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
        <?php
        $select[$documento_oficialDatos['tipo']] = 'selected="selected"';
        ?>
        <select name="tipo" id="tipo" class='myselect'>
            <option value="">Seleccione</option>
            <option <?= $select['ine'] ?> value="ine">INE</option>
            <option <?= $select['comprobante_domicilio'] ?> value="comprobante_domicilio">Comprobante Domicilio</option>
            <option <?= $select['pasaporte'] ?> value="pasaporte">Pasaporte</option>
        </select>
    </div>

    <div class="sucForm">
        <label class="labelForm" id="labeltemaname">Fecha Emisión<font color="#FF0004">*</font></label><br>
        <input class="inputlogin" type="text" name="fecha_emision" autocomplete="off" id="fecha_emision" value="<?= $documento_oficialDatos['fecha_emision'] ?>" placeholder="" /><br>
    </div>

    <div class="sucForm">
        <label class="labelForm" id="labeltemaname">Fecha Vigencia<font color="#FF0004">*</font></label><br>
        <input class="inputlogin" type="text" name="fecha_vigencia" autocomplete="off" id="fecha_vigencia" value="<?= $documento_oficialDatos['fecha_vigencia'] ?>" placeholder="" /><br>
    </div>

    

    <div id="form_imagen">
        <?php
        include "formImagen.php";
        ?>
    </div>

    <div class="sucForm" style="width: 100%" >
        <br>
        <?php
        if ($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']) {
            ?>
            <input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
            <?php
        }
        ?>
        <input type="button" value="Cancelar" onclick="cerrar()">
    </div>
</div>
<script type="text/javascript">
    $(".myselect").select2();
</script>
