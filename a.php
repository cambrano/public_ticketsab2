<?php
$extensiones = get_loaded_extensions();
echo "<table border=1>";
echo "<tr>";
echo "<td>Extension</td>";
echo "</tr>";
foreach ($extensiones as $key => $value) {
	echo "<tr>";
	echo "<td>".$value."</td>";
	echo "</tr>";
}

?>
