
<?php
//Eu insiro um novo pacote no banco

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$peqi_tyse_seq= $_POST["peqi_tyse_seq"];
$peqi_qtd_sessoes= $_POST["peqi_qtd_sessoes"];
$peqi_desc= $_POST["peqi_desc"]; 
$arrlength = count($peqi_tyse_seq);
$client_seq=$_POST["client_seq"];
$secl_desc="";
$v_secl_days_weak=array();
$v_secl_days_weak=$_POST["secl_days_weak"];
$maxweak=count($v_secl_days_weak);
$qtd_sessao=$_POST["secl_qtd_sessao_realizada"];

$agenda_sala=$_POST["secl_agenda_sala"];

if (isset($_POST["secl_seq"]))
{
	$secl_seq=$_POST["secl_seq"];  /// Obtém  o identificador do pacote

}

$agenda_start_time= $_POST["secl_data_agendamento"]. " ". $_POST["agenda_start_time"] . ":00";
$agenda_end_time= $_POST["secl_data_agendamento"]. " ". $_POST["agenda_end_time"] .  ":00";
$agenda_date_antes= $_POST["secl_data_agendamento"];

$secl_days_weak="";
//Obtém a descrição do pacote
for($x = 0; $x <$maxweak; $x++)
{//coloquei essa condicao para colocar "," na lista
 if ($x ==($maxweak-1))
 	$secl_days_weak.=$v_secl_days_weak[$x] ;
else
	$secl_days_weak.=$v_secl_days_weak[$x]. "," ;
}

//Obtém a descrição do pacote
for($x = 0; $x < $arrlength; $x++)
	$secl_desc.= $peqi_qtd_sessoes[$x] . " " . $peqi_desc[$x] . " ";


//print_r($_POST);

// Atualiza ou insere um novo registro
//echo $secl_seq;
if (isset($_POST["ins_secl"]))

{  
	
	$sql= "INSERT INTO pacote_servico_cliente (secl_desc, secl_client_seq, secl_prof_seq, secl_valor_vendido, secl_qtd_sessao_realizada, secl_contrato_seq, secl_days_weak,secl_data_agendamento, secl_end_time, secl_agenda_sala,secl_agenda_type_pagto,secl_agenda_cheque) ";
    $sql.="VALUES ('"  . $secl_desc . "'," .  $_POST["client_seq"] .  "," . $_POST["prof_seq"] . "," .  $_POST["secl_valor_vendido"]. "," . $_POST["secl_qtd_sessao_realizada"]. "," .  $_POST["secl_contrato_seq"]. ",'".  $secl_days_weak .   "','" .  $agenda_start_time.   "','" .  $agenda_end_time .  "',".  $_POST["secl_agenda_sala"] .  "," .  $_POST["secl_agenda_type_pagto"] .  ",'" . $_POST["secl_agenda_cheque"] . "')";
    
    //echo $sql."<br>";
    $result = $conn->query($sql);
    
    //obtem o identificador da client inserida
    $secl_seq= $conn->insert_id;
    //Insere todos os formulários de telefone do cliente
	// Faz alteração na tabela que contém os telefones dos clientes
	for($x = 0; $x < $arrlength; $x++)
	{
		$sql= "INSERT INTO pacotes_type_services (peqi_tyse_seq, peqi_qtd_sessoes, peqi_desc, peqi_secl_seq ) ";
    	$sql.="VALUES (" . $peqi_tyse_seq[$x] . "," .  $peqi_qtd_sessoes[$x]. ",'" .  $peqi_desc[$x] . "',". $secl_seq .")";
    	$result = $conn->query($sql);
	}
	//echo $sql."<br>";
  	
       $destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=2&client_seq=". $client_seq . "&secl_seq=" . $secl_seq ."'</script>";		 	
}

// Verifica se é para atualizar pacotes
if  (isset($_POST["upd_secl"]))  
{	
//primeiro verifica se foi definido pacote para editar
	$sql= "Update pacote_servico_cliente  SET secl_desc='". $secl_desc . "',";
	$sql.="secl_client_seq=". $_POST["client_seq"] . "," ;
	$sql.="secl_prof_seq=". $_POST["prof_seq"] . "," ;
	$sql.="secl_valor_vendido=". $_POST["secl_valor_vendido"] . "," ;
	$sql.="secl_qtd_sessao_realizada=". $qtd_sessao . "," ;
	$sql.="secl_contrato_seq=". $_POST["secl_contrato_seq"] . "," ;
	$sql.="secl_days_weak='". $secl_days_weak . "'," ;
	$sql.="secl_data_agendamento='". $agenda_start_time . "'," ;
	$sql.="secl_end_time='".$agenda_end_time . "'," ;
	$sql.="secl_agenda_sala=". $_POST["secl_agenda_sala"] . "," ;
	$sql.="secl_agenda_type_pagto=". $_POST["secl_agenda_type_pagto"] . "," ;
	$sql.="secl_agenda_cheque='". $_POST["secl_agenda_cheque"] . "'" ;
	$sql.=" Where secl_seq=". $secl_seq ;
	
	
	$result = $conn->query($sql);
	echo $sql . "<br>";
	
	
	$sql= "DELETE FROM pacotes_type_services Where peqi_secl_seq=". $secl_seq;
	//echo $sql . "<br>";
	$result = $conn->query($sql);
	
	// Faz alteração na tabela que contém os telefones dos clientes
    	for($x = 0; $x < $arrlength; $x++)
	{
		$sql= "INSERT INTO pacotes_type_services (peqi_tyse_seq, peqi_qtd_sessoes, peqi_desc, peqi_secl_seq ) ";
    	$sql.="VALUES (" . $peqi_tyse_seq[$x] . "," .  $peqi_qtd_sessoes[$x]. ",'" .  $peqi_desc[$x] . "',". $secl_seq .")";
    	$result = $conn->query($sql);
	
	}	
	
        $destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=2&client_seq=". $client_seq . "&secl_seq=" . $secl_seq ."'</script>";	
}


