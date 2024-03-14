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

//sql3="select date_sub('". $agenda_start_time. "', INTERVAL 30 DAYS) as dia_menos";
//echo " <br>Sql date add antes de exec.: <br> " . $sql;
//$result3 = $conn->query($sql3);
//while($row = $result3->fetch_assoc())
	//$agenda_start_time=$row["dia_menos"];


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
 $sql = "Select * FROM profissional where prof_seq > 0 ORDER BY prof_name";
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
 /*
 0=Clientes inativos
 1=Clientes ultimos 3 mes
 2=cancelamento
 3=Acompanhamento
 */
 
 // Verifica se é para pesquisar profissional/clientes
 if  (isset($_REQUEST["search_clica"]))
 {
 	
 		$sql="SELECT clica_motivo,clica_activate,clica_stali_seq,clica_next_agenda_seq,clica_last_agenda_seq, DATE_FORMAT(agenda_start_time,'%m-%d-%Y') as data_agenda, DATE_FORMAT(clica_data_ligacao,'%d-%m-%Y %H:%i') as data_lig,clica_comment,prof_name, prof_seq,client_name,client_email,stali_desc, client_seq, clica_seq  From status_ligacao,profissional,client,client_call";
 		$sql.=" LEFT OUTER JOIN agenda on (agenda_seq=clica_last_agenda_seq)";
 		$sql.=" WHERE client_seq > 0 AND client_seq=clica_client_seq ";
 		$sql.= "AND prof_seq=clica_prof_seq AND clica_stali_seq=stali_seq AND client_seq = clica_client_seq AND prof_seq=". $prof_seq;
 		//$sql.= " AND client_seq not in (SELECT distinct secl_client_seq FROM pacote_servico_cliente WHERE secl_data_agendamento >= DATE_SUB(CURDATE(),INTERVAL 60 DAY))";
 				
 		$sql.= " ORDER BY stali_desc,clica_data_ligacao,clica_motivo DESC";

		 		//echo $sql;
 	/*
 		
 		$sql="SELECT  clica_next_agenda_seq,clica_last_agenda_seq,prof_name, prof_seq,client_name,client_email, clica_data_ligacao,stali_desc, client_seq, clica_seq  From status_ligacao, client_call,profissional,client where client_seq=clica_client_seq ";
 		$sql.= "AND prof_seq=clica_prof_seq AND clica_stali_seq=stali_seq AND client_seq = clica_client_seq AND prof_seq=". $prof_seq;
 		$sql.= " ORDER BY clica_data_ligacao DESC";
 		
 	*/
 		
 	
 		$result = $conn->query($sql);
 	
 		echo "<div id='div_report_lig'>";
 		$stroption='<div class="linha_report_lig">Agendam.</div> ';
 		$stroption.='<div class="linha_report_lig">Cliente</div> ';
 		$stroption.='<div class="linha_report_lig">'. utf8_encode("Status Lig.").'</div> ';
 		$stroption.='<div class="linha_report_lig">'. utf8_encode("Última Visita"). '</div> ';
 		$stroption.='<div class="linha_report_lig">'. utf8_encode("Data Lig."). '</div> ';
 		$stroption.='<div class="linha_report_lig">'. utf8_encode("Situação"). '</div> ';
 		$stroption.='<div class="clear"></div>';
 	   
 		if ($result->num_rows > 0)
 		{
 			while ($linha = $result->fetch_assoc())
 						
 			{  
 					
 				if($linha["clica_motivo"]==0)
 					$clica_motivo="Inativo";
 				
 				if($linha["clica_motivo"]==1)
 					$clica_motivo="3 Meses";
 				
 				if($linha["clica_motivo"]==2)
 					$clica_motivo="Cancelou";
 				
 				if($linha["clica_motivo"]==3)
 					$clica_motivo="Acompanhamento";
 					
 				$tel_client="";
 				$sql2 = "Select  tecl_operadora, tecl_desc FROM telefone_clients where tecl_client_seq=".$linha["client_seq"] ;
 				$result2 = $conn->query($sql2);
 				if ($result2)
 				{
 					while ($linha2 = $result2->fetch_assoc())
 						$tel_client.="<br>".$linha2["tecl_desc"].  "  ".  $linha2["tecl_operadora"];

 				}
 				// Resolvido
 				if($linha["clica_activate"]==1){
 					$class="linha_lig1";
 				}
 				// Deixa o registro na cor amarela se for contactado via zap ou telefone
 				elseif($linha["clica_stali_seq"]==7 || $linha["clica_stali_seq"]==8){
 					$class="linha_lig2";
 				}
 				//Ligações Pendentes
 				else{
 					$class="linha_lig0";
 				}	
 				//Se tiver agendamento, mostra o link
 				if ($linha["clica_next_agenda_seq"])
 					$stroption.='<div class="'.$class.'"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '&agenda_seq='.  $linha["clica_next_agenda_seq"].  '">Agendamento Programado</a></div>';

 				else 
 					$stroption.='<div class="'.$class.'"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"]. '&clica_seq='.  $linha["clica_seq"]. '">Novo</a></div>';
 				
 					$stroption.='<div class="'.$class.'">'. $linha["client_name"]. $tel_client. '</div>';
 					$stroption.='<div class="'.$class.'"><a target=”_blank” href="dsp_form_status_lig.php?clica_seq='. $linha["clica_seq"]. ' " >'. $linha["stali_desc"].  '</a></div>';
 				
 				if($linha["clica_last_agenda_seq"])
 					$stroption.='<div class="'.$class.'"><a target=”_blank”  href="dsp_form_agenda.php?client_seq='. $linha["client_seq"]. '&prof_seq='.  $linha["prof_seq"].  '&agenda_seq='.  $linha["clica_last_agenda_seq"] .  '">'. $linha["data_agenda"]. "<br>" . $linha["clica_comment"].'</a></div>';
 				else
 					$stroption.='<div class="'.$class.'">'. $linha["clica_comment"]. '</div>';
 					
 					$stroption.='<div class="'.$class.'">'. $linha["data_lig"].  '</div>';
 					
 					$stroption.='<div class="'.$class.'">'. $clica_motivo . '</div>';
 					$stroption.='<div class="clear"></div>';
 				
 				
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
 			
 			$sql="SELECT max(agenda_seq) as max_agenda_seq From agenda ";
 			$sql.=" WHERE agenda_client_seq > 0 AND agenda_concluded=3 AND agenda_client_seq=". $client_seq[$x];
 			$result2 = $conn->query($sql);
 			if ($result2)
 			{
 				while ($linha2 = $result2->fetch_assoc())
 					$agenda_seq=$linha2["max_agenda_seq"];
 			
 			}
 			
 			$sql= "INSERT INTO client_call ( clica_last_agenda_seq,clica_prof_seq, clica_client_seq, clica_stali_seq, clica_motivo,  clica_comment) values (";
 			// Verifica se possui agendamento anterior
 			if ($agenda_seq != 0 )
 				$sql.=$agenda_seq ;
 			else 
 				$sql.='NULL' ;
 			
 			$sql.= ","  .$prof_seq . ",". $client_seq[$x] .",1 , 0,'Resgatar Cliente' )";
 			$result = $conn->query($sql);
 			echo $sql . "<br>";
 		}
 		echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=4&search_clica='yes'&prof_seq=". $prof_seq . " '</script>";
 	}
			
?>
   