<?php
//Eu listo todos os pacotes do cliente

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

//$sql= "SELECT * FROM pacote_servico_cliente Where secl_seq=". $_REQUEST['secl_seq']; 


$sql= "SELECT secl_prof_venda,secl_forma_venda,secl_parcelas, date_format(secl_data_venda,'%Y-%m-%d') as secl_data_venda2,secl_agenda_type_pagto, secl_agenda_cheque,secl_agenda_sala, secl_days_weak, secl_qtd_sessao_realizada,secl_valor_vendido,secl_prof_seq,secl_contrato_seq, date_format(secl_data_agendamento,'%Y-%m-%d') as agenda_date,time_format(secl_data_agendamento,'%H:%i') as time_start, time_format(secl_end_time,'%H:%i') as time_end FROM pacote_servico_cliente Where secl_seq=". $_REQUEST['secl_seq'];
$result = $conn->query($sql);
//echo $sql;	
//Se retornou registros
if ($result->num_rows > 0) 
{	
	while ($linha = $result->fetch_assoc())
	{
		$return.= $linha["secl_prof_seq"]. "|" . $linha["secl_valor_vendido"] . "|" . $linha["secl_qtd_sessao_realizada"] . "|" . $linha["secl_contrato_seq"]. "|". $linha["agenda_date"]. "|" .$linha["time_start"] . "|" .$linha["time_end"]. "|". $linha["secl_agenda_type_pagto"]. "|" . $linha["secl_agenda_cheque"]. "|"  . $linha["secl_agenda_sala"].   "|". $linha["secl_days_weak"]. "|" . $linha["secl_parcelas"]. "|" . $linha["secl_data_venda2"]. "|"  . $linha["secl_prof_venda"]. "|" . $linha["secl_forma_venda"]. "|" ;
		//$return.= $linha["secl_prof_seq"]. "|" . $linha["secl_valor_vendido"] . "|" . $linha["secl_qtd_sessao_realizada"] . "|" . $linha["secl_status_pagto"] . "|" . $linha["secl_contrato_seq"]. "|";
	//Seleciona os tipos de tratamentos associados ao pacote da cliente atual
	$sql = "Select *  FROM pacotes_type_services, type_service WHERE tyse_seq=peqi_tyse_seq AND peqi_secl_seq=" . $_REQUEST["secl_seq"];
	$result2 = $conn->query($sql);
	 
	//echo $sql;
	if ($result2->num_rows > 0)
	{
		$return.= $result2->num_rows . "|";
		while ($linha = $result2->fetch_assoc())
			$return.= $linha["peqi_tyse_seq"]. "|" .  $linha["tyse_desc"]. "|". $linha["peqi_qtd_sessoes"] . "|" ;
	}
	else 
		$return.= "0|";
	
	echo $return;
	
    }
}


	  
else 
// Se a consulta não retornar nenhum valor, exibi mensagem para o usuário 
echo "Null"; 
	 mysqli_close($conn);

?>

