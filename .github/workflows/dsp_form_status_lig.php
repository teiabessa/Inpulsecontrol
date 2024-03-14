<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu faço a busca no banco de dados de todos os registros de clientes

/* Invoca o arquivo que faz conexão com o db */
require_once 'dsp_header.php';
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
$clica_stali_seq="";
$clica_comment="";
$clica_date="";
$clica_time="";
$clica_client_seq="";

if (isset($_REQUEST['clica_seq']))
	{
		$clica_seq=$_REQUEST['clica_seq'];
		
		$sql = "SELECT  clica_stali_seq,clica_client_seq, clica_comment,time_format(clica_data_ligacao,'%H:%i') as clica_time,  date_format(clica_data_ligacao,'%Y-%m-%d') as clica_date FROM client_call WHERE clica_seq=".$clica_seq;
	    
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0)
		{
			// Atribui o código HTML para montar uma tabela
			while ($linha = $result->fetch_assoc())
			{
			//Inicia o valor das variáveis da agenda
				$clica_stali_seq=$linha["clica_stali_seq"];
				$clica_date=$linha["clica_date"];
				$clica_time=$linha["clica_time"];
				$clica_comment=$linha["clica_comment"];
				$clica_client_seq=$linha["clica_client_seq"];
				$b_clica_action="Update";
			 }
		}
	}
    
	$sql = "SELECT stali_seq, stali_desc FROM status_ligacao";
	$result = $conn->query($sql);
	
    $stroption='<div id="divfrmstatus">';
	$stroption.='<FORM ACTION="act_ins_clica.php" METHOD="POST" id="formclient" name="formclica">';
	echo $stroption;
    
	$stroption="Status da Ligacao <select class='frmclient' name='clica_stali_seq'  id='clica_stali_seq'  onchange='changestatushist()'>";
	
	while ($linha = $result->fetch_assoc())
		$stroption.="<option value='". $linha["stali_seq"]. "'>". $linha["stali_desc"]. "</option>";
	
	$stroption.= "</select>";
	echo $stroption;
	
	
	$strfield= utf8_encode('Comentário:	').' <input class="frmclient" type="text" name="clica_comment" id="clica_comment" size="30" ';
	$strfield.= 'value="'.$clica_comment.'">';
	echo $strfield;
	
	$strfield= utf8_encode('Data Ligação:').' <input class="frmclient" type="date" name="clica_date" id="clica_date" size="30" ';
	$strfield.= 'value="'.$clica_date.'">';
	echo $strfield;
	
	$strfield= utf8_encode('Hora ligacão:'). '<input class="frmclient"  type="time" name="clica_time" id="clica_time" size="30" ';
	$strfield.= 'value="'.$clica_time.'">';
	echo $strfield;
	
	$strfield='<input type="hidden" id="clica_client_seq" name="clica_client_seq" ';
	$strfield.= 'value="'.$clica_client_seq.'">';
	
	$strfield.='<input type="hidden" id="clica_seq" name="clica_seq" ';
	$strfield.= 'value="'.$clica_seq.'">';
	//exibe o campo submit
	$strfield.= '<input type="submit" name="bsubmit" class="frmsubmit" id="bsubmit" value ="Atualizar">';
	
	//exibe o campo submit
	$strfield.=  "</form> </div>";
			
	echo $strfield;
	
	$sql = "SELECT   date_format(hstat_date,'%Y-%m-%d  %H:%i') as hstat_date2, hstat_comment,stali_desc FROM hist_stat_lig,status_ligacao where stali_seq=hstat_stali_seq AND hstat_clica_seq=" . $clica_seq ;
	$result = $conn->query($sql);
	
	$strfield=  "<div id=historico_status>";
	echo $strfield;
	$stroption='<div class="linha_report_title">Status</div> ';
	$stroption.='<div class="linha_report_title">'.utf8_encode("Comentário"). '</div> ';
	$stroption.='<div class="linha_report_title">Data </div> ';
	$stroption.='<div class="clear"></div>';

		while ($linha = $result->fetch_assoc())
	{
		$stroption.='<div class="linha_report">'. $linha["stali_desc"].  '</div>';
		$stroption.='<div class="linha_report">'. $linha["hstat_comment"].  '</div>';
		$stroption.='<div class="linha_report">'. $linha["hstat_date2"].  '</div>';
			
	}
	echo $stroption. "</div>";
	mysqli_close($conn); 
?>

