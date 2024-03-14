<?php

echo "<div id='foto_antes_depois2'>";
if (isset($_REQUEST['agenda_seq']))
{
	echo "Fotos deste cliente:";
	$strdiv='<form action="upload.php" method="post" enctype="multipart/form-data"> <input type="file" class="frmclient"  name="fileToUpload" id="fileToUpload">';
	$strdiv.='<input type="hidden"  name="agenda_seq" value ="'. $agenda_seq  .'">';
	$strdiv.='<input type="submit" class="frmclient" value="Upload Image" name="submit"> </form>';
	echo $strdiv;
	
	$sql2= "select clipi_seq,clipi_filetype from agenda, client, client_pictures where client_seq=agenda_client_seq AND clipi_agenda_seq=agenda_seq AND agenda_seq=".$agenda_seq;
	//echo $sql2;
	$result = $conn->query($sql2);
	
	if ($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{
			$strdiv='<div id="fotosclipi">';
    		$strdiv.= '<img src="uploads/' .  $row["clipi_seq"].  $row["clipi_filetype"]. '" class="imgantesdpois">';
			$strdiv.='</div>';
    		echo $strdiv; 
		}
	}
}
echo " </div>";
?>
