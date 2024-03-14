<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';


// Eu apresento o formulário de data da tela de agendamento

// obtém o valor passado da datta a ser pesquisada
if (isset($_REQUEST['agenda_start_time']))

	$agenda_start_time= $_REQUEST['agenda_start_time'];
else
	$agenda_start_time=date('Y-m-d');

//echo $_REQUEST['action_report'];

// obtém o valor passado da datta a ser pesquisada
//if (isset($_REQUEST['action_report']))

//	$action_report= $_REQUEST['action_report'];
//else
	$action_report="1";

$field='<div class="divsearchreport"> <form ACTION="index.php?menu_seq=3" METHOD="POST" id="formdate2" name="formdate2">';
$field.='Data Pesquisa:<input  type="date" value="'. $agenda_start_time . '" class="frmclient" name="agenda_start_time" id="agenda_start_time">';
 echo $field;
 
 // Campo select com opção de agendamento
 $stroption='Tipo de Report: <select class="frmclient" name="action_report" id="action_report" >';
 
 $stroption.='<option value= "1" ';
 if ($action_report == "1")
 	$stroption.='selected';
 $stroption.='>Clientes que venceram o ciclo do tratamento</option>';
  
 $stroption.='<option value= "2"';
 if ($action_report == "2")
 	$stroption.='selected';
 $stroption.='>'. utf8_encode('Email Aniversário'). '</option>';
  
 $stroption.='<option value= "3"';
 if ($action_report == "3")
 	$stroption.='selected';
 $stroption.='>'. utf8_encode('Enviar Email Confirmação de Foto'). '</option>';
 
 $stroption.='<option value= "4"';
 if ($action_report == "4")
 	$stroption.='selected';
 $stroption.='>'.  utf8_encode('Enviar SMS Confirmação de Foto').'</option>';
  
 $stroption.= '</select>';
 echo $stroption;
  
 //$field='<input type="submit" class="frmsubmit"  value="Buscar" name="busca_submit">';
 $field.='<input type="submit" class="frmsubmit"  value="Executar" name="exec_submit"> </form></div>';
 echo $field;
 
 
 // Verifica se é  a função de exibir busca apenas
 //if (isset($_REQUEST["busca_submit"]))
 //{
 	sql= "SELECT  distinct client_name,client_email, agenda_start_time,tyse_desc From agenda, client, type_service,agenda_service where client_seq=agenda_client_seq ";
	$sql.= "AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq AND datediff('". $agenda_start_time. "',agenda_start_time) > tyse_duracao";
	echo $sql;
	
	/*
	$result = $conn->query($sql);

	echo "<div id='div_report'>";

	$stroption='<div class="linha_report_title">Nome</div> ';
	$stroption.='<div class="linha_report_title">Email</div> ';
	$stroption.='<div class="linha_report_title">Tratamento</div> ';
	$stroption.='<div class="linha_report_title">Data Tratamento</div> '; 
	$stroption.='<div class="clear"></div>';

	if ($result->num_rows > 0)
	{
			while ($linha = $result->fetch_assoc())
			{
				$stroption.='<div class="linha_report">'. $linha["client_name"].  '</div>';
				$stroption.='<div class="linha_report">'. $linha["client_email"].  '</div>';
				$stroption.='<div class="linha_report">'. $linha["tyse_desc"].  '</div>';
				$stroption.='<div class="linha_report">'. $linha["agenda_start_time"].  '</div>';
			
			}
  	}
*/
echo $stroption;
echo " </div>";

//}

// Verifica se é o momento de executar a pesquisa
//if (isset($_REQUEST["exec_submit"]))

//	echo "exec";



?>