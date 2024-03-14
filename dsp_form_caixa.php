<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu sou a cliente que faz caixa

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
/* Invoca o arquivo que faz conexão com o db */
require_once 'dsp_header.php';


$caixa_secl_seq="";
$caixa_agenda_seq="";
$caixa_lanc_seq="";

if (isset($_REQUEST["caixa_tipo"]))
	$caixa_tipo=$_REQUEST['caixa_tipo'];
$stroption='<a href="dsp_form_caixa.php?caixa_tipo=1">Voltar </a>  ';

//verifica se foi definido o caixa
if (isset($_REQUEST["caixa_seq"]))
{	
	$caixa_seq=$_REQUEST['caixa_seq'];
	$sql = "Select date_format(caixa_data,'%Y-%m-%d') as caixa_data2, caixa_lanc_seq, caixa_secl_seq, caixa_agenda_seq, time_format(caixa_data,'%H:%i') as caixa_time,caixa_tipo,caixa_client_seq,caixa_valor,caixa_comment, caixa_desc, caixa_data, caixa_forma_pagto, caixa_parcelas FROM lancamento_caixa where caixa_seq=" . $caixa_seq;
	//echo $sql;

	$result = $conn->query($sql);
	while ($linha = $result->fetch_assoc())
	{	
		$caixa_client_seq=$linha["caixa_client_seq"];
		$caixa_valor=$linha["caixa_valor"];
		$caixa_forma_pagto=$linha["caixa_forma_pagto"];
		$caixa_parcelas=$linha["caixa_parcelas"];
		$caixa_data=$linha["caixa_data2"];
		$caixa_time=$linha["caixa_time"];
		$caixa_tipo=$linha["caixa_tipo"];
		$caixa_comment=$linha["caixa_comment"];
		$caixa_desc=$linha["caixa_desc"];
		$caixa_secl_seq=$linha["caixa_secl_seq"];
		$caixa_agenda_seq=$linha["caixa_agenda_seq"];
		$caixa_lanc_seq=$linha["caixa_lanc_seq"];
			
	}
	
	$stroption.='<p><b>Alterar '. utf8_encode('Lançamento</b>'). '</p>';
	$button="Atualiza";
	

}
else
{
	$caixa_seq=0;
	$caixa_client_seq=1;
	$caixa_valor=0;
	$caixa_forma_pagto=1;
	$caixa_parcelas="";
	$caixa_data=date('Y-m-d');
	$caixa_tipo=0;
	$caixa_comment="";
	$caixa_desc="";
	$caixa_time=date('H:i');
	
	
	$stroption.='<p> <b>Novo '. utf8_encode('Lançamento</b>'). '</p>';
	$button="Cadastrar";
}


$stroption.='<div id="frmcaixa"> <FORM ACTION="act_caixa_ins.php" METHOD="POST" id="formcaixa" name="formcaixa">';
echo $stroption;

//if (($_SESSION['login'] == "abrbrandao") || ($_SESSION['login'] == "teiabak"))
//{

 $stroption=' Cliente: <select class="frmclient" name="caixa_client_seq" id="caixa_client_seq">';
 //Busca o cliente no banco
 $sql = "Select client_seq, client_name FROM client  WHERE client_seq > 0";
 $sql.= " ORDER BY client_name"; 
 //and client_activate=1
 $result = $conn->query($sql);
//echo $sql;

 if ($result->num_rows)
 {
 		$stroption.='<option value="">';
 	// Monta o select do cliente
	 while ($linha = $result->fetch_assoc())
 	{
 		 				
 		$stroption.='<option value="'. $linha["client_seq"]. '"';
 		if ($linha["client_seq"] == $caixa_client_seq)
 			$stroption.='selected';
 		$stroption.='>'. $linha["client_name"].  '</option>';
 
 	}
 	$stroption.='</select>';
 	
 }
 
 echo $stroption;