// Inserir agenda automatica
if (isset($_POST["ins_agenda"]))
{
	$dateinc=0;
	$n=0;
	$i=0;
        $agenda_seq=0;
	
	$diasemana_n=date('w',strtotime($agenda_start_time));
	//$key = array_search($diasemana_n, $v_secl_days_weak); //Retorna a posicao que encontrou o primeiro dia da semana;
	
			
	$n=0;
	//data inicial
	$valor=$_POST["secl_valor_vendido"]/$qtd_sessao;

	//cria agenda de acordo com a quantidade de sessao
	for($x = 0; $x < $qtd_sessao; $x++)
	{
        	$destination="";
		// Invoca o arquivo que faz a consistência da data e hora 
		
		require 'act_check_consistencia.php';
                //echo $destination; 
		
                if (trim($destination) != "")
			break;

		//   echo "valor data".$agenda_start_time. "<br>";
                        
		// entra nesse loop e so sai quansdo a data fica igual a próxima
	
			$sql= "INSERT INTO agenda (agenda_concluded,agenda_desc,agenda_valor,agenda_prof_seq, agenda_client_seq, agenda_type, agenda_start_time, agenda_end_time";
			$sql.= ",agenda_secl_seq,agenda_sala,agenda_type_pagto,agenda_cheque)";
			$sql.=  "  VALUES (0,'".$secl_desc . "'," .$valor .   "," . $_POST["prof_seq"] . "," .  $_POST["client_seq"] . ", 2,'" . $agenda_start_time. "','". $agenda_end_time . "'";
			$sql.= "," .$secl_seq .   ",".  $_POST["secl_agenda_sala"] .  "," .  $_POST["secl_agenda_type_pagto"] .  ",'" . $_POST["secl_agenda_cheque"] . "')";
	
			$result = $conn->query($sql);
                        //obtem o identificador da agenda inserida
    	                $agenda_seq= $conn->insert_id;

                        //insere os tipos de tratamentos
        		for($xn = 0; $xn < $arrlength; $xn++)
	 		{
				$sql2= "INSERT INTO agenda_service (aget_agenda_seq,aget_tyse_seq) VALUES (";
				$sql2.=  $agenda_seq . " , " . $peqi_tyse_seq[$xn] . ")";
				$result2 = $conn->query($sql2);
			}

			echo "<br>" ."Insere Agenda ". "<br>";
                        echo $sql2;
			
		
			// Se for só 1 registro, nao precisa fazer muita conta
			if ($maxweak == 1)
			{
			
				$sql2="select date_add('". $agenda_start_time. "', INTERVAL 7 DAY) as ini_date, date_add('". $agenda_end_time. "', INTERVAL 7 DAY) as end_date";
				$result = $conn->query($sql2);
				while($row = $result->fetch_assoc())
				{
					$agenda_start_time=$row["ini_date"];
					$agenda_end_time=$row["end_date"];
                                 echo "Agenda start time=" .$agenda_start_time. "<br>";
				}
			
			}
			else
			{
				if ($n==($maxweak-1))
					$n=0;
			
				else 
			
					$n=$n+1;
			
			
				// Faz o loop até  chegar no proximo dia da semana que vai ter a sessao
                                // Se começa com segunda, vai ate quarta e sai
			        echo "<br>". "Dia Semana:". $diasemana_n . "V_secl:". $v_secl_days_weak[$n]. "<br>"; 
				while ($diasemana_n != $v_secl_days_weak[$n])
				{
					$sql2="select date_add('". $agenda_start_time. "', INTERVAL 1 DAY) as ini_date, date_add('". $agenda_end_time. "', INTERVAL 1 DAY) as end_date";
					$result = $conn->query($sql2);
					while($row = $result->fetch_assoc())
					{
						$agenda_start_time=$row["ini_date"];
						$agenda_end_time=$row["end_date"];
                                                //echo "Start time=" .$agenda_start_time. "<br>";
					}
				
				 	$diasemana_n=date('w',strtotime($agenda_start_time));
				
				}
                                	
			
				//echo "<br>dia semana depois".$lista_semana[$diasemana_n] ."n=" .  $diasemana_n . "array=".$v_secl_days_weak[$n];
			
				
                       
			}//else max weak
            	
	
	}// for das sessoes
  	//Se o link destino tiver cheio, é que veio com erro do check da data
	if (trim($destination) == "")
	   $destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=2&client_seq=". $client_seq . "&secl_seq=" . $secl_seq ."'</script>";	
		
}
	

mysqli_close($conn);
echo $destination;


?>
