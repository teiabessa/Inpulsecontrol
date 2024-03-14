
<?php
//Eu listo todos os pacotes do cliente

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$sql= "SELECT * FROM pacote_servico_cliente, profissional Where secl_prof_seq=prof_seq AND secl_client_seq=". $_REQUEST['client_seq']; 
$result = $conn->query($sql);
//var_dump($result);
	
//Se retornou registros
if ($result->num_rows > 0) 
{	$stroption='Pacotes deste Cliente: <select class="frmclient" name="secl_seq" id="secl_seq" onchange="modifyall()">';
    $stroption.='<option value=""></option>';
	 while ($linha = $result->fetch_assoc())
	  $stroption.='<option value="'. $linha["secl_seq"]. '">'. $linha["secl_desc"]. '</option>';
	$stroption.= '</select>';
	echo $stroption;
} 
else 
// Se a consulta não retornar nenhum valor, exibi mensagem para o usuário 
echo "Null"; 
	 mysqli_close($conn);

?>

