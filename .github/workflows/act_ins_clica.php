<?php
//Eu faço a busca no banco de dados dos registros de clientes passado via variaável "client_name"


/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
$clica_stali_seq= $_POST["clica_stali_seq"];
$clica_comment= $_POST["clica_comment"];
$clica_seq= $_POST["clica_seq"];
$clica_date= $_POST["clica_date"]. " ". $_POST["clica_time"] . ":00";

//var_dump($_POST). "<br>";


$sql= "INSERT INTO hist_stat_lig ( hstat_date, hstat_comment, hstat_stali_seq, hstat_clica_seq) values ";
$sql.="('" . $clica_date . "','". $clica_comment ."',". $clica_stali_seq .",". $clica_seq. ")";
$result = $conn->query($sql);
echo $sql . "<br>";


$sql= "update client_call set";
$sql.= " clica_stali_seq=" . $clica_stali_seq  . ",";
$sql.= " clica_comment='" . $clica_comment . "',";
$sql.= " clica_data_ligacao='" . $clica_date . "'";
$sql.= " where clica_seq=". $clica_seq;	

echo $sql;
$result = $conn->query($sql);
mysqli_close($conn);
echo "<script>location.href='". $path_inpulsecontrol. "/dsp_form_status_lig.php?clica_seq=". $clica_seq . " '</script>";

//echo $sql ;

  
 
?>

