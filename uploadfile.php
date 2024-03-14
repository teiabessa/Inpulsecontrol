<?php

// Eu seleciono as fotos do cliente atual e mostro
$sql2= "select clipi_seq from client_pictures where clipi_client_seq=".$agenda_client_seq;

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