//} 

 
 
 $strfield= utf8_encode('Data Lançamento:'). '<input class="frmclient" type="date" name="caixa_data" id="caixa_data" size="30" required ';
 $strfield.= 'value="'.$caixa_data.'">';
 echo $strfield;
 
 
 
 // Campo select com opção do tipo do pagamento
 $stroption='Tipo de Pagamento:';
 $stroption.= '<select class="frmclient" name="caixa_forma_pagto" id="caixa_forma_pagto" >';
  
 $stroption.='<option value= "1"';
 if ($caixa_forma_pagto == "1")
 	$stroption.='selected';
 $stroption.='>Dinheiro</option>';
 
 $stroption.='<option value= "2"';
 if ($caixa_forma_pagto == "2")
 	$stroption.='selected';
 $stroption.='>'.utf8_encode('Crédito').'</option>';
 	
 $stroption.='<option value= "3"';
 if ($caixa_forma_pagto == "3")
 	$stroption.='selected';
 $stroption.='>'.utf8_encode('Débito').'</option>';
 
 $stroption.='<option value= "4"';
 if ($caixa_forma_pagto == "4")
 	$stroption.='selected';
 $stroption.='>Cheque</option>';
 
 $stroption.= '</select>';
 echo $stroption;
 	
 $strfield= 'Valor: <input class="frmclient" type="text" name="caixa_valor" id="caixa_valor" size="10"';
 $strfield.= 'value="'.$caixa_valor.'">';
 echo $strfield;
 
 $strfield= utf8_encode('Horário') . '<input class="frmclient" required min="07:00" max="21:00" type="time" name="caixa_time" id="caixa_time" size="30"';
 $strfield.= 'value="'.$caixa_time.'">';
 echo $strfield;
 	
 
 $strfield= 'Parcelas: <input class="frmclient" type="text" name="caixa_parcelas" id="caixa_parcelas" size="10"';
 $strfield.= 'value="'.$caixa_parcelas.'">';
 echo $strfield;
 
 
 if ($caixa_secl_seq <> "")
 {
 	$sql= "SELECT * FROM pacote_servico_cliente Where secl_seq=". $caixa_secl_seq;
 	$result = $conn->query($sql);
 		
 		
 	if ($result->num_rows > 0)
 	{
 		echo 'Pacote:<select name="secl_seq" class="frmclient" id="secl_seq">';
 		// Atribui o código HTML para montar uma tabela
 		while ($linha = $result->fetch_assoc())
 		{
 			if ($agenda_secl_seq == $linha['secl_seq'])
 				echo '<option value="'. $linha["secl_seq"]. '"selected >'. $linha["secl_desc"]. '</option>';
 			else
 				echo '<option value="'. $linha["secl_seq"]. '">'. $linha["secl_desc"]. '</option>';
 		}
 		//fecha o select
 		echo "</select>";
 	}
 
 }
 
 // Vetor de tratamentos avulso
 $aget_tyse_seq = array();
 
 	
 	//Seleciona os tipos de servicos dessa agenda
 	
 	$sql = "SELECT  agenda_seq, agenda_desc,time_format(agenda_end_time,'%H:%i') as time_end, time_format(agenda_start_time,'%H:%i') as time_start, date_format(agenda_start_time,'%Y-%m-%d') as agenda_date, agenda_client_seq, agenda_sala, agenda_secl_seq, client_name FROM agenda, client WHERE agenda_client_seq=client_seq AND agenda_concluded in (0,4)";
 	
 	$result = $conn->query($sql);
 	//echo $sql;
 	$vreturn='Agendamento: '.'<select  name="agenda_seq" id="agenda_seq"  class="frmclient" >';
 	echo $vreturn;
 
 	// Atribui o código HTML para montar uma tabela
 		while ($linha = $result->fetch_assoc())
 		{
 			if ($caixa_agenda_seq == $linha['agenda_seq'])
 				echo '<option value="'. $linha["agenda_seq"]. '"selected >'. $linha["agenda_desc"] . "/". $linha["agenda_date"] . " " . $linha["time_start"] . "/" . substr( $linha["client_name"], 0,17) . '</option>';
 			else
 				//echo '<option value="'. $linha["agenda_seq"]. '">'. $linha["agenda_desc"]. '</option>';
 				echo '<option value="'. $linha["agenda_seq"]. '">'. $linha["agenda_desc"] . "/". $linha["agenda_date"] . " ".  $linha["time_start"] . "/" .substr( $linha["client_name"], 0,17) . '</option>';
 		}
 		echo "</select>";
 	
 
     // Opção de lancamento 
 	$sql= "SELECT * FROM lancamentos where lanc_type=". $_REQUEST['caixa_tipo']. " order by lanc_desc";
 	$result = $conn->query($sql); 		
 		
 	if ($result->num_rows > 0)
 	{
 		echo utf8_encode('Lançamentos:'). '<select name="lanc_seq" class="frmclient" id="lanc_seq">';
 		// Atribui o código HTML para montar uma tabela
 		while ($linha = $result->fetch_assoc())
 		{
 			if ($caixa_lanc_seq == $linha['lanc_seq'])
 				echo '<option value="'. $linha["lanc_seq"]. '"selected >'. $linha["lanc_desc"]. '</option>';
 			else
 				echo '<option value="'. $linha["lanc_seq"]. '">'. $linha["lanc_desc"]. '</option>';
 		}
 		echo "</select>";
 		
 	}
 		

 	$strfield= '<input type="hidden" name="caixa_tipo" id="caixa_tipo"';
 	$strfield.= 'value="'.$_REQUEST['caixa_tipo'].'">';
 	echo $strfield;
 	
 
 
 $strfield= utf8_encode('Descrição:').'<input class="frmclient" type="text" name="caixa_desc" id="caixa_desc" size="30"';
 $strfield.= 'value="'.$caixa_desc.'">';
 echo $strfield;
 
 $strfield= utf8_encode('Comentário:'). ' <input class="frmclient" type="text" name="caixa_comment" id="caixa_commment" size="30"';
 $strfield.= 'value="'.$caixa_comment.'">';
 echo $strfield;
 
 

 $strfield= '<INPUT type="submit" value="'. $button . '" id="ins_caixa" name="ins_caixa"  class="frmsubmit">';
 echo $strfield;
 
 // Se for alessandra ou teia logado, aparece essa opçao
 
 
 $stroption="</form></div>";
 echo $stroption;
 
 	
//echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=4&search_clica='yes'&prof_seq=". $prof_seq . " '</script>";
 	
			
?>
   