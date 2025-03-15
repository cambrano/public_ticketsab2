<?php

    @session_start();
    $original=$_SESSION['numarrayback'];
    if($i= $_SESSION['numarrayback']== 0){
      $i= $_SESSION['numarrayback'];
      $_SESSION['urllink'.$i];
    }else{
      $i= $_SESSION['numarrayback']=$_SESSION['numarrayback']-1;
      $_SESSION['urllink'.$i];
    }
   echo  $_SESSION['Paguinasub']=$_SESSION['urllink'.$i];
    $original;
?>