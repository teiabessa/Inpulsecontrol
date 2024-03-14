<?php
//Eu faço pesquiso a data aniversario


/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

// Verifica se existe a variável txtnome 
if (isset($_GET["mes_aniversario"]))
	{ 
		$data1 = $_GET["mes_aniversario"];
		
	  	$sql = "Select * FROM client WHERE month(client_data_nascimento) =".$data1; 
	   
	}
	
// Verifica se o cliente está ativo
	if (isset($_GET["search_activate"]))
	
		$sql = "Select * FROM client WHERE client_activate=1";
	else
		$sql = "Select * FROM client WHERE client_activate=0";
	
	
	
//	  sleep(1);
	$result = $conn->query($sql);
	//var_dump($result);
	
	//Se retornou registros
	if ($result->num_rows > 0) 
	  {
	  	 
	  	// Atribui o código HTML para montar uma tabela 
	  	//$tabela = "<div id='clients_list_ajax'> Nome:". "<br>";
	  	//$return = "$tabela"; // Captura os dados da consulta e inseri na tabela HTML 
	  //	$return = "<div id='clients_list_ajax'>"; // Captura os dados da consulta e inseri na tabela HTML
	  	while ($linha = $result->fetch_assoc())
	  	 { 
	  	 	$return.= $linha["client_name"] . "|" . $linha["client_seq"] . "|" . $linha["client_email"]; 
	  	 	$return.=  "|" . $linha["client_cpf"] . "|" . $linha["client_rg"] . "|" . $linha["client_email"] . "|" . $linha["client_telefone_fixo"];
	  	 	$return.=  "|" . $linha["client_telefone_cel1"] . "|" . $linha["client_activate"] . "|" . $linha["client_data_nascimento"] . "|" . $linha["client_data_ultimo_atend"]. "|" . $linha["client_address"] . "|" ;
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

