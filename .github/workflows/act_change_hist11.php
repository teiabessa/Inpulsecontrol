<?php
//Eu faço a busca no banco de dados dos registros de clientes passado via variaável "client_name"


/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
$agenda_start_time=date('Y-m-d');

$sql= "UPDATE client_call SET  clica_comment='Email Enviado Sistema', clica_stali_seq=11"  ;
$sql.= " WHERE  clica_seq=" .  $_REQUEST["clica_seq"] ;
echo $sql;
$result = $conn->query($sql);

$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq) VALUES(11,";
$sql.="'Email enviado via sistema','".  $agenda_start_time. "'," . $_REQUEST["clica_seq"].  ")";
$result = $conn->query($sql);

require  'rotinas/act_email_corporal_mes.php'; 
 
?>

