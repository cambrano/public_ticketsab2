<?php
	@session_start();
	include __DIR__."../../functions/tool_xhpzab.php";
	setcookie("paguinaId",encrypt_ab_check($_GET['id']), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}