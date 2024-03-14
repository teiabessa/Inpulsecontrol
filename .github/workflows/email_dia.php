<?php
$emailsender='informativo@inpulsebrasil.com.br';
//$assunto = utf8_encode('Queremos te parabenizar ');
$assunto = 'A In Pulse  te deseja muitas  felicidades... ';
require_once 'db.php';

$sql = "SELECT client_name, client_email  from client where month(client_data_nascimento)='". date('m') .  "' AND day(client_data_nascimento)='".   date('d'). "' ";
//$sql = "SELECT client_name, client_email  from client where client_data_nascimento='1978-11-15'";
echo $sql;

$result = $conn->query($sql);

//Se retornou registros
if ($result->num_rows > 0)
{
	while ($linha = $result->fetch_assoc())
	{
		$emailclient=$linha["client_email"];
		//$linha["client_name"];
		$pos= strpos($linha["client_name"]," ");
		$client_name=substr($linha["client_name"], 0, $pos);
		//echo "pos=" .$pos;

		// Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.
		if(PATH_SEPARATOR == ";")
			$quebra_linha = "\r\n"; //Se for Windows
		else
			$quebra_linha = "\n"; //Se "nÃ£o for Windows"

		$headers = "MIME-Version: 1.1" . $quebra_linha;
		$headers .= "Content-type: text/html; charset=iso-8859-1" .$quebra_linha;
		$headers .= "From: " . $emailsender. $quebra_linha;
		$message = '<body> <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head><table border="0" cellpadding="0" cellspacing="0">';
		$message .= '<tr> <td> <p>';
		$message .= utf8_decode($client_name) . ', que Deus abençõe ricamente a sua vida e te traga muita alegria e paz! </p>';
		$message .= '<img src="http://inpulsebrasil.com.br/inpulsecontrol/images/cartao_niver_inpulse.jpg" alt="Venha nos visitar" width= "400" height="300"  border="0"">';
		$message .= '</td> </tr>';
		$message .= '<tr> <td><img src="http://inpulsebrasil.com.br/inpulsecontrol/images/assinatura_email.jpg"  alt="tel:3923-6623" border="0">';
		$message .= '</td> </tr>';
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

	}
}


echo $message;
?>


