<?php

/* client_service (tabela de realacionamento que liga clientes aos tratamentos realizados
 type_service, client)
 client_service(clse_seq,clse_tyse_seq,clse_client_seq,clse_agenda_seq,clse_status, clse_motivo,clse_data_prox_contato)
 
 
 CREATE TABLE `inpulsebrasil`.`client_service` (
  `clse_seq` INT NOT NULL,
  `clse_tyse_seq` INT NULL,
  `clse_tyse_parent_seq` INT NULL,
  `clse_client_seq` INT NULL,
  `clse_agenda_seq` INT NULL,
  PRIMARY KEY (`clse_seq`))
COMMENT = 'tabela que mantem o histórico dos tratamentos realizados dos clientes\n( type_service, client)\n\n ';


 client_call (clica_seq,clica_next_agenda_seq,clica_clse_seq, clica_motivo)
 
 
 update inpulsebrasil.status_ligacao set stali_desc='Contato não realizado' where stali_seq=1;
 update inpulsebrasil.status_ligacao set stali_desc='Não tem interesse' where stali_seq=2;
 update inpulsebrasil.status_ligacao set stali_desc='Está em manutenção do tratamento' where stali_seq=3;
 update inpulsebrasil.status_ligacao set stali_desc='Finalizou' where stali_seq=4;
 update inpulsebrasil.status_ligacao set stali_desc='Contactar em outro momento' where stali_seq=5;
 update inpulsebrasil.status_ligacao set stali_desc='Insatisfeito com a In Pulse' where stali_seq=6;
 update inpulsebrasil.status_ligacao set stali_desc='Agendamento Realizado' where stali_seq=7;
 update inpulsebrasil.status_ligacao set stali_desc='Email enviado' where stali_seq=8;
 
 
 *  */

ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu faço a busca no banco de dados de todos os registros de clientes

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$client_seq=array();

//verifica se foi definido o cliente
if (isset($_REQUEST["client_seq"]))
	$client_seq=$_REQUEST['client_seq'];
else 
	$client_seq[0]="";
$arrlength = count($client_seq);

//verifica se foi definido o profissional
if (isset($_REQUEST["prof_seq"]))
	$prof_seq=$_REQUEST['prof_seq'];
else
	$prof_seq="";

$stroption='<div id="frmligacoesprofissionais"> <FORM ACTION="index.php?menu_seq=4" METHOD="POST" id="formagendaligacoes" name="formagendaligacoes">';
echo $stroption;


// Pega cliente que tem pacote inferiro a 60 dias diminui 30 dias
$agenda_start_time=date('Y-m-d');


 $stroption=' Cliente: <select  multiple  name="client_seq[]" id="client_seq"  size="20">';
 //Busca o cliente no banco
 $sql = "Select client_seq, client_name FROM client  WHERE client_seq > 0 ";
 if (trim($clica_client_seq))
 	$sql .= " and client_seq not in(". $clica_client_seq.   ") ";
 
 $sql.= " AND client_seq not in (SELECT distinct secl_client_seq FROM pacote_servico_cliente WHERE secl_data_agendamento >= DATE_SUB(CURDATE(),INTERVAL 60 DAY))";
 $sql.= " ORDER BY client_name";

 
 $result = $conn->query($sql);
