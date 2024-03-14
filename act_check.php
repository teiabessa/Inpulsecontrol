<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu faço a busca no banco de dados de todos os registros de clientes

/* Invoca o arquivo que faz conexão com o db */
require_once '../db.php';

//Essa lógica é para ser rodada separadamente
//lista todos os clientes que tiveram atendimento concluido
	
$sql="SELECT max(agenda_start_time) as max_agenda_date, client_seq, if(tyse_parent_seq=7, 'fotodepilação', tyse_desc) as tratamento From agenda, client, type_service,agenda_service where agenda_seq > 0 AND client_seq=agenda_client_seq AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq ";
$sql.="AND client_seq <> 1298 AND agenda_concluded=3 Group by client_seq,tratamento";


$result = $conn->query($sql);
if ($result)
{
	while ($linha = $result->fetch_assoc())
	{
		// Verifica se o intervalo tá dentro do período estipulado
		$sql2="SELECT agenda_seq, agenda_prof_seq, agenda_start_time From agenda, client, type_service,agenda_service where agenda_seq > 0 AND client_seq=agenda_client_seq AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq";
		$sql2.="AND DATE_ADD(agenda_start_time,INTERVAL tyse_duracao DAY) <= CURDATE() AND agenda_start_time= '".  $linha["max_agenda_date"] . "' AND client_seq = ". $linha["client_seq"] . " ORDER BY client_name asc";
		$result2 = $conn->query($sql2);

		if ($result2)
		{
			while ($linha2 = $result2->fetch_assoc())
			{
				$sql3= "INSERT INTO client_call ( clica_prof_seq, clica_client_seq, clica_stali_seq, clica_last_agenda_seq) values ";
				$sql3.="(" . $linha2["agenda_prof_seq"] . ",". $linha["client_seq"] .",1 , " . $linha2["agenda_seq"] . ")";
				//$result3 = $conn->query($sql3);
				echo $sql3 . "<br>";
			}
		}
	}
}
	

//	echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=4&search_clica='yes'&prof_seq=". $prof_seq . " '</script>";

?>