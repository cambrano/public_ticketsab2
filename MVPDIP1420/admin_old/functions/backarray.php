<?php
		@session_start();
		$urlink=$_POST['urlink'];
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