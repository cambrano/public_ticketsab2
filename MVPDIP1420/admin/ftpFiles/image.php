<?php
date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
setlocale(LC_ALL,"es_ES");
$length=6; 
$mk_id=time();
$gen_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
$gen_id=$gen_id.$mk_id; 
//Check if we are getting the image
if(isset($_FILES['image'])){
		$type=$_FILES['image']['type'];
		if ($type== "image/gif" OR $type== "image/png" OR $type== "image/jpeg" OR $type== "image/JPEG" OR $type== "image/PNG" OR $type== "image/GIF"){
			if($_FILES['image']['error']==UPLOAD_ERR_OK) {
				$img = $_FILES['image'];
				//The new path of the uploaded image, rand is just used for the sake of it
				$name=str_replace(" ", "_", $gen_id.$img["name"]);
				$rutaTemporal=$_FILES['image']['tmp_name'];
				$path = "files/" . $name;
				if(move_uploaded_file($rutaTemporal,$path)){
					//The direct link to the uploaded image, this might varyu depending on your script location    
					$link = "https://www.softwaresada.com/alienservice/admin/ftpImageNicEditor/files/".$name;
					//Get image info, reuiqred to biuld the JSON object
					//$data = getimagesize("../upload/".$name);
					$data = getimagesize($link);
					$width = $data[0];
					$height = $data[1];
					//Here we are constructing the JSON Object
					$link="https://www.ops.softwaresada.com/imagen.php?id_img={$name}";
					$res = array("data" => array(
											"link" => addslashes(urldecode($link)),
											"width"=>$data[0],
											"height"=>$data[1],
											"size"=>$img['size'],
											"alt"=>$img['name'],
								)
					,"success"=>true,"status"=>200,
					);
				}else{
					$res = array("data" => array("link" => urldecode($link),),"success"=>false,"status"=>500,);
				} 
			}else{
				$res = array("data" => array("link" => urldecode($link),),"success"=>false,"status"=>500);
			}

		}
		else{
			$res = array("data" => array("link" => urldecode($link),),"success"=>false,"status"=>400);
		}
}else{
	$res = array("data" => array("link" => urldecode($link),),"success"=>false,"status"=>400);
}
echo json_encode($res);
?>