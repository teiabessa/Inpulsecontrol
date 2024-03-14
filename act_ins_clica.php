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
//echo $sql . "<br>";

// Status que desativa o cliente da lista de ligaçoes
$stat_array = array("2","3","4","5","6","9","10","14");
// Se for um desses status, seta para contato realizado
//if ($clica_stali_seq == 3 || $clica_stali_seq == 14 || $clica_stali_seq == 2 || $clica_stali_seq == 4 || $clica_stali_seq == 5 || $clica_stali_seq == 6 || $clica_stali_seq == 9 || $clica_stali_seq == 10)

if(in_array($clica_stali_seq, $stat_array))
	$clica_activate=1;
else 
	$clica_activate=0;

$agenda_start_time=date('Y-m-d');

$sql= "update client_call set";
$sql.= " clica_stali_seq=" . $clica_stali_seq  . ",";
$sql.= " clica_comment='" . $clica_comment . "',";
$sql.= " clica_activate=" . $clica_activate . ",";
$sql.= " clica_data_ligacao='" . $clica_date . "'";
$sql.= " where clica_seq=". $clica_seq;	

//echo $sql;
$result = $conn->query($sql);


$sql= "UPDATE client set";
$sql.= " client_data_nascimento='" . $_POST["client_data_nascimento"]  . "',";
$sql.= " client_email='" . $_POST["client_email"] . "',";
$sql.= " client_rg='" . $_POST["client_rg"] . "',";
// Se cliente tá insatisfeito, tira ele de ativo
if ($clica_stali_seq == 9 || $clica_stali_seq == 13)
	$sql.= " client_activate=0,";
$sql.= " client_cpf='" . $_POST["client_cpf"] . "'" ;
$sql.= " where client_seq=". $_POST["clica_client_seq"];

//echo $sql;
$result = $conn->query($sql);
mysqli_close($conn);

echo "<script>location.href='". $path_inpulsecontrol. "/dsp_form_status_lig.php?clica_seq=". $clica_seq . " '</script>";

//echo $sql ;

  
 
?>

