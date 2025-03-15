<?php
include "../MVPDIP1420/admin/functions/db.php";
include "../MVPDIP1420/admin/functions/secciones_ine.php";

?>
<script>
    function buscar() {
        var id_seccion_ine = document.getElementById("id_seccion_ine").value;
        var secciones = [];
        var data = {
            'id_seccion_ine_ciudadano': id_seccion_ine
        };
        secciones.push(data);

        fetch("mapa.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({secciones: secciones})
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("mapa").innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }

</script>
<div class="sucForm">
    <label class="labelForm" id="labeltemaname">Sección</label><br>
    <select class="myselect" id="id_seccion_ine" >
        <?php
        echo secciones_ine($seccion_ine_ciudadanoDatos['id_seccion_ine'],1821);
        ?>
    </select><br>
</div>
<div id="mapa">

</div>
<div class="sucForm" style="width: 100%" >
    <input type="button" id="sumbmit" onclick="buscar()" value="Buscar">
</div>