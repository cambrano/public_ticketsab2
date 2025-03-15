<?php
		@session_start();
		echo $urlink=$_POST['urlink'];
		echo "/";
		echo $id=$_POST['id'];

		setcookie('Paguinasub', $urlink, time() + (86400 * 30), '/MVPDIP1420/ciudadano/');
		setcookie('paguinaId', $id, time() + (86400 * 30), '/MVPDIP1420/ciudadano/');

		$numarrayback=$_SESSION['numarrayback'];
		$session_urllink=$_SESSION['urllink'.$numarrayback];
		if($urlink==$session_urllink){}else{
			if($_SESSION['numarrayback'] ==0){
				$_SESSION['numarrayback']=1;  
			}else{
				$_SESSION['numarrayback']=$_SESSION['numarrayback']+1;
			}
		$numarrayback=$_SESSION['numarrayback'];
		$_SESSION['urllink'.$numarrayback]=$_POST['urlink'];
}
?>