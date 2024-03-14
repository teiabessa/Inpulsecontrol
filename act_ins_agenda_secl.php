<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
//
session_start();
$user_seq= $_SESSION['user_seq'];

//Eu insiro uma nova agenda no banco
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
//var_dump($_POST);

$agenda_desc="";


//Coloca a data como padrao do php
$agenda_start_time= $_POST["agenda_date"]. " ". $_POST["agenda_start_time"] . ":00";
$agenda_end_time= $_POST["agenda_date"]. " ". $_POST["agenda_end_time"] .  ":00";
$agenda_seq=$_POST["agenda_seq"];
$agenda_sala=$_POST["agenda_sala"];

$agenda_valor=0;
$agenda_desconto=0;

//verifica se foi definido o desconto
if (isset($_POST["agenda_desconto"]))
{
	if(trim($_POST["agenda_desconto"])!="")
		$agenda_desconto=$_POST["agenda_desconto"];
}
//var_dump($_POST);

// Verifica se a opção é de apagar o agendamento
if (isset($_POST["b_delete"]))
{ 

	$sql= "Update agenda set agenda_seq=agenda_seq*-1 where agenda_seq=" . $_POST["agenda_seq"];
	$result = $conn->query($sql);
	$sql="INSERT INTO agenda_log (log_action,log_comments,log_agenda_seq, log_user_seq) VALUES(1,'Apagado'," .$agenda_seq . ", " . $user_seq . ")";
	$result = $conn->query($sql);
	$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0&agenda_start_time=". $_POST["agenda_date"] ."'</script>";
	//$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0'</script>";
	
}
else 
{

	$tyse_seq=array();
	$aget_value=array();
	
	// Verifica se foi checado o formulário do pacote de cliente
	if ($_POST["secl_seq"] != "")
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
		//obtém o valor do tratamento passado
		$aget_value=$_POST["aget_value"];
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
//echo "dest"; 
if (trim($destination) == "")
{
	//se for avulso, pega o valor correto
	if($_POST["agenda_type"] == 1)
	{
		$agenda_valor=$_POST["agenda_valor"];
		$agenda_parcelas=$_POST["agenda_parcelas"];
		$agenda_cheque=$_POST["agenda_cheque"];
	}
	else
	{ 
		$agenda_valor=0;
		$agenda_parcelas=1;
		$agenda_cheque="";
	}
		

	// prepara para inserir ou atualizar o agendamento

// Se o agenda_seq=0, é para inserir
 if ($agenda_seq == "0")
  {
  	
  	
  	if (isset($_POST["tyse_seq"]))
  	{ 
  		for($x = 0; $x < $arrlength; $x++){	
  		//recalcula o valor do tratamento
  			$agenda_valor=$aget_value[$x]+$agenda_valor;  			
  		
      }
      // faz o calculo do desconto para o cliente
      if($agenda_valor!=0 && $agenda_desconto !=0)
         $agenda_valor=$agenda_valor-($agenda_valor*($agenda_desconto/100));
            
  	 
  	}
  			
  	
  	
  	
	$sql= "INSERT INTO agenda (agenda_pvend_seq,agenda_desconto,agenda_prof_log,agenda_log_comments, agenda_desc,agenda_valor,agenda_parcelas,agenda_cheque,agenda_sala, agenda_prof_seq, agenda_client_seq, agenda_type, agenda_start_time, agenda_end_time, agenda_concluded, agenda_type_pagto";
	
	// Verifica se foi checado o formulário do pacote de cliente 
	if (isset($_POST["secl_seq"]))		
		
		$sql.= ",agenda_secl_seq";
		
	if (isset($_POST["agenda_outros"]))
		$sql.= ",agenda_outros";
			
	$sql.=  ") VALUES (". $_POST["agenda_pvend_seq"] . ",". $agenda_desconto . ",". $_POST["agenda_prof_log"] . ",'". $_POST["agenda_log_comments"]. "','" .$agenda_desc . "'," . $agenda_valor . ", " . $agenda_parcelas . ",'" . $agenda_cheque.  "'," . $_POST["agenda_sala"] ;
			
		$sql.= "," . $_POST["prof_seq"] . "," .  $_POST["client_seq"]; 
		$sql.="," . $_POST["agenda_type"]. ",'" . $agenda_start_time. "','".  $agenda_end_time. "',". $_POST["agenda_concluded"]. ",". $_POST["agenda_type_pagto"];
             
    		
    	// Verifica se foi checado o formulário de cliente ativo
    	if (isset($_POST["secl_seq"]))
    		$sql.= "," . $_POST["secl_seq"];
    		
    	// Verifica se foi checado o formulário de cliente ativo
    	if (isset($_POST["agenda_outros"]))
    		$sql.= ",'" .  $_POST["agenda_outros"] . "'";
    	 	
    	$sql.=  ")";

    	echo  "Ins agenda". $sql. "</br>" ;
    		
    		
    	$result = $conn->query($sql);
    	//obtem o identificador da agenda inserida
    	$agenda_seq= $conn->insert_id;
    	echo "<br> agenda_seq=". $agenda_seq;
	
    	echo $sql;
	
	
	// Verifica se foi definido o campo tratamento para continuar
	// Insere os tratamentos da agenda 
	if (isset($_POST["tyse_seq"]))
	{
		
		for($x = 0; $x < $arrlength; $x++)
		{
									
			$sql2= "INSERT INTO agenda_service (aget_agenda_seq,aget_tyse_seq, aget_value) VALUES (";
			$sql2.=  $agenda_seq . " , " . $tyse_seq[$x]. " , " . $aget_value[$x] . ")";
			$result = $conn->query($sql2);
		}
			
	}
	// Se tiver algum pacote, também inclui
	if ($_POST["secl_seq"] != "")
	{
		
		/*	$sql_secl="SELECT secl_valor_vendido FROM pacote_servico_cliente where secl_seq=".$_POST["secl_seq"];
		$result_secl = $conn->query($sql_secl);
		
		while($row = $result_secl->fetch_assoc())
		{
		if (is_numeric($row["secl_valor_vendido"]))
				$valor_pacote=$row["secl_valor_vendido"];
			else 
				
			*/
			$valor_pacote=0;
			$sql2= "INSERT INTO agenda_service (aget_agenda_seq,aget_tyse_seq, aget_value, aget_secl_seq) VALUES (";
			$sql2.=  $agenda_seq . " , NULL , " . $valor_pacote . "," . $_POST["secl_seq"] . ")";
			//$result = $conn->query($sql2);
			echo sql2;
		//}
	}
	//echo "antes envio email"; 		
		 
	require_once "rotinas/act_email_conf_agenda.php";
	
	//echo "depois envio email";
    		
	//$path_inpulsecontrol, definido no arquivo db.php
	
  $destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0&agenda_start_time=". $_POST["agenda_date"] ."'</script>";
  

	//$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0'</script>";
		//require_once "rotinas/act_email_conf.php";
	}
	  	 
   	else
   		// update
   		{
   		$agenda_seq=$_POST["agenda_seq"];
   		
   			// Verifica se foi definido o campo tratamento para continuar
   			if (isset($_POST["tyse_seq"]))
   			{
   				$sql2= "Delete from agenda_service where aget_agenda_seq=" .  $agenda_seq ;
   				$result = $conn->query($sql2);
   				//echo "<br> agenda_service=" . $sql2;
   				
   		        
   				$agenda_valor=0;
   				for($x = 0; $x < $arrlength; $x++)
   				{
   					if((trim($aget_value[$x])!= "") || (trim($aget_value[$x])!= 0)) 
   						//recalcula o valor do tratamento
   						$agenda_valor=$aget_value[$x]+$agenda_valor;
   						
   						$sql2= "INSERT INTO agenda_service (aget_agenda_seq,aget_tyse_seq, aget_value) VALUES (";
						$sql2.=  $agenda_seq . " , " . $tyse_seq[$x]. " , " . $aget_value[$x] . ")";
   						$result = $conn->query($sql2);
   					
   				}
   				
   				 if($agenda_valor!=0 && $agenda_desconto !=0)
   					$agenda_valor=$agenda_valor-($agenda_valor*($agenda_desconto/100));
   			}
   				
   			
   			
   			
   			// Se tiver algum pacote, insere na tabela
   			if ($_POST["secl_seq"] != "")
			{
					$valor_pacote=0;
					$sql2= "INSERT INTO agenda_service (aget_agenda_seq, aget_value, aget_secl_seq) VALUES (";
					$sql2.=  $agenda_seq . " , " . $valor_pacote . "," . $_POST["secl_seq"] . ")";
					$result = $conn->query($sql2);
					echo $sql2;
   				
   		
			}
			else
				echo "Nao entrou no pacote <br>";
   			
   			//Se ocorreu mudança no horário ou dia, envia novamente o email com agendamento para o cliente
   			$sql5="SELECT agenda_start_time, agenda_end_time FROM agenda WHERE agenda_seq=".$agenda_seq;
   			$result5 = $conn->query($sql5);
   			
   			if ($result5->num_rows > 0){
   				while ($linha5 = $result5->fetch_assoc())
   				{
   						
   					$banco_start_time=$linha5["agenda_start_time"];
   					$banco_end_time=$linha5["agenda_end_time"];
   				
   				}
   			}
   			
   			//echo "Aqui3". $banco_start_time. " ". $agenda_start_time. "<br>";
   			 
   			
   			
   			

   		    $sql= "UPDATE agenda 
   			SET 
   			agenda_sala="  . $_POST["agenda_sala"] . "," ;
   		// Se for treinamento, não cadastra cliente e profissional
   		/*if ($_POST["agenda_type"] == 4)
   			$sql.= "agenda_client_seq=NULL," ;
   		else
   		*/
   			$sql.= "agenda_prof_seq="  . $_POST["prof_seq"] . ", agenda_client_seq="  . $_POST["client_seq"] . "," ;
   		
   		$sql.= "agenda_type="  . $_POST["agenda_type"] . ",";
   		$sql.= "agenda_start_time='" . $agenda_start_time. "',";
   		$sql.= "agenda_end_time='" . $agenda_end_time. "',";
   		$sql.= "agenda_concluded="  . $_POST["agenda_concluded"] . "," ;
   		$sql.= "agenda_type_pagto="  . $_POST["agenda_type_pagto"] . "," ;
   		$sql.= "agenda_desc='"  . $agenda_desc . "'," ;
   		$sql.= "agenda_valor="  . $agenda_valor . "," ;
   		$sql.= "agenda_desconto="  . $agenda_desconto . ",";
   		$sql.= "agenda_parcelas="  . $agenda_parcelas. "," ;
   		$sql.= "agenda_cheque='"  . $agenda_cheque . "'," ;
   		$sql.= "agenda_log_comments='"  . $_POST["agenda_log_comments"] . "'," ;
   		$sql.= "agenda_pvend_seq="  . $_POST["agenda_pvend_seq"] . ",";   		
   		$sql.= "agenda_prof_log="  . $_POST["agenda_prof_log"] ;		
   		
   		
   		// Verifica se foi checado o formulário do pacote de cliente
   		//if (isset($_POST["secl_seq"]))
   			
   		// Verifica se foi checado o formulário do pacote de cliente
   		if ($_POST["secl_seq"] != "")
   			$sql.= ",agenda_secl_seq="  . $_POST["secl_seq"] . ", agenda_outros=NULL";
   	
   		// Verifica se foi checado o formulário do tratamento avulso do cliente
   		if (isset($_POST["agenda_outros"]))
   			$sql.= ",agenda_outros='"  . $_POST["agenda_outros"] . "', agenda_secl_seq=NULL";
   	
   		$sql.= " Where agenda_seq=" . $_POST["agenda_seq"];
   	
   		echo $sql;
   		//$result = $conn->query($sql);
   		
   		//atualiza a última data da vinda do cliente na In Pulse se foi concluído
   		
   		
   		//echo  "Update agenda". $sql. "</br>" ;
   		
   		
   		
   		 

   		if(($banco_start_time != $agenda_start_time)||($banco_end_time!= $agenda_end_time))
   		{
   			//echo "enviou email";
   			require_once "rotinas/act_email_conf_agenda.php";
   		}
   		//echo "<br>Aqui2". $banco_start_time. " ". $agenda_start_time. "<br>";
   		
   			$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0&agenda_start_time=". $_POST["agenda_date"] ."'</script>";
   		
   			//$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0'</script>";
   		//echo "update";
   	   
   }
  // 	Atualiza os dados de ligacao do cliente e  torna ativo

	if($_POST["agenda_concluded"] =="3")
   		{
   		$sql= "UPDATE client client_data_ultimo_atend='". $agenda_start_time. "',";
   		$sql.= " client_activate=1";
   		$sql.= " WHERE  client_seq=" .  $_POST["client_seq"] ;
   		$result = $conn->query($sql);
   		
   		/* chama o arquivo que envia email */
   		require_once 'rotinas/act_email_cuidados.php';
   		// so entra no caixa se for avulso e se o pagto não for nenhum
   		
   		if($_POST["agenda_type"] == 1 AND $_POST["agenda_type_pagto"]!= 0)
   		{
   			$caixa_data=$agenda_end_time;
   			$caixa_valor=$agenda_valor;
   			//divide o valor pela qtd de parcelas
   			if($agenda_parcelas > 1)
   				$caixa_valor=$agenda_valor/$agenda_parcelas;
   			
   			$sql2= "SELECT caixa_seq, caixa_parcelas from lancamento_caixa where caixa_agenda_seq=" .  $agenda_seq ;
   			echo $sql2;
   			$result1 = $conn->query($sql2);	
   			// Apaga do caixa se já tiver inserido   			
   			
   			if ($result1->num_rows != 0)
   			{
   				$sql3= "DELETE FROM lancamento_caixa where caixa_agenda_seq=" .  $agenda_seq ;
   				//echo $sql3;
   				$result3 = $conn->query($sql3);
   			}
   			
   		
   			// faz o loop de acordo com a qtd de parcelas
   			for($xparc = 0; $xparc < $agenda_parcelas; $xparc++)
   				{   			
   			 		$sql = "INSERT INTO lancamento_caixa (caixa_tipo,caixa_client_seq,caixa_valor,caixa_comment, caixa_desc, caixa_data, caixa_forma_pagto, caixa_parcelas, caixa_prof_seq, caixa_agenda_seq)";
   					$sql.= "VALUES (0," . $_POST["client_seq"] . " , " . $caixa_valor . " , '" . $_POST["agenda_log_comments"] . "' , '" . $agenda_desc  . "' , '" . $caixa_data  . "'," . $_POST["agenda_type_pagto"]  . "," . $agenda_parcelas . ",". $_POST["agenda_prof_log"] . ",". $agenda_seq  . ")";
   					//echo $sql;
   					$result = $conn->query($sql);
   					//prepara a variavel da data para a inserçao no próximo periodo de percelas   				
   					$sql="select date_add('". $caixa_data. "', INTERVAL 30 DAY) as dia_mais";
   					$result = $conn->query($sql);
   					
   					while($row = $result->fetch_assoc())
   					$caixa_data=$row["dia_mais"];
   						
   				}// fecha o for   //echo "<br>". $sql;  	
   			}
   		}// if da verificação se já ta no caixa
   		
   		
   		$clica_seq=0;

   		// verifica se o cliente está cadastrado na lista de ligaçoes e atualiza o status, pega apenas 1 ligação
   		$sql5= "SELECT clica_seq from client_call where clica_client_seq=" .  $_POST["client_seq"] . " LIMIT 1";
   		
   		$result5 = $conn->query($sql5);
   		// verifica se tem registro de ligações para esse cliente
   		if ($result5->num_rows > 0)
   		{
   			while ($linha5 = $result5->fetch_assoc())
   			
   				$clica_seq=$linha5["clica_seq"];
   			
   		}
   		
   		// Verifica se tem registro na tabela ligações
   		// != 0 Tem registro
	   	if($clica_seq !=0)
	   	{  
	   		/* clica_stali_seq=4 cliente fez tratamento
	   		
	   		0=Clientes inat
	   		1=Clientes ultimos 3 mes
	   		2=cancelamento
	   		3=Acompanhamento
	   		*/

	   		if($_POST["agenda_concluded"] =="3")
	   			{
	   			$sql= "UPDATE client_call SET clica_motivo=3,clica_last_date='".  $agenda_start_time. "',clica_next_agenda_seq=". $agenda_seq  . ", clica_comment='". $agenda_desc. "', clica_stali_seq=4,clica_activate=1"  ;
	   			$sql.= " WHERE  clica_seq=" .  $clica_seq ;
	   			//echo $sql;
	   			$result = $conn->query($sql);
	   	
	   			$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq, hstat_agenda_seq) VALUES(4,'";
	   			$sql.=$agenda_desc . "','".  $agenda_start_time. "'," . $clica_seq. ",". $agenda_seq. ")";
	   			$result = $conn->query($sql);
	   			}
	   		
	   		
	   		
	   		// se for cancelado, insere na tabela de ligações para a profissional ligar
	   		 
	   		if($_POST["agenda_concluded"] =="2")	   		 
	   		 
	   		{
	   			// prepara string do cancelamento
	   			$txtcancel= "Cancelou na data ". $agenda_start_time. "<br> do tratamento: ". $agenda_desc;
	   			//seta clica_motivo =cancelado 
	   			
	   			$sql= "UPDATE client_call SET  clica_motivo=2,clica_last_date='".  $agenda_start_time. "', clica_comment='". $txtcancel. "', clica_stali_seq=1,clica_activate=1"  ;
	   			$sql.= " WHERE  clica_seq=" .  $clica_seq ;	   				
	   			$result = $conn->query($sql);
	   			
	   			$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq, hstat_agenda_seq) VALUES(1,'";
	   			$sql.=$agenda_desc . "','".  $agenda_start_time. "'," . $clica_seq. ",". $agenda_seq. ")";
	   			$result = $conn->query($sql);
	   		}
	   		 
	   	
	   		// clica_stali_seq=6 cliente agendado
	   		if($_POST["agenda_concluded"] =="0")
	   		{
	   			$sql= "UPDATE client_call SET clica_motivo=3,clica_next_agenda_seq=". $agenda_seq . ", clica_comment='". $agenda_desc. "', clica_stali_seq=6,clica_activate=1"  ;
	   			$sql.= " WHERE  clica_seq=" .  $clica_seq ;
	   			//echo $sql;
	   			$result = $conn->query($sql);
	   	
	   			$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq, hstat_agenda_seq) VALUES(6,'";
	   			$sql.=$agenda_desc . "','".  $agenda_start_time. "'," . $clica_seq. ",". $agenda_seq. ")";
	   			$result = $conn->query($sql);
	   			//echo $sql;   		  		
	   		}
   		}// if clica_seq
   
   	
   	else
   	{
   		
   		// se for cancelamento
   		if($_POST["agenda_concluded"] =="2")
   		 
   		{
   			// prepara string do cancelamento
   			$txtcancel= "Cancelou na data ". $agenda_start_time. "<br> do tratamento: ". $agenda_desc;
   			//seta clica_motivo =cancelado = 2
   			
   			$sql3= "INSERT INTO client_call ( clica_last_date, clica_prof_seq, clica_client_seq, clica_stali_seq, clica_motivo,  clica_comment) values ";
   			$sql3.="('".  $agenda_start_time. "'," . $_POST["prof_seq"] . ",". $_POST["client_seq"] .",1 , 2, '". $txtcancel.  "' )";
   		
   			$result = $conn->query($sql3);
   			
   			echo $sql3;
   			 
    		//obtem o identificador da agenda inserida
    		$clica_seq= $conn->insert_id;
   			
   			$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq, hstat_agenda_seq) VALUES(1,'";
   			$sql.=$txtcancel . "','".  $agenda_start_time. "'," . $clica_seq. ",". $agenda_seq. ")";
   			$result = $conn->query($sql);
   			
   			
   		}
   		
   		// se foi executado - faz acompanhamento
   		if($_POST["agenda_concluded"] =="3")
   		{
   			$sql3= "INSERT INTO client_call (clica_last_date, clica_prof_seq, clica_client_seq, clica_stali_seq, clica_motivo,  clica_comment) values ";
   			$sql3.="('".  $agenda_start_time. "'," . $_POST["prof_seq"] . ",". $_POST["client_seq"] .",4 , 3, '". $agenda_desc.  "' )";
   			//echo $sql;
   			$result = $conn->query($sql);

   			//obtem o identificador da agenda inserida
   			$clica_seq= $conn->insert_id;
   			 
   			$sql="INSERT INTO hist_stat_lig (hstat_stali_seq,hstat_comment, hstat_date, hstat_clica_seq, hstat_agenda_seq) VALUES(4,'";
   			$sql.=$agenda_desc . "','".  $agenda_start_time. "'," . $clica_seq. ",". $agenda_seq. ")";
   			$result = $conn->query($sql);
   		}
   		
   		 
   		 
   	}	// else clica_seq != 0
    
   //insere registro de log no sistema
   
   //var_dump($_SESSION['user_seq']);   
   
   $sql="INSERT INTO agenda_log (log_action,log_comments,log_agenda_seq, log_user_seq) VALUES(". $_POST["agenda_concluded"]. ",'" . $_POST["agenda_log_comments"] . "'," .$agenda_seq . ", " . $user_seq . ")";
   $result = $conn->query($sql);
   echo $sql;		
     
}
 


mysqli_close($conn);
//echo $destination;
?>

