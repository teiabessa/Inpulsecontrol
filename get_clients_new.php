<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu faço a busca no banco de dados dos registros de clientes passado via variaável "client_name"


/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
$return="";

// Verifica se existe a variável txtnome 
if (isset($_GET["client_name"])) 
	{ 
		$nome = strtoupper($_GET["client_name"]);

	  // Verifica se a variável está vazia 
	  if (empty($nome)) 
	  { 
	  	$sql = "Select * FROM client where client_seq > 0 ORDER BY client_name"; 
	  } 
	  else 
	  { 
	  	$nome = "%" . $nome . "%" ;
	  	$sql = "Select * FROM client  where client_seq > 0 AND UPPER(client_name) like '$nome'"; 
	  } 
	}
	$result = $conn->query($sql);
	//var_dump($result);
	
	//Se retornou registros
	if ($result->num_rows > 0) 
	  {
	  	 while ($linha = $result->fetch_assoc())
	  	 { 
	  	 	$return.= $linha["client_name"] . "|" . $linha["client_seq"] . "|" . $linha["client_email"]; 
	  	 	$return.=  "|" . $linha["client_cpf"] . "|" . $linha["client_rg"] . "|" . $linha["client_email"] . "|";
	  	 	$return.=  $linha["client_activate"] . "|" . $linha["client_data_nascimento"] . "|" . $linha["client_data_ultimo_atend"]. "|" . $linha["client_cep"] . "|" . $linha["client_bairro"] . "|".  $linha["client_address"] . "|" ;
	  	 	$return.=  $linha["client_bairro"] . "|" . $linha["client_indicacao"] . "|" . $linha["client_profissao"];
	  	 	
	  	 	$sql2 = "Select tecl_seq,tecl_desc,tecl_operadora FROM telefone_clients WHERE tecl_client_seq=". $linha["client_seq"];
	  	 	$result2 = $conn->query($sql2);
	  	 	//retorna a qtd de telefones registrados
	  	 	$return.=$result2->num_rows . "|";
	  	 	while ($linha2 = $result2->fetch_assoc())
	  	 	{
	  	 		$return.=  $linha2["tecl_seq"] . "|" . $linha2["tecl_desc"] . "|" . $linha2["tecl_operadora"] . "|" ;
	  	 	
	  	 	}
	  	 }
	  	  
	  	 echo $return; 
	  	 
	  } 
	 else 
	  { 
	  	 // Se a consulta não retornar nenhum valor, exibi mensagem para o usuário 
	  	 echo "Null"; 
	  }
	mysqli_close($conn);

?>

