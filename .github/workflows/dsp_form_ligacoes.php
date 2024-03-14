<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu faço a busca no banco de dados de todos os registros de clientes

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$client_seq=array();

//verifica se foi definido o cliente
if (isset($_REQUEST["client_seq"]))
	$client_seq=$_REQUEST['client_seq'];
else 
	$client_seq[0]="";
$arrlength = count($client_seq);

//verifica se foi definido o profissional
if (isset($_REQUEST["prof_seq"]))
	$prof_seq=$_REQUEST['prof_seq'];
else
	$prof_seq="";

$stroption='<div id="frmligacoesprofissionais"> <FORM ACTION="index.php?menu_seq=4" METHOD="POST" id="formagendaligacoes" name="formagendaligacoes">';
echo $stroption;

if (($_SESSION['login'] == "abrbrandao") || ($_SESSION['login'] == "teiabak"))
{
	
$sql2 = "Select clica_client_seq FROM client_call order by clica_seq"; 	
$result = $conn->query($sql2);
$clica_client_seq="";
if ($result->num_rows > 0)
{
	while ($linha = $result->fetch_assoc())
	{
		$clica_client_seq.= $linha["clica_client_seq"] . ",";
	
	}
}
//retira a últma vírgula

$tam_field= strlen($clica_client_seq)-1 ;
$clica_client_seq=substr ($clica_client_seq ,0 ,$tam_field );


 $stroption=' Cliente: <select  multiple  name="client_seq[]" id="client_seq"  size="20">';
 //Busca o cliente no banco
 $sql = "Select * FROM client  where client_seq>0 and client_seq not in(". $clica_client_seq.   ") ORDER BY client_name";
 $result = $conn->query($sql);

 if ($result->num_rows > 0)
 {
 		$stroption.='<option value="">';
 	// Monta o select do cliente
	 while ($linha = $result->fetch_assoc())
 	{
 			$tel_client="";
 			$sql2 = "Select  tecl_operadora, tecl_desc FROM telefone_clients where tecl_client_seq=".$linha["client_seq"] ;
 			//echo $sql2;
 			$result2 = $conn->query($sql2);
 			if ($result2)
 			{
 				while ($linha2 = $result2->fetch_assoc())
 					// verifica se foi preenchido a operadora
 					if(trim($linha2["tecl_operadora"]))
 						$tel_client.=  "   --   "  .  $linha2["tecl_operadora"];
 			}
 				
 		$stroption.='<option value="'. $linha["client_seq"]. '"';
 		if ($linha["client_seq"] == $clica_client_seq)
 			$stroption.='selected';
 		$stroption.='>'. $linha["client_name"]. $tel_client.  '</option>';
 
 	}
 	$stroption.='</select>';
 	echo $stroption;
 }
} 

 
 // Campo das profissionais
 $stroption='Profissional:';
 $sql = "Select * FROM profissional ORDER BY prof_name";
 $result = $conn->query($sql);
 $stroption.=' <select  name="prof_seq" id="prof_seq" >';
 	
 $stroption.='<option value="">';
 while ($linha = $result->fetch_assoc())
 {
 	$stroption.='<option value="'. $linha["prof_seq"]. '"';
 	if ($linha["prof_seq"] == $prof_seq)
 		$stroption.='selected';
 	 
 	$stroption.='>'. $linha["prof_name"]. '</option>';
 
 }
 $stroption.='</select>';
 echo $stroption;
 
 
 
 
 //Formulario de clientes do profissional
 /*
 $stroption=' Cliente: <select  multiple class="frmclient" name="clica_client_seq[]" id="clica_client_seq" >';
 //Busca o cliente no banco
 $sql = "Select * FROM client ORDER BY client_name order by client_name";
 $result = $conn->query($sql);
 
 if ($result->num_rows > 0)
 {
 	// Monta o select do cliente
 	while ($linha = $result->fetch_assoc())
 	{
 		$stroption.='<option value="">';
 		$stroption.='<option value="'. $linha["client_seq"]. '"';
 		// Verifica se existe no array
 		if (in_array($linha['client_seq'], $client_seq))
 			$stroption.='selected';
 	
 		$stroption.='>'. $linha["client_name"]. '</option>';
	 }
 $stroption.='</select>';
 echo $stroption;
 }
 */ 
 echo '<INPUT type="submit" value="Pesquisar" id="search_clica" name="search_clica"  class="frmsubmit">';
 // Se for alessandra ou teia logado, aparece essa opçao
 if (($_SESSION['login'] == "abrbrandao") || ($_SESSION['login'] == "teiabak"))
 	echo '<INPUT type="submit" value="'. utf8_encode("Vincular Ligações"). ' id="ins_clica" name="ins_clica"  class="frmsubmit" size="25">';
 
 
 
 $stroption="</form></div>";
 echo $stroption;
 
 
 
 
 
 // Verifica se é para pesquisar profissional/clientes
 if  (isset($_REQUEST["search_clica"]))
 {
 	
 		$sql="SELECT clica_next_agenda_seq,clica_last_agenda_seq,prof_name, prof_seq,client_name,client_email, clica_data_ligacao,stali_desc, client_seq, clica_seq  From status_ligacao,profissional,client,client_call ";
 		$sql.=" where client_seq=clica_client_seq ";
 		$sql.= "AND prof_seq=clica_prof_seq AND clica_stali_seq=stali_seq AND client_seq = clica_client_seq AND prof_seq=". $prof_seq;
 		$sql.= " ORDER BY clica_seq DESC";
		//echo $sql;

 		//echo $sql;
 	/*
 		
 		$sql="SELECT  clica_next_agenda_seq,clica_last_agenda_seq,prof_name, prof_seq,client_name,client_email, clica_data_ligacao,stali_desc, client_seq, clica_seq  From status_ligacao, client_call,profissional,client where client_seq=clica_client_seq ";
 		$sql.= "AND prof_seq=clica_prof_seq AND clica_stali_seq=stali_seq AND client_seq = clica_client_seq AND prof_seq=". $prof_seq;
 		$sql.= " ORDER BY clica_data_ligacao DESC";
 		
 	*/
 		
 	
 		$result = $conn->query($sql);
 	
 		echo "<div id='div_report_lig'>";
 		$stroption='<div class="linha_report_title">Agendamento</div> ';
 		$stroption.='<div class="linha_report_title">Cliente</div> ';
 		$stroption.='<div class="linha_report_title">Profissioanal</div> ';
 		$stroption.='<div class="linha_report_title">'. utf8_encode("Status Ligação").'</div> ';
 		$stroption.='<div class="linha_report_title">'. utf8_encode("Data Ligação"). '</div> ';
 		$stroption.='<div class="linha_report_title">'. utf8_encode("Última Visita"). '</div> ';
 		$stroption.='<div class="clear"></div>';
 	   
 		if ($result->num_rows > 0)
 		{
 			while ($linha = $result->fetch_assoc())
 			{  
 				$tel_client="";
 				$sql2 = "Select  tecl_operadora, tecl_desc FROM telefone_clients where tecl_client_seq=".$linha["client_seq"] ;
 				$result2 = $conn->query($sql2);
 				if ($result2)
 				{
 					while ($linha2 = $result2->fetch_assoc())
 						$tel_client.="<br>".$linha2["tecl_desc"].  "  ".  $linha2["tecl_operadora"];

 				}
 				$clica_last_agenda_seq="";
 				$data_last_sessao="";
 					
 				
 				//Obtém o identificador do agendamento do cliente
 				$sql = "SELECT max(agenda_seq) as max_agenda_seq,date_format(agenda_start_time, '%d/%m/%Y %h:%i:%s') as data_last_sessao FROM agenda WHERE agenda_client_seq=". $linha["client_seq"] ;
 				$sql .=	" AND agenda_seq > 0 AND agenda_concluded=3" ;
 				$sql .=" ORDER BY  agenda_seq DESC";
 				//echo $sql;
 				$result_verify2 = $conn->query($sql);
 				while ($linha3 = $result_verify2->fetch_assoc())
 				{
 					$clica_last_agenda_seq=  $linha3["max_agenda_seq"];
 					$data_last_sessao=  $linha3["data_last_sessao"];
 				}
 				
 				 					
 				//Se tiver agendamento, mostra o link
 				if ($linha["clica_next_agenda_seq"])
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '&agenda_seq='.  $linha["clica_next_agenda_seq"].  '">Agendamento Programado</a></div>';
 					
 				else 
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '">Novo Agendam</a></div>';
 				
 				$stroption.='<div class="linha_lig"> <a target=”_blank” href="index.php?menu_seq=1&client_seq='. $linha["client_seq"]. ' " >'. $linha["client_name"]. '</a>'.  $tel_client.'</div>';
 				$stroption.='<div class="linha_lig">'. $linha["prof_name"].  '</div>';
 				$stroption.='<div class="linha_lig"> <a target=”_blank” href="dsp_form_status_lig.php?clica_seq='. $linha["clica_seq"]. ' " >'. $linha["stali_desc"].  '</a></div>';
 				$stroption.='<div class="linha_lig">'. $linha["clica_data_ligacao"].  '</div>';
 				if (date($data_last_sessao))
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"].  '&agenda_seq='.  $clica_last_agenda_seq.  '">'.$data_last_sessao.'</a></div>';
 				else 
 					$stroption.='<div class="linha_lig"></div>';
 				
 				
 			}
 		}
 	
 		echo $stroption;
 		 
 		echo " </div>";
 	}
 	

 	// Verifica se é para pesquisar profissional/clientes
 	if  (isset($_POST["ins_clica"]))
 	{
 		
 		for($x = 0; $x < $arrlength; $x++)
 		{
 			$sql= "INSERT INTO client_call ( clica_prof_seq, clica_client_seq, clica_stali_seq) values ";
 			$sql.="(" . $prof_seq . ",". $client_seq[$x] .",1)";
 			$result = $conn->query($sql);
 			//echo $sql . "<br>";
 		}
 		
 		echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=4&search_clica='yes'&prof_seq=". $prof_seq . " '</script>";
 	}
			
?>
   