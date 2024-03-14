<?php
//Eu insiro um novo crm no banco
ini_set('display_errors', 1);
error_reporting(E_ALL);


/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
//if (isset($_REQUEST['b_delete']))





//verifica se foi definido o cliente para não dar erro
if ($_REQUEST["crm_client_seq"] != "")
	$crm_client_seq=$_REQUEST['crm_client_seq'];
else
	$crm_client_seq="0";




//print_r($_POST);

// Verifica se já tem cliente cadastrado na base de clientes In Pulse e no CRM
if ($_POST["crm_seq"] == "")
{
    $nome = $_POST["crm_client_name"]. "%')";
	$sql = "Select crm_seq FROM CRM WHERE crm_client_name like ucase('%". $nome; 
	//echo $sql;
	$result = $conn->query($sql);
	//Se retornou registros
	if ($result->num_rows > 0) 
		{
			//obtem o identificador do cliente
			while ($linha = $result->fetch_assoc())
				$crm_seq=$linha["crm_seq"];
			
	  		$destination= "<script>alert(' Esse cliente já foi cadastrado no CRM !')</script>";
	  		$destination.="<script>location.href='". $path_inpulsecontrol. "/dsp_form_crm.php?&crm_seq=". $crm_seq . "'</script>";
	  		echo $destination;
	  		
	  		/*$sql2 = "Select crm_seq FROM CRM WHERE crm_client_seq =". $client_seq;
	  		//echo $sql;
	  		$result2 = $conn->query($sql2);
	  		//Se retornou registros
	  		if ($result2->num_rows > 0)
	  		{
	  			//obtem o identificador do cliente
	  			while ($linha2 = $result2->fetch_assoc())
	  			$crm_seq=$linha2["crm_seq"];
	  				
	  			$destination= "<script>alert(' Esse cliente já foi cadastrado no CRM !')</script>";
	  			$destination.="<script>location.href='". $path_inpulsecontrol. "/dsp_form_crm.php?&crm_seq=". $crm_seq . "'</script>";
	  			echo $destination;
	  		
	  	       */	
	 	}
	 	
	 	 	
   
	else 
		{
			 
	$sql= "INSERT INTO CRM (crm_client_name, crm_contato, crm_historico, crm_acao_futura, crm_status_conversa, crm_situacao_cliente,  crm_prof_seq, crm_indicacao, crm_client_seq) ";
    $sql.="VALUES ('" . $_POST["crm_client_name"] . "','" .  $_POST["crm_contato"]. "','" . $_POST["crm_historico"]. "','" . $_POST["crm_acao_futura"]. "',".  $_POST["crm_status_conversa"]. ",".   $_POST["crm_situacao_cliente"]. "," ;
    $sql.= $_POST["crm_prof_seq"]. ",'".$_POST["crm_indicacao"]."',". $crm_client_seq.")";
    
    echo $sql;
    
    $result = $conn->query($sql);
    
    //obtem o identificador da client inserida
    $crm_seq= $conn->insert_id;
    //Insere todos os formulários de telefone do cliente
    
    }
}
else 
{	
   
	$crm_seq=$_POST["crm_seq"];
	$sql= "update CRM  SET crm_client_name='". $_POST["crm_client_name"]  . "',";	
	$sql.="crm_contato='". $_POST["crm_contato"] . "'," ;
	$sql.="crm_historico='". $_POST["crm_historico"] . "'," ;
	$sql.="crm_acao_futura='". $_POST["crm_acao_futura"] . "'," ;
	$sql.="crm_status_conversa=". $_POST["crm_status_conversa"]. ",";
	$sql.="crm_situacao_cliente=". $_POST["crm_situacao_cliente"]. ",";
	$sql.="crm_prof_seq=". $_POST["crm_prof_seq"]. ",";
	$sql.="crm_client_seq=". $crm_client_seq. ",";
	$sql.="crm_indicacao='". $_POST["crm_indicacao"]. "'";
	$sql.=" Where crm_seq=". $crm_seq  ;
	
	$result = $conn->query($sql);
	//echo $sql;
	
	
	
	}

	//atualiza a conversa
	$sql2 = "INSERT INTO hist_crm(hcrm_date,hcrm_comment,hcrm_scrm_seq,hcrm_crm_seq)";
	$sql2 .="VALUES (NOW(),'". $_POST["crm_historico"]. "',".$_POST["crm_status_conversa"]. ",". $crm_seq. ")";
	$result = $conn->query($sql2);
	//echo $sql2;
	
	//echo "Operadora" .$sql;  	 
	mysqli_close($conn);
	// Se não tem client_seq, é porrque o cliente já existe
   echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=4&crm_prof_seq=". $_POST["crm_prof_seq"] . "'</script>";
	
	

?>

