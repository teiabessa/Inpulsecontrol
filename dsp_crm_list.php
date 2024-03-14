<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu listo todo o crm

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$class_crm="linha_crm1";
$client_seq=array();

echo "<div id='novoagendamento'><a href='dsp_form_crm.php?menu_seq=4'>Novo Registro</a></div>";


//verifica se foi definido o profissional
if (isset($_REQUEST["prof_seq"]))
	$prof_seq=$_REQUEST['prof_seq'];
else
	$prof_seq="";


 	$sql = "SELECT  prof_name,crm_seq,crm_client_name, crm_contato, crm_historico, crm_acao_futura, scrm_desc, crm_situacao_cliente, crm_prof_seq,";
 	$sql .= "crm_indicacao FROM CRM,status_crm, profissional WHERE crm_status_conversa=scrm_seq AND crm_prof_seq=prof_seq and crm_seq > 0 ORDER BY crm_situacao_cliente,crm_prof_seq";
 	 
 	$result = $conn->query($sql);
 		
 		echo "<div id='div_report_crm'>";
 		
 		$stroption='<div class="linha_crm_list">Cliente</div> ';
 		$stroption.='<div class="linha_crm_list">'. utf8_encode("Histórico").'</div> ';
 		$stroption.='<div class="linha_crm_list">'. utf8_encode("Ação Futura"). '</div> ';
 		$stroption.='<div class="linha_crm_list">'. utf8_encode("Status Conversa"). '</div> ';
 		$stroption.='<div class="linha_crm_list">'. utf8_encode("Profissional"). '</div> ';
 		$stroption.='<div class="clear"></div>';
 		
 		if ($result->num_rows)
 		{
 			// Atribui o código HTML para montar uma tabela
 			while ($linha = $result->fetch_assoc())
 			{
 		           	//Seta a cor da linha de acordo com o status do registro do CRM
 		           	$class_crm="linha_crm". $linha["crm_situacao_cliente"]; 		             
 		            
 	   			    $stroption.='<div class="'.$class_crm.'">'. $linha["crm_client_name"]."<br>". $linha["crm_contato"]. '</div>';
 					$stroption.='<div class="'.$class_crm.'"><a target=”_blank” href="dsp_form_crm.php?menu_seq=4&crm_seq='. $linha["crm_seq"]. ' " >'. $linha["crm_historico"].  '</a></div>';
 				
 					$stroption.='<div class="'.$class_crm.'">'. $linha["crm_acao_futura"]. '</div>';
 					$stroption.='<div class="'.$class_crm.'">'. $linha["scrm_desc"].  '</div>';
 					$stroption.='<div class="'.$class_crm.'">'. $linha["prof_name"].  '</div>';
 				
 					$stroption.='<div class="clear"></div>';
 				
 				
 			}//fecha while
 		}//fecha result
 	
 		echo $stroption;
 		 
 		echo " </div>";
 
 	
		
?>
   