//echo $sql;

 if ($result->num_rows)
 {
 		$stroption.='<option value="">';
 	// Monta o select do cliente
	 while ($linha = $result->fetch_assoc())
 	{
 			$tel_client="";
 			$sql2 = "Select  tecl_operadora, tecl_desc FROM telefone_clients where tecl_client_seq=".$linha["client_seq"] ;
 			//echo $sql2;
 			$result2 = $conn->query($sql2);
 			if ($result2)
 			{
 				while ($linha2 = $result2->fetch_assoc())
 					// verifica se foi preenchido a operadora
 					if(trim($linha2["tecl_operadora"]))
 						$tel_client.=  "   --   "  .  $linha2["tecl_operadora"];
 			}
 				
 		$stroption.='<option value="'. $linha["client_seq"]. '"';
 		if ($linha["client_seq"] == $clica_client_seq)
 			$stroption.='selected';
 		$stroption.='>'. $linha["client_name"]. $tel_client.  '</option>';
 
 	}
 	$stroption.='</select>';
 	echo $stroption;
 }
} 

 
 // Campo das profissionais
 $stroption='Profissional:';
 $sql = "Select * FROM profissional ORDER BY prof_name";
 $result = $conn->query($sql);
 $stroption.=' <select  name="prof_seq" id="prof_seq" >';
 	
 $stroption.='<option value="">';
 while ($linha = $result->fetch_assoc())
 {
 	$stroption.='<option value="'. $linha["prof_seq"]. '"';
 	if ($linha["prof_seq"] == $prof_seq)
 		$stroption.='selected';
 	 
 	$stroption.='>'. $linha["prof_name"]. '</option>';
 
 }
 $stroption.='</select>';
 echo $stroption;
 
 
 
 
 //Formulario de clientes do profissional
 /*
 $stroption=' Cliente: <select  multiple class="frmclient" name="clica_client_seq[]" id="clica_client_seq" >';
 //Busca o cliente no banco
 $sql = "Select * FROM client ORDER BY client_name order by client_name";
 $result = $conn->query($sql);
 
 if ($result->num_rows > 0)
 {
 	// Monta o select do cliente
 	while ($linha = $result->fetch_assoc())
 	{
 		$stroption.='<option value="">';
 		$stroption.='<option value="'. $linha["client_seq"]. '"';
 		// Verifica se existe no array
 		if (in_array($linha['client_seq'], $client_seq))
 			$stroption.='selected';
 	
 		$stroption.='>'. $linha["client_name"]. '</option>';
	 }
 $stroption.='</select>';
 echo $stroption;
 }
 */ 
 echo '<INPUT type="submit" value="Pesquisar" id="search_clica" name="search_clica"  class="frmsubmit">';
 // Se for alessandra ou teia logado, aparece essa opçao
 
 
 $stroption="</form></div>";
 echo $stroption;
 
 
 
 
 
 // Verifica se é para pesquisar profissional/clientes
 if  (isset($_REQUEST["search_clica"]))
 {
 	
 		$sql="SELECT clica_next_agenda_seq,clica_motivo,clica_comment,clica_last_agenda_seq,prof_name, prof_seq,client_name,client_email, clica_data_ligacao,stali_desc, client_seq, clica_seq  From status_ligacao,profissional,client,client_call ";
 		$sql.=" where client_seq > 0 AND client_seq=clica_client_seq ";
 		$sql.= "AND prof_seq=clica_prof_seq AND clica_stali_seq=stali_seq AND client_seq = clica_client_seq AND prof_seq=". $prof_seq;
 		$sql.= " AND client_seq not in (SELECT distinct secl_client_seq FROM pacote_servico_cliente WHERE secl_data_agendamento >= DATE_SUB(CURDATE(),INTERVAL 60 DAY))";
 		$sql.= " ORDER BY clica_seq DESC";
 		
 		//Essa lógica é para ser rodada separadamente
 		//lista todos os clientes que tiveram atendimento concluido
 		
 		$sql="SELECT max(agenda_start_time) as max_agenda_date, client_seq, if(tyse_parent_seq=7, 'fotodepilação', tyse_desc) as tratamento From agenda, client, type_service,agenda_service where agenda_seq > 0 AND client_seq=agenda_client_seq AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq ";
 		$sql.="AND client_seq <> 1298 AND agenda_concluded=3 Group by client_seq,tratamento";
 			
 		//$sql="SELECT max(agenda_start_time) as data_last_sessao,agenda_seq,client_seq FROM agenda, client WHERE agenda_client_seq=client_seq AND client_seq > 0 AND "; 
		//$sql.=" agenda_seq > 0 AND agenda_concluded=3 Group by client_seq order by client_name asc";

       	$result = $conn->query($sql);
 			if ($result)
 			{
 				while ($linha = $result->fetch_assoc())
 				{
 			
       				// seleciona os agendamentos com ciclos vencidos
       				//$sql2="SELECT agenda_prof_seq, agenda_start_time, if(tyse_parent_seq=7, 'fotodepilação', tyse_desc) as tratamento From agenda, client, type_service,agenda_service where agenda_seq > 0 AND client_seq=agenda_client_seq AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq AND datediff('2016-01-01',agenda_start_time) > tyse_duracao"; 
       				//$sql2.="AND agenda_seq=$agenda_seq ORDER BY client_name asc";
       				
       				// Verifica se o intervalo tá dentro do período estipulado
       				$sql2="SELECT agenda_seq, agenda_prof_seq, agenda_start_time From agenda, client, type_service,agenda_service where agenda_seq > 0 AND client_seq=agenda_client_seq AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq";
       				$sql2.="AND DATE_ADD(agenda_start_time,INTERVAL tyse_duracao DAY) <= CURDATE() AND agenda_start_time= '" $linha["max_agenda_date"] . "' AND client_seq = ". $linha["client_seq"] . " ORDER BY client_name asc";
       				$result2 = $conn->query($sql2);
       			
       				if ($result2)
       				{
       					while ($linha2 = $result2->fetch_assoc())
       					{
       					$sql3= "INSERT INTO client_call ( clica_prof_seq, clica_client_seq, clica_stali_seq, clica_last_agenda_seq) values ";
       					$sql3.="(" . $linha2["agenda_prof_seq"] . ",". $linha["client_seq"] .",1 , " . $linha2["agenda_seq"] . ")";
       					//$result3 = $conn->query($sql3);
       					echo $sql3 . "<br>"
       					}
       				}
 				}
 			}
 		

		//echo $sql;

 		//echo $sql;
 	/*
 		
 		$sql="SELECT  clica_next_agenda_seq,clica_last_agenda_seq,prof_name, prof_seq,client_name,client_email, clica_data_ligacao,stali_desc, client_seq, clica_seq  From status_ligacao, client_call,profissional,client where client_seq=clica_client_seq ";
 		$sql.= "AND prof_seq=clica_prof_seq AND clica_stali_seq=stali_seq AND client_seq = clica_client_seq AND prof_seq=". $prof_seq;
 		$sql.= " ORDER BY clica_data_ligacao DESC";
 		
 	*/
 		
 	
 		$result = $conn->query($sql);
 	
 		echo "<div id='div_report_lig'>";
 		$stroption='<div class="linha_report_lig">Agendamento</div> ';
 		$stroption.='<div class="linha_report_lig">Comentário</div> ';
 		$stroption.='<div class="linha_report_lig">Cliente</div> ';
 		$stroption.='<div class="linha_report_lig">'. utf8_encode("Status Lig.").'</div> ';
 		$stroption.='<div class="linha_report_lig">'. utf8_encode("Data Lig."). '</div> ';
 		$stroption.='<div class="linha_report_lig">'. utf8_encode("Última Visita"). '</div> ';
 		$stroption.='<div class="clear"></div>';
 	   
 		if ($result->num_rows > 0)
 		{
 			while ($linha = $result->fetch_assoc())
 			{  
 				$tel_client="";
 				$sql2 = "Select  tecl_operadora, tecl_desc FROM telefone_clients where tecl_client_seq=".$linha["client_seq"] ;
 				$result2 = $conn->query($sql2);
 				if ($result2)
 				{
 					while ($linha2 = $result2->fetch_assoc())
 						$tel_client.="<br>".$linha2["tecl_desc"].  "  ".  $linha2["tecl_operadora"];

 				}
 				$clica_last_agenda_seq="";
 				$data_last_sessao="";
 					
 				
 				//Obtém o identificador do agendamento do cliente
 				$sql = "SELECT max(agenda_seq) as max_agenda_seq,date_format(agenda_start_time, '%d/%m/%Y %h:%i:%s') as data_last_sessao FROM agenda WHERE agenda_client_seq=". $linha["client_seq"] ;
 				$sql .=	" AND agenda_seq > 0 AND agenda_concluded=3" ;
 				$sql .=" ORDER BY  agenda_seq DESC";
 				//echo $sql;
 				$result_verify2 = $conn->query($sql);
 				while ($linha3 = $result_verify2->fetch_assoc())
 				{
 					$clica_last_agenda_seq=  $linha3["max_agenda_seq"];
 					$data_last_sessao=  $linha3["data_last_sessao"];
 				}
 				
 				 					
 				//Se tiver agendamento, mostra o link
 				if ($linha["clica_next_agenda_seq"])
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '&agenda_seq='.  $linha["clica_next_agenda_seq"].  '">Agendamento Programado</a></div>';
 					
 				else 
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '">Novo Agendam</a></div>';
 				
 				$stroption.='<div class="linha_lig">'. $linha["client_name"]. $tel_client. '</div>';
 				$stroption.='<div class="linha_lig"> <a target=”_blank” href="dsp_form_status_lig.php?clica_seq='. $linha["clica_seq"]. ' " >'. $linha["stali_desc"].  '</a></div>';
 				$stroption.='<div class="linha_lig">'. $linha["clica_data_ligacao"].  '</div>';
 				if (date($data_last_sessao))
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"].  '&agenda_seq='.  $clica_last_agenda_seq.  '">'.$data_last_sessao.'</a></div>';
 				else 
 					$stroption.='<div class="linha_lig"></div>';
 				
 				$stroption.='<div class="clear"></div>';
 				
 				
 			}
 		}
 	
 		echo $stroption;
 		 
 		echo " </div>";
 	}
 	

 	// Verifica se é para pesquisar profissional/clientes
 	if  (isset($_POST["ins_clica"]))
 	{
 		
 		for($x = 0; $x < $arrlength; $x++)
 		{
 			$sql= "INSERT INTO client_call ( clica_prof_seq, clica_client_seq, clica_stali_seq) values ";
 			$sql.="(" . $prof_seq . ",". $client_seq[$x] .",1)";
 			$result = $conn->query($sql);
 			//echo $sql . "<br>";
 		}
 		
 		echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=4&search_clica='yes'&prof_seq=". $prof_seq . " '</script>";
 	}
			
?>
   