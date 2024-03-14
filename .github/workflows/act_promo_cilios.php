<?php
$emailsender='informativo@inpulsebrasil.com.br';
$assunto = utf8_encode('Vamos melhorar o seu visual ? ');
require_once 'db.php';


$sql = "SELECT client_seq,client_name, client_sexo, client_email from client  where  client_email != '' AND client_email != 'Não possui E-Mail' order by client_seq" ;
$sql = "SELECT client_name, client_email  from client where client_seq in (1246,1211)";
//echo $sql;

$result = $conn->query($sql);

//Se retornou registros
if ($result->num_rows > 0)
{
	while ($linha = $result->fetch_assoc())
	{
		$pos= strpos($linha["client_name"]," ");
		$client_name=substr($linha["client_name"], 0, $pos);
		$emailclient=$linha["client_email"];
				
		// Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.
		if(PATH_SEPARATOR == ";")
			$quebra_linha = "\r\n"; //Se for Windows
		else
			$quebra_linha = "\n"; //Se "nÃ£o for Windows"
		
		
		$headers = "MIME-Version: 1.1" . $quebra_linha;
		$headers .= "Content-type: text/html; charset=iso-8859-1" .$quebra_linha;
		//$headers .= "Content-type: text/html; charset=UTF-8" .$quebra_linha;
		$headers .= "From: " . $emailsender. $quebra_linha;
		$message = '<body> <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head><table border="0" cellpadding="0" cellspacing="0">';
		$message .= '<tr> <td> <p>';
		$message .= utf8_decode($client_name) . ', se você gosta de se cuidar ou pensa em dar uma renovada no visual ! <br>';
		$message .= 'Ligue agora e agende o seu alongamento de cílios! tel: 3923-6623 e Whats App: 99651-4126 </p>';
		$message .=' <p>acesse o link para obter mais informações desta técnica de alongamento de cílios:  <a href="http://inpulsebrasil.com.br/index.php?menu_seq=0&tratamento=alongcilios">Inpulsebrasil.com.br</a> </p>';
		$message .= '<img src="http://inpulsebrasil.com.br/inpulsecontrol/images/along_cilios.jpg" width= "500" height="350" border="0"">';
		$message .= '</td> </tr>';
		$message .= '<tr> <td> <p>';
		$message .= 'Continuamos a promoção dos pais até o fim de agosto(para todos os públicos).. </p>';
		$message .= '<img src="http://inpulsebrasil.com.br/inpulsecontrol/images/dia_pais.jpg" width= "500" height="300"  border="0">';
		$message .= '</td> </tr>';
		$message .= '<tr> <td> <p>obs.: Pagamento em dinheiro ou no débito! </p></td> </tr>';
		$message .= '<tr> <td><img src="http://inpulsebrasil.com.br/inpulsecontrol/images/assinatura_email.jpg" border="0"></td></tr>';
		$message .= '</table> </body>';

		// Se o email funcionar, direciona para a página principal
	
		 if (mail($emailclient, $assunto , $message, $headers,"-r"."teia@inpulsebrasil.com.br"))

		 {
			echo "<script>alert('email enviado com sucesso !'</script>";

				$vok='Email enviado com sucesso ';

			}
				else
			{
				$vok='Não Funcionou';
				}
		
				ob_end_flush();
				
		//echo $vok;
		//echo $message;

	 //sleep(1);
	}
	
}
 echo $message;


?>


