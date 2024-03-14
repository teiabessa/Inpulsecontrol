<?phpini_set('display_errors', 1);error_reporting(E_ALL);require_once "db.php";
//Eu sou a página que envia o contato do cliente para a página atendimento@inpulsebrasil.com.br
$emailsender='informacoes@inpulsebrasil.com.br';$assunto = 'A In Pulse lembrou de você nesse dia especial !';$sql = "SELECT client_name, client_email  from client where client_data_nascimento='". date('Y-m-d') .  "'";//$sql = "SELECT client_name, client_email  from client where client_data_nascimento='1980-03-08'"; echo $sql;$result = $conn->query($sql);//Se retornou registrosif ($result->num_rows > 0){	while ($linha = $result->fetch_assoc())	{		$emailclient=$linha["client_email"]; 		//$linha["client_name"];		$pos= strpos($linha["client_name"]," ");		$client_name=substr($linha["client_name"], 0, $pos);		//echo "pos=" .$pos;			/* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  */		if(PATH_SEPARATOR == ";") 			$quebra_linha = "\r\n"; //Se for Windows		else 		$quebra_linha = "\n"; //Se "nÃ£o for Windows"		/* Montando o cabeÃ§alho da mensagem */
		$headers = "MIME-Version: 1.1" . $quebra_linha;		$headers .= "Content-type: text/html; charset=iso-8859-1" .$quebra_linha;		$headers .= "From: " . $emailsender. $quebra_linha;		$message = '<body> <table border="0" cellpadding="0" cellspacing="0">'; 		$message .= '<tr> <td> <p>';		$message .= $client_name . ', que Deus abençõe ricamente a sua vida e te traga muita alegria e paz! </p>';		$message .= '<img src="http://inpulsebrasil.com.br/inpulsecontrol/images/cartao_niver_inpulse.jpg" width= "450" height="400"  border="0"">';		$message .= '</td> </tr>';		$message .= '<tr> <td><img src="http://inpulsebrasil.com.br/inpulsecontrol/images/assinatura_email.jpg" border="0">'; 		$message .= '</td> </tr>';		$message .= '</table> </body>';
		// Se o email funcionar, direciona para a página principal

		if (mail($emailclient, $assunto , $message, $headers,"-r"."atendimento@inpulsebrasil.com.br"))

		{			echo "<script>alert('email enviado com sucesso !'</script>";			$vok='Email enviado com sucesso ';

		}		else		{ 
		$vok='Não Funcionou';		}
	ob_end_flush();	//echo $vok;echo $message;	}}
    
?>




