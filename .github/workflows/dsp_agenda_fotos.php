<?php

$sql2= "select clipi_seq from agenda, client, client_pictures where client_seq=agenda_client_seq AND clipi_agenda_seq=agenda_seq AND agenda_seq=".$agenda_seq;

$result = $conn->query($sql2);
echo "<div id='foto_antes_depois'>";
echo "Fotos deste cliente:";
if ($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$strdiv='<div id="fotosclipi">';
    	$strdiv.= '<img src="uploads/' . $clipi_seq. '.jpg"> </a>';
		$strdiv.'</div>';
    	echo $strdiv; 
	}
}
echo " </div>";
?>