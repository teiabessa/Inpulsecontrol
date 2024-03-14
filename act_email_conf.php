<?php
// Eu envio email para confirmar o atendimento do cliente
ini_set('display_errors', 1);
error_reporting(E_ALL);
/* Invoca o arquivo que faz conexão com o db */
//require_once '../db.php';


$emailsender='informativo@inpulsebrasil.com.br';

//obtem a data de amanhã
$diasemana_n=date('w',strtotime(date('Y-m-d')));

// verifica se hoje é sabado, manda email de segunda
if (date($diasemana_n)== 6)
	$interval="2";
else
	$interval="1";

$sql3="select date_add('". date('Y-m-d'). "', INTERVAL " . $interval. " DAY) as dia_mais";
$result3 = $conn->query($sql3);

while($row = $result3->fetch_assoc())
	$agenda_data=$row["dia_mais"];


	//obtém o dia da semana
	$diasemana=array('domingo','segunda-feira', 'terça-feira', 'quarta-feira','quinta-Feira','sexta-feira', 'sábado');
	$diasemana_n=date('w',strtotime($agenda_data));
			
	$sql2 = "SELECT agenda_seq, agenda_prof_seq,agenda_desc, time_format(agenda_start_time,'%H:%i') as agenda_time_ini,DATE_FORMAT(agenda_start_time, '%d/%m/%Y')as agenda_data, client_name, client_email from client, agenda where agenda_client_seq=client_seq";
	$sql2 .= " AND client_email != '' AND client_email != 'Não possui E-Mail' AND agenda_concluded in (0) AND agenda_seq > 0 AND DATE_FORMAT(agenda_start_time, '%Y-%m-%d')='" . $agenda_data . "'";
	
	// usado para teste $sql2 .= " AND agenda_seq =170684";
	
		
	//echo $sql2; 
	$result = $conn->query($sql2);
		
		
	if ($result->num_rows > 0)
		{
			while ($linha = $result->fetch_assoc())
			{
			// variavel com tipo de agendamento
			$tyse_parent_seq = array();
				
			
			$assunto = "Atendimento In Pulse dia " . $linha["agenda_data"] . " " . $linha["agenda_time_ini"];
					
			$pos= strpos($linha["client_name"],"  ");
			$client_name=substr($linha["client_name"], 0, $pos);
			$emailclient=$linha["client_email"];
				
			
			//verifica se é agendamento de fotodepilação, muda o texto de acordo
			$sql = "Select distinct tyse_parent_seq FROM type_service, agenda_service WHERE aget_tyse_seq=tyse_seq AND aget_agenda_seq=". $linha["agenda_seq"];
            //echo $sql;
			$result2 = $conn->query($sql);
			
			if ($result2->num_rows > 0)
			{
				while ($linha2 = $result2->fetch_assoc())
			  	array_push($tyse_parent_seq, $linha2["tyse_parent_seq"]);
			}
			
			
			$post_desc=$client_name . "</br>";
			//$post_desc.= "E-mail de confirmação do seu horário";
			$post_desc.= "E-mail de confirmação do seu atendimento de ";
			
			
			// Verifica se é agendamento de fotodepilacao
			if (in_array(7, $tyse_parent_seq))				
				$post_desc.= "Fotodepilação";
			else 
				$post_desc.=utf8_decode(trim($linha["agenda_desc"]));
			//$post_desc.=utf8_decode($linha["agenda_desc"]);
			
			$post_desc.= " na In Pulse, ". $diasemana[$diasemana_n]. ", dia ". $linha["agenda_data"] . ", às ". $linha["agenda_time_ini"] . " hs.";
				
			$post_desc.= "<br>Pedimos que se possível, confirmar ou cancelar o seu atendimento clicando em um dos links abaixo:<br>";
            $post_desc.= '<br><a href="http://inpulsebrasil.com.br/inpulsecontrol/rotinas/act_confirm_sessao.php?acao=4&agenda_seq='. $linha["agenda_seq"] .  '">Confirmação</a>';
            $post_desc.=  '<br><br><a href="http://inpulsebrasil.com.br/inpulsecontrol/rotinas/act_confirm_sessao.php?acao=2&agenda_seq='. $linha["agenda_seq"] .  '">Cancelamento</a>';
			
            //$post_desc2="";
			if (in_array(7, $tyse_parent_seq))
				$post_desc.= "<br>Não esquecer de raspar as áreas a serem tratadas !";
			
			//unset($tyse_parent_seq);
			$post_desc.= "<br><br>Desde já agradecemos a sua atenção!";
			
			//$post_desc.="<br>Temos muita satisfação em poder cuidar de vidas!";

			// Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.
			if(PATH_SEPARATOR == ";")
				$quebra_linha = "\r\n"; //Se for Windows
			else
				$quebra_linha = "\n"; //Se "nÃ£o for Windows"

			$headers = "MIME-Version: 1.1" . $quebra_linha;
			$headers .= "Content-type: text/html; charset=UTF-8" .$quebra_linha;
			$headers .= "From: " . $emailsender. $quebra_linha;
			$message = '<body> <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head>';
			$message .= '<table border="0" cellpadding="0" cellspacing="0">';
			$message .= '<tr> <td width="700" height="230" border="1"> <p><font size="4">';			
			$message .= utf8_encode($post_desc). '</font></p></td></tr>';
			
			$message .= '<tr><td><img src="http://inpulsebrasil.com.br/inpulsecontrol/images/assinatura_600.jpg" border="0"></td></tr>';
			$message .= '</table> </body>';
			
			
			
			// Se o email funcionar, direciona para a página principal

			//$vok='Email enviado com sucesso ! ';
			
			
          
			if (mail($emailclient, $assunto , $message, $headers,"-r"."teia@inpulsebrasil.com.br"))

			{
				
				
				$vok='Email enviado com sucesso ! ';

			}
			else
			{
				$vok='Falha ao enviar o e-mail!';
			}
			
     
		
			echo $message;
			$message ="";
			$post_desc="";
			unset($tyse_parent_seq);
				
	}

}




//mysqli_close($conn);
//echo $vok;

?>

