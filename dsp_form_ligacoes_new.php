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

// Pega cliente que tem pacote inferiro a 60 dias diminui 30 dias
$agenda_start_time=date('Y-m-d');


 $stroption=' Cliente: <select  multiple  name="client_seq[]" id="client_seq"  size="20">';
 //Busca o cliente no banco
 $sql = "Select client_seq, client_name FROM client  WHERE client_seq > 0 ";
 if (trim($clica_client_seq))
 	$sql .= " and client_seq not in(". $clica_client_seq.   ") ";
 
 $sql.= " AND client_seq not in (SELECT distinct secl_client_seq FROM pacote_servico_cliente WHERE secl_data_agendamento >= DATE_SUB(CURDATE(),INTERVAL 60 DAY))";
 $sql.= " ORDER BY client_name";

 
 $result = $conn->query($sql);
//echo $sql;

 if ($result->num_rows)
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
 
 
 
 
 echo '<INPUT type="submit" value="Pesquisar" id="search_clica" name="search_clica"  class="frmsubmit">';
 // Se for alessandra ou teia logado, aparece essa opçao
 if (($_SESSION['login'] == "abrbrandao") || ($_SESSION['login'] == "teiabak"))
 	echo '<INPUT type="submit" value="'. utf8_encode("Vincular Ligações"). ' id="ins_clica" name="ins_clica"  class="frmsubmit" size="25">';
 
 
 
 $stroption="</form></div>";
 echo $stroption;
 
 echo "<div id='div_report_lig_title'>";
 $stroption='<div class="linha_report_lig">Agendamento</div> ';
 $stroption.='<div class="linha_report_lig">Cliente</div> ';
 $stroption.='<div class="linha_report_lig">'. utf8_encode("Status Lig.").'</div> ';
 $stroption.='<div class="linha_report_lig">'. utf8_encode("Data Contato"). '</div> ';
 $stroption.='<div class="linha_report_lig">'. utf8_encode("Última Visita"). '</div> ';
 $stroption.='<div class="clear"></div></div>';
  
 echo $stroption;
 
 $sql3 = "Select prof_seq, prof_name FROM profissional WHERE prof_seq > 0 AND prof_seq <> 1 ORDER BY prof_name";
 $result3 = $conn->query($sql3);
 //echo $sql3;
 if ($result3->num_rows > 0)
 {
 	while ($linha3 = $result3->fetch_assoc())
 	{
 		echo "<div id='div_report_lig'>";
 		$stroption='<div class="linha_prof_lig">'. $linha3["prof_name"] .'</div> ';
 		echo $stroption;
 		
 		$sql="SELECT clica_next_agenda_seq,clica_last_agenda_seq,clica_comment,prof_name, prof_seq,client_name,client_email, clica_data_ligacao,stali_desc, client_seq, clica_seq  From status_ligacao,profissional,client,client_call ";
 		$sql.=" WHERE client_seq > 0 AND client_seq=clica_client_seq ";
 		$sql.= "AND prof_seq=clica_prof_seq AND clica_stali_seq=stali_seq AND client_seq = clica_client_seq AND prof_seq =". $linha3["prof_seq"];
 		$sql.= " ORDER BY prof_name,clica_seq DESC";

		//echo $sql;
 	
 		$result = $conn->query($sql);
 	
 		//$stroption.='<div class="clear"></div>';
 	   
 		if ($result->num_rows > 0)
 		{
 			while ($linha = $result->fetch_assoc())
 			{  
 				$tel_client="";
 				$sql2 = "Select tecl_operadora, tecl_desc FROM telefone_clients where tecl_client_seq=".$linha["client_seq"] ;
 				$result2 = $conn->query($sql2);
 				if ($result2)
 				{
 					while ($linha2 = $result2->fetch_assoc())
 						$tel_client.="<br>".$linha2["tecl_desc"].  "  ".  $linha2["tecl_operadora"];

 				}
 								 					
 				//Se tiver agendamento, mostra o link
 				if ($linha["clica_next_agenda_seq"])
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '&agenda_seq='.  $linha["clica_next_agenda_seq"].  '">Agendamento Programado</a></div>';
 					
 				else 
 					$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '">Novo Agendam</a></div>';
 				
 				$stroption.='<div class="linha_lig">'. $linha["client_name"]. $tel_client. '</div>';
 				$stroption.='<div class="linha_lig"> <a target=”_blank” href="dsp_form_status_lig.php?clica_seq='. $linha["clica_seq"]. ' " >'. $linha["stali_desc"].  '</a></div>';
 				$stroption.='<div class="linha_lig">'. $linha["clica_data_ligacao"].  '</div>';
 				$stroption.='<div class="linha_lig"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"].  '&agenda_seq='.  $linha["clica_last_agenda_seq"].  '">'.$linha["clica_comment"].'</a></div>';
 				
 				$stroption.='<div class="clear"></div>';
 				
 				
 			}
 		}
 	}}
 	
 		echo $stroption;
 		 
 		echo " </div>";
 	

 	// Verifica se é para pesquisar profissional/clientes
 	if  (isset($_POST["ins_clica"]))
 	{
 		
 		for($x = 0; $x < $arrlength; $x++)
 		{
 			$sql= "INSERT INTO client_call ( clica_prof_seq, clica_client_seq, clica_stali_seq,clica_comment,clica_motivo) values ";
 			$sql.="(" . $prof_seq . ",". $client_seq[$x] ."'Oferecer Promoção',1,1)";
 			$result = $conn->query($sql);
 			//echo $sql . "<br>";
 		}
 		
 		echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=4&search_clica='yes'&prof_seq=". $prof_seq . " '</script>";
 	}
			
?>