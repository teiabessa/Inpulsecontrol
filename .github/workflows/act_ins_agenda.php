<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu insiro uma nova agenda no banco
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
//var_dump($_POST);

$agenda_desc="";

$valor=$_POST["agenda_valor"];
//Coloca a data como padrao do php
$agenda_start_time= $_POST["agenda_date"]. " ". $_POST["agenda_start_time"] . ":00";
$agenda_end_time= $_POST["agenda_date"]. " ". $_POST["agenda_end_time"] .  ":00";
$agenda_seq=$_POST["agenda_seq"];
$agenda_sala=$_POST["agenda_sala"];

//var_dump($_POST);

// Verifica se a opção é de apagar o agendamento
if (isset($_POST["b_delete"]))
{ 

	$sql= "Update agenda set agenda_seq=agenda_seq*-1 where agenda_seq=" . $_POST["agenda_seq"];
	$result = $conn->query($sql);
	$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0&agenda_start_time=". $_POST["agenda_date"] ."'</script>";
	//$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0'</script>";
	
}
else 
{

	$tyse_seq=array();
	// Verifica se foi checado o formulário do pacote de cliente
	if (isset($_POST["secl_seq"]))
	{
		$sql= "Select secl_desc, secl_qtd_sessao_realizada From pacote_servico_cliente Where secl_seq=". $_POST["secl_seq"];
		$result1 = $conn->query($sql);
		while($row = $result1->fetch_assoc())
		{
			//$qtd_sessao= $row["secl_qtd_sessao_realizada"]+1;
			$qtd_sessao= 1;
		
			$agenda_desc= $row["secl_desc"];
		}
	
	}
	// Transforma o formulario dos servicos em array
	if (isset($_POST["tyse_seq"]))
	{	
		//$tyse_seq=array_merge($_POST["tyse_seq"]);
		$tyse_seq=$_POST["tyse_seq"];
		$arrlength =count($_POST["tyse_seq"]);
		for($x = 0; $x < $arrlength; $x++)
		{
			$sql= "Select tyse_desc, tyse_valor From type_service where tyse_seq=". $tyse_seq[$x];
			$result1 = $conn->query($sql);
			if ($result1->num_rows > 0)
			{	
				while($row = $result1->fetch_assoc())
			{
				if (trim($agenda_desc))
					$agenda_desc.="-". $row["tyse_desc"] ;
				else 
					$agenda_desc.=$row["tyse_desc"] ;
				
				// se tiver vazio, recebe o valor do tratamento no banco
				if(trim($valor) == "")
					$valor=$row["tyse_valor"]; 
			 }
		}
	}
}

//echo "agenda_desc=". $agenda_desc . "<br>" ;
//print_r($_POST["tyse_seq"]);

if (isset($_POST["agenda_outros"]))
	$agenda_desc=$_POST["agenda_outros"];

// Invoca o arquivo que faz a consistência da data e hora 
require 'act_check_consistencia.php';
if (trim($destination) == "")
{

	// prepara para inserir ou atualizar o agendamento

// Se o agenda_seq=0, é para inserir
 if ($agenda_seq == "0")
  {
	$sql= "INSERT INTO agenda (agenda_desc,agenda_valor,agenda_desconto,agenda_cheque,agenda_sala, agenda_prof_seq, agenda_client_seq, agenda_type, agenda_start_time, agenda_end_time, agenda_concluded, agenda_type_pagto";
	
	// Verifica se foi checado o formulário do pacote de cliente 
	if (isset($_POST["secl_seq"]))
		$sql.= ",agenda_secl_seq";
		
	if (isset($_POST["agenda_outros"]))
		$sql.= ",agenda_outros";
		
	$sql.=  ") VALUES ('".$agenda_desc . "'," . $valor . ", " . $_POST["agenda_desconto"] . ",'" . $_POST["agenda_cheque"].  "'," . $_POST["agenda_sala"] ;
			
	if ($_POST["agenda_type"] == 4)
		$sql.= ", NULL, NULL";
	else
		$sql.= "," . $_POST["prof_seq"] . "," .  $_POST["client_seq"]; 
		$sql.="," . $_POST["agenda_type"]. ",'" . $agenda_start_time. "','".  $agenda_end_time. "',". $_POST["agenda_concluded"]. ",". $_POST["agenda_type_pagto"];
             
    		
    	// Verifica se foi checado o formulário de cliente ativo
    	if (isset($_POST["secl_seq"]))
    		$sql.= "," . $_POST["secl_seq"];
    		
    	// Verifica se foi checado o formulário de cliente ativo
    	if (isset($_POST["agenda_outros"]))
    		$sql.= ",'" .  $_POST["agenda_outros"] . "'";
    	 	
    	$sql.=  ")";

    	//echo  "Ins agenda". $sql. "</br>" ;
    		
    		
    	$result = $conn->query($sql);
    	//obtem o identificador da agenda inserida
    	$agenda_seq= $conn->insert_id;
	//echo $sql;
			
	// Verifica se foi checado o formulário de cliente ativo
	if (isset($_POST["tyse_seq"]))
	{
		$sql2= "Delete from agenda_service where aget_agenda_seq=" .  $agenda_seq ;
		$result = $conn->query($sql2);
				
		for($x = 0; $x < $arrlength; $x++)
		{
			$sql2= "INSERT INTO agenda_service (aget_agenda_seq,aget_tyse_seq) VALUES (";
			$sql2.=  $agenda_seq . " , " . $tyse_seq[$x] . ")";
			$result = $conn->query($sql2);
		}
	}	 
			
		
	//$path_inpulsecontrol, definido no arquivo db.php
	$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0&agenda_start_time=". $_POST["agenda_date"] ."'</script>";
	//$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0'</script>";
			
	}
	  	 
   	else
   		// update
   		{
   		$agenda_seq=$_POST["agenda_seq"];
   			
   		    	$sql= "UPDATE agenda 
   			SET 
   			agenda_sala="  . $_POST["agenda_sala"] . "," ;
   		// Se for treinamento, não cadastra cliente e profissional
   		if ($_POST["agenda_type"] == 4)
   			$sql.= "agenda_prof_seq=NULL, agenda_client_seq=NULL," ;
   		else
   			$sql.= "agenda_prof_seq="  . $_POST["prof_seq"] . ", agenda_client_seq="  . $_POST["client_seq"] . "," ;
   		
   		$sql.= "agenda_type="  . $_POST["agenda_type"] . ",";
   		$sql.= "agenda_start_time='" . $agenda_start_time. "',";
   		$sql.= "agenda_end_time='" . $agenda_end_time. "',";
   		$sql.= "agenda_concluded="  . $_POST["agenda_concluded"] . "," ;
   		$sql.= "agenda_type_pagto="  . $_POST["agenda_type_pagto"] . "," ;
   		$sql.= "agenda_desc='"  . $agenda_desc . "'," ;
   		$sql.= "agenda_valor="  . $_POST["agenda_valor"] . "," ;
   		$sql.= "agenda_desconto="  . $_POST["agenda_desconto"]. "," ;
   		$sql.= "agenda_cheque='"  . $_POST["agenda_cheque"] . "'" ;
   		
   		
   		// Verifica se foi checado o formulário do pacote de cliente
   		if (isset($_POST["secl_seq"]))
   			$sql.= ",agenda_secl_seq="  . $_POST["secl_seq"] . ", agenda_outros=NULL";
   	
   		// Verifica se foi checado o formulário do tratamento avulso do cliente
   		if (isset($_POST["agenda_outros"]))
   			$sql.= ",agenda_outros='"  . $_POST["agenda_outros"] . "', agenda_secl_seq=NULL";
   	
   		$sql.= " Where agenda_seq=" . $_POST["agenda_seq"];
   	
   		//echo $sql;
   		$result = $conn->query($sql);
   		
   		//atualiza a última data da vinda do cliente na In Pulse se foi concluído
   		
   		
   		//echo  "Update agenda". $sql. "</br>" ;
   		
   		
   		// Verifica se foi checado o formulário de cliente ativo
   		if (isset($_POST["tyse_seq"]))
   			{
   				$sql2= "Delete from agenda_service where aget_agenda_seq=" .  $agenda_seq ;
   				$result = $conn->query($sql2);
   			
   				for($x = 0; $x < $arrlength; $x++)
   				{
   				$sql2= "INSERT INTO agenda_service (aget_agenda_seq,aget_tyse_seq) VALUES (";
   						$sql2.=  $agenda_seq . " , " . $tyse_seq[$x] . ")";
   						$result = $conn->query($sql2);
   				}
   			}
   			
   			
   			$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0&agenda_start_time=". $_POST["agenda_date"] ."'</script>";
   			//$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0'</script>";
   		//echo "update";
   	 
   }
  // 	Atualiza os dados de ligacao e cliente

	if($_POST["agenda_concluded"] =="3")
   		{
   		$sql= "UPDATE client client_data_ultimo_atend='". $agenda_start_time. "'";
   		$sql.= " WHERE  client_seq=" .  $_POST["client_seq"] ;
   		$result = $conn->query($sql);
   		}
   		
   			
   //verifica se é agendamento feito pelo telefone
   if (isset($_POST["clica_seq"]))
   {
   	if($_POST["agenda_concluded"] =="3")
   	{
   		$sql= "UPDATE client_call SET clica_last_agenda_seq=". $agenda_seq  . ", clica_comment='Inserido via sistema', clica_stali_seq=12,"  ;
   		$sql.= ", clica_next_agenda_seq=null  WHERE  clica_seq=" .  $_POST["clica_seq"] ;
   		//echo $sql;
   		$result = $conn->query($sql);
   	
   		$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq, hstat_agenda_seq) VALUES(12,";
   		$sql.="'Inserido via sistema','".  $agenda_start_time. "'," . $_POST["clica_seq"]. ",". $agenda_seq. ")";
   		$result = $conn->query($sql);
   		//echo $sql;
   	}
   	
   	if($_POST["agenda_concluded"] =="0")
   	{
   		$sql= "UPDATE client_call SET clica_next_agenda_seq=". $agenda_seq . ", clica_comment='Inserido via sistema', clica_stali_seq=3"  ;
   		$sql.= " WHERE  clica_seq=" .  $_POST["clica_seq"] ;
   		//echo $sql;
   		$result = $conn->query($sql);
   	
   		$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq, hstat_agenda_seq) VALUES(3,";
   		$sql.="'Inserido via sistema','".  $agenda_start_time. "'," . $_POST["clica_seq"]. ",". $agenda_seq. ")";
   		$result = $conn->query($sql);
   		//echo $sql;
   	}
   
   }//if clica_seq
   		
     
}

   
}
   mysqli_close($conn);
  echo $destination;

?>

