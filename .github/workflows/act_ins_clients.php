<?php
//Eu insiro um novo cliente no banco


/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$tecl_seq= $_POST["tecl_seq"];
$tecl_desc= $_POST["tecl_desc"];
$tecl_operadora= $_POST["tecl_operadora"];

$arrlength = count($tecl_desc);

// Verifica se foi checado o formulário de cliente ativo
if (isset($_POST["client_activate"]))
	$client_activate=1;
else
	$client_activate=0;


//print_r($_POST);

// Verifica se foi checado o formulário de cliente ativo
if ($_POST["client_seq"] == "")
{

	$nome = $_POST["client_name"]. "%')";
	$sql = "Select client_seq FROM client WHERE client_seq > 0 AND ucase(client_name) like ucase('". $nome; 
	//echo $sql;
	$result = $conn->query($sql);
	//Se retornou registros
	if ($result->num_rows > 0) 
		{
			//obtem o identificador do cliente
			while ($linha = $result->fetch_assoc())
				$client_seq=$linha["client_seq"];
			
	  		$destination= "<script>alert(' Esse cliente já foi cadastrado !')</script>";
	  		$destination.="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=1&client_seq=". $client_seq . "'</script>";
	  		echo $destination;
	  		
	 	}
	else 
		{
	
	$sql= "INSERT INTO client (client_name, client_cpf, client_rg, client_address, client_cep, client_email,  client_activate, client_data_nascimento, client_bairro) ";
    $sql.="VALUES ('" . $_POST["client_name"] . "','" .  $_POST["client_cpf"]. "','" . $_POST["client_rg"]. "','" . $_POST["client_address"]. "','".  $_POST["client_cep"]. "','".   $_POST["client_email"]. "'," .$client_activate. ",'" . $_POST["client_data_nascimento"].  "','" . $_POST["client_bairro"]. "')";
    
    //echo $sql;
    
    $result = $conn->query($sql);
    
    //obtem o identificador da client inserida
    $client_seq= $conn->insert_id;
    //Insere todos os formulários de telefone do cliente
    for($x = 0; $x < $arrlength; $x++) 
      {
    	$sql= "INSERT INTO telefone_clients (tecl_desc, tecl_operadora, tecl_client_seq) ";
    	$sql.="VALUES ('" . $tecl_desc[$x] . "','" .  $tecl_operadora[$x]. "'," . $client_seq .")";
    	$result = $conn->query($sql);
    	
      }
      
  	}
}
else 
{	
   
	$client_seq=$_POST["client_seq"];
	$sql= "update client  SET client_name='". $_POST["client_name"]  . "',";
	$sql.="client_cpf='". $_POST["client_cpf"] . "'," ;
	$sql.="client_rg='". $_POST["client_rg"] . "'," ;
	$sql.="client_address='". $_POST["client_address"] . "'," ;
	$sql.="client_bairro='". $_POST["client_bairro"] . "'," ;
	$sql.="client_email='". $_POST["client_email"] . "'," ;
	$sql.="client_cep='". $_POST["client_cep"] . "'," ;
	$sql.="client_data_nascimento='". $_POST["client_data_nascimento"] . "'," ;
	$sql.="client_activate=". $client_activate ;
	$sql.=" Where client_seq=". $client_seq  ;
	
	$result = $conn->query($sql);
	//echo $sql;
	
	
	$sql= "DELETE FROM telefone_clients Where tecl_client_seq=". $client_seq;
	
	$result = $conn->query($sql);
	
	// Faz alteração na tabela que contém os telefones dos clientes
	for($x = 0; $x < $arrlength; $x++)
	{
		$sql= "INSERT INTO telefone_clients (tecl_desc, tecl_operadora, tecl_client_seq) ";
    	$sql.="VALUES ('" . $tecl_desc[$x] . "','" .  $tecl_operadora[$x]. "'," . $client_seq .")";
    	$result = $conn->query($sql);
	}
	//executa a sequência sql
	
	}	
	
	//echo "Operadora" .$sql;  	 
	mysqli_close($conn);
	// Se não tem client_seq, é porrque o cliente já existe
    	echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=1&client_seq=". $client_seq . "'</script>";
	
	

?>

