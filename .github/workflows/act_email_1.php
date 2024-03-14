<?php



//Eu sou a página que envia o contato do cliente para a página atendimento@inpulsebrasil.com.br




$emailsender='teia@inpulsebrasil.com.br';
$emailclient='teiabak@gmail.com';
$client_name='Verusca';
$assunto = 'A In Pulse comemora com você esse dia tão especial !';

/* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  */
if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
else $quebra_linha = "\n"; //Se "nÃ£o for Windows"
 

/* Montando o cabeÃ§alho da mensagem */
$headers = "MIME-Version: 1.1" .$quebra_linha;
$headers .= "Content-type: text/html; charset=iso-8859-1" .$quebra_linha;
// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
$headers .= "From: " . $emailsender.$quebra_linha;
//$headers .= "Reply-To: " . $emailremetente . $quebra_linha;
// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)

$message = '<body> <table border="0" cellpadding="0" cellspacing="0">'; 
$message .= '<tr> <td> <p>';
$message .= $client_name . ', receba essa sincera lembrança da In Pulse ! </p>';
$message .= '<img src="http://inpulsebrasil.com.br/inpulsecontrol/images/cartao_aniversario.jpg" border="0" width="300" height="300">';
$message .= '</td> </tr>';
$message .= '<tr> <td><img src="http://inpulsebrasil.com.br/inpulsecontrol/images/rodape_inpulse.jpg" border="0">'; 
$message .= '</td> </tr>';
$message .= '</table> </body>';

// Se o email funcionar, direciona para a página principal


if (mail($emailclient, $assunto , $message, $headers,"-r"."atendimento@inpulsebrasil.com.br"))

{echo "<script>alert('email enviado com sucesso !'</script>";


}


else{ 
	
$vok='Não Funcionou';



}


ob_end_flush();



    
?>




