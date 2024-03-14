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
$clica_date=date('Y-m-d');
$clica_time=date('H:i');
$clica_client_seq="";
$client_data_nascimento="";

if (isset($_REQUEST['clica_seq']))
	{
		$clica_seq=$_REQUEST['clica_seq'];
		
		$sql = "SELECT client_email, client_data_nascimento,client_rg, client_cpf, clica_stali_seq,clica_client_seq, clica_comment,time_format(clica_data_ligacao,'%H:%i') as clica_time,  date_format(clica_data_ligacao,'%Y-%m-%d') as clica_date FROM client_call, client WHERE clica_client_seq=client_seq AND clica_seq=".$clica_seq;
	    
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0)
		{
			// Atribui o código HTML para montar uma tabela
			while ($linha = $result->fetch_assoc())
			{
			//Inicia o valor das variáveis da agenda
				//$clica_stali_seq=$linha["clica_stali_seq"];
				//$clica_date=$linha["clica_date"];
				//$clica_time=$linha["clica_time"];
				//$clica_comment=$linha["clica_comment"];
				$clica_client_seq=$linha["clica_client_seq"];				
				$client_email=$linha["client_email"];
				$client_rg=$linha["client_rg"];
				$client_cpf=$linha["client_cpf"];
				$client_data_nascimento=$linha["client_data_nascimento"];				
				$b_clica_action="Update";
			 }
		}
	}
    
	$sql = "SELECT stali_seq, stali_desc FROM status_ligacao";
	$result = $conn->query($sql);
	
    $stroption='<div id="divfrmstatus">';
	$stroption.='<FORM ACTION="act_ins_clica.php" METHOD="POST" id="formclient" name="formclica">';
	echo $stroption;
    
	$stroption="Status<select class='frmligacoes' name='clica_stali_seq'  id='clica_stali_seq'>";
	
	while ($linha = $result->fetch_assoc())
		$stroption.="<option value='". $linha["stali_seq"]. "'>". $linha["stali_desc"]. "</option>";
	
	$stroption.= "</select>";
	echo $stroption;
	
	
	$strfield= "<br>" . utf8_encode('Comentário:	').' <input type="text" class="frmligacoes" name="clica_comment" id="clica_comment" size="30" ';
	$strfield.= 'value="'.$clica_comment.'">';
	echo $strfield;
	
	$strfield= "<br>" .utf8_encode('Data Ligação:').' <input  type="date" class="frmligacoes" name="clica_date" id="clica_date" size="30" ';
	$strfield.= 'value="'.$clica_date.'">';
	echo $strfield;
	
	$strfield= "<br>" .utf8_encode('Hora ligacão:'). '<input type="time" class="frmligacoes" name="clica_time" id="clica_time" size="30" ';
	$strfield.= 'value="'.$clica_time.'">';
	echo $strfield;
	
	$strfield= "<br>" .utf8_encode('Email Cliente:'). '<input class="frmligacoes"  type="text" name="client_email" id="client_email" size="30" ';
	$strfield.= 'value="'.$client_email.'">';
	echo $strfield;
	
	$strfield= "<br>" . utf8_encode('Data de Nascimento:'). '<input class="frmligacoes"  type="date" name="client_data_nascimento" id="client_data_nascimento" size="30" ';
	$strfield.= 'value="'.$client_data_nascimento.'">';
	echo $strfield;
	
	$strfield= "<br>" . utf8_encode('CPF:'). '<input class="frmligacoes"  type="text" name="client_cpf" id="client_cpf" size="15" ';
	$strfield.= 'value="'.$client_cpf.'">';
	echo $strfield;

	$strfield= "<br>" .utf8_encode('RG:'). '<input class="frmligacoes"  type="text" name="client_rg" id="client_rg" size="15" ';
	$strfield.= 'value="'.$client_rg.'">';
	echo $strfield;
	
	//Seleciona todas as promoçoes ativas para o cliente
	$sql_promo="SELECT plig_short_name, plig_seq FROM promo_ligacoes WHERE plig_ativa=1";
	$result_promo = $conn->query($sql_promo);
	
	//echo $sql_promo;
	
	$strfield='<input type="hidden" id="clica_client_seq" name="clica_client_seq" ';
	$strfield.= 'value="'.$clica_client_seq.'">';
	
	$strfield.='<input type="hidden" id="clica_seq" name="clica_seq" ';
	$strfield.= 'value="'.$clica_seq.'">';
	//exibe o campo submit
	$strfield.= "<br><br>" . '<input type="submit" name="bsubmit" class="frmsubmit" id="bsubmit" value ="Atualizar">';
	
	
	$plig_string= "<br><br>". utf8_encode('Envio de E-mail Automático de Promoções:'). "<br>";
	// seleciona as promoções vigentes e mostra para o atendente escolher
	if ($result_promo->num_rows > 0)
	{
		while ($linha_promo = $result_promo->fetch_assoc())
	
			$plig_string.='<a href="rotinas/act_promo_vigente.php?clica_seq='. $clica_seq. '&plig_seq='. $linha_promo["plig_seq"]. '&client_seq=' . $clica_client_seq. '">'. $linha_promo["plig_short_name"]. '</a> &nbsp;&nbsp;';
	
	}
	
	$strfield.= $plig_string ;
	
	
	//exibe o campo submit
	$strfield.=  "</form> </div>";
			
	echo $strfield;
	
	$sql = "SELECT   date_format(hstat_date,'%d-%m-%y  %H:%i') as hstat_date2, hstat_comment,stali_desc FROM hist_stat_lig,status_ligacao where stali_seq=hstat_stali_seq AND hstat_clica_seq=" . $clica_seq ;
	$result = $conn->query($sql);
	
	$strfield=  "<div id=historico_status>";
	echo $strfield;
	$stroption='<div class="linha_status">Status</div> ';
	$stroption.='<div class="linha_status">'.utf8_encode("Comentário"). '</div> ';
	$stroption.='<div class="linha_status">Data </div> ';
	$stroption.='<div class="clear"></div>';

		while ($linha = $result->fetch_assoc())
	{
		$stroption.='<div class="linha_report">'. $linha["stali_desc"].  '</div>';
		$stroption.='<div class="linha_report">'. $linha["hstat_comment"].  '</div>';
		$stroption.='<div class="linha_report">'. $linha["hstat_date2"].  '</div>';
		$stroption.='<div class="clear"></div>';
			
	}
	echo $stroption. "</div>";
	mysqli_close($conn); 
?>

