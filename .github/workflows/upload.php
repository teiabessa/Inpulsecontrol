<?php
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
$target_dir = "uploads/";
$target_file = $target_dir . strtolower(basename($_FILES["fileToUpload"]["name"]));
echo "target=".$target_file;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

if(isset($_POST["submit"])) 
{
	$check = getimagesize(strtolower($_FILES["fileToUpload"]["tmp_name"]));
	if($check !== false) {
		//echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}
}

// Check if file already exists
if (file_exists($target_file)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
	echo "Sorry, your file is too large.";
	$uploadOk = 0;
}

// Allow certain file formats
if(strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
		} else {
			//if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
			 {
				//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				$sql2= 'insert into client_pictures (clipi_agenda_seq, clipi_filetype) values ('.$_POST["agenda_seq"] . ',  ".' . $imageFileType . '" )';
				$result = $conn->query($sql2);
				//obtem o identificador da client inserida
				$clipi_seq= $conn->insert_id;
				//recebe o nome do arquivo
				$clipi_file=$target_dir. $clipi_seq . "." . $imageFileType ;
				//renomeia o arquivo uploadado
				rename($target_file,$clipi_file);
				
				//echo $sql2;
				
				$destination= "<script>alert(' Arquivo carregado com Sucesso !')</script>";
				$destination.="<script>location.href='". $path_inpulsecontrol. "/dsp_form_agenda.php?agenda_seq=". $_POST["agenda_seq"] . "'</script>";
					
			  } 
			else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
		mysqli_close($conn);
		echo $destination;
		?>
