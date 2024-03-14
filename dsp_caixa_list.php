<?php
ini_set ( 'display_errors', 1 );
error_reporting ( E_ALL );

// Eu faço a busca no banco de dados de todos os registros de clientes

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';
// //isso não deixa que seja feita uma busca null e que a busca não seja tão ampla*("")
// verifica se foi definido o profissional
if (isset ( $_REQUEST ["prof_seq"] ))
	$prof_seq = $_REQUEST ['prof_seq'];
else
	$prof_seq = "";
	
	// verifica se foi definido o client
if (isset ( $_REQUEST ["client_seq"] ))
	$client_seq = $_REQUEST ['client_seq'];
else
	$client_seq = "";
	
	// verifica se foi definido o typeservice
if (isset ( $_REQUEST ["tyse_seq"] ))
	$tyse_seq = $_REQUEST ['tyse_seq'];
else
	$tyse_seq = "";
	
	// Eu apresento o formulário de data da tela de agendamento
	// obtém o valor passado da data a ser pesquisada
if (isset ( $_REQUEST ['caixa_start_time'] )) {
	$caixa_start_time = $_REQUEST ['caixa_start_time']; // data inicial
	$caixa_end_time = $_REQUEST ['caixa_end_time']; // data final
} else {
	$caixa_start_time = date ( 'Y-m-d' );
	$caixa_end_time = date ( 'Y-m-d' );
	// $agenda_start_time="";
	// $agenda_end_time="";
}
if (isset ( $_REQUEST ['action_report'] ))
	$action_report = $_REQUEST ['action_report'];
else
	$action_report = "1";

$field = "<div id='toolbar_nova'>";
$field .= "<a href='dsp_form_caixa.php?caixa_tipo=0'>Entrada Caixa </a>";
$field .= "</div>";

$field .= "<div id='toolbar_nova'>";
$field .= "<a href='dsp_form_caixa.php?caixa_tipo=1'>" . utf8_encode ( 'Saída Caixa' ) . " </a>";
$field .= "</div>";
// $field.="<a href='dsp_form_caixa.php?caixa_tipo=1'>Saída Caixa </a>";
echo $field;
//
$field = '<div class="clear"></div><div id="centralizareport"><div id="divsearchreport"> <form ACTION="index.php?menu_seq=5" METHOD="POST" id="formdate2" name="formdate2">';
// Campo da data inicial
$field .= 'Data Pesquisa:<input  type="date" value="' . $caixa_start_time . '" class="frmclient" name="caixa_start_time" id="caixa_start_time" >';
echo $field;
// Campo da data final
$field = 'Data Final :<input  type="date" value="' . $caixa_end_time . '" class="frmclient" name="caixa_end_time" id="caixa_end_time">';
echo $field;

// Campo das profissionais
$stroption = 'Profissional:';
$sql = "Select * FROM profissional ORDER BY prof_name";
$result = $conn->query ( $sql );
$stroption .= ' <select class="frmclient" name="prof_seq" id="prof_seq" >';
$stroption .= '<option value="">';
while ( $linha = $result->fetch_assoc () ) {
	$stroption .= '<option value="' . $linha ["prof_seq"] . '"';
	if ($linha ["prof_seq"] == $prof_seq)
		$stroption .= 'selected';
	$stroption .= '>' . $linha ["prof_name"] . '</option>';
}
$stroption .= '</select>';
echo $stroption;

// Campo Clientes
$stroption = 'Cliente:';
$sql = "Select * FROM client where client_seq > 0 ORDER BY client_name";
$result = $conn->query ( $sql );
$stroption .= '<select class="frmclient" name="client_seq" id="client_seq" >';
$stroption .= '<option value="">';
while ( $linha = $result->fetch_assoc () ) {
	$stroption .= '<option value="' . $linha ["client_seq"] . '"';
	if ($linha ["client_seq"] == $client_seq)
		$stroption .= 'selected';
	$stroption .= '>' . $linha ["client_name"] . '</option>';
}
$stroption .= '</select>';
echo $stroption;

// Campo tipo do tratamento
$stroption = 'Tipo de tratamento:';
$sql = "Select * FROM inpulsebrasil.type_service where tyse_seq > 0 ORDER BY tyse_desc";
$result = $conn->query ( $sql );
$stroption .= '<select class="frmclient" name="tyse_seq" id="tyse_seq" >';
$stroption .= '<option value="">';
while ( $linha = $result->fetch_assoc () ) {
	$stroption .= '<option value="' . $linha ["tyse_seq"] . '"';
	if ($linha ["tyse_seq"] == $tyse_seq)
		$stroption .= 'selected';
	$stroption .= '>' . $linha ["tyse_desc"] . '</option>';
}
$stroption .= '</select>';
echo $stroption;

// Campo select com opção de agendamento
$stroption = 'Tipo de Pesquisa <select class="frmclient" name="action_report" id="action_report" >';

$stroption .= '<option value= "1" ';
if ($action_report == "1")
	$stroption .= 'selected';
$stroption .= '>'.utf8_encode("Informações Detalhadas").'</option>';

// só aparece a opç~cao de ver o valor do caixa pra esses uusuarios
if (($_SESSION['login'] == "abrbrandao") || ($_SESSION['login'] == "teiabak") || ($_SESSION['login'] == "susanna"))
{

	$stroption .= '<option value= "2"';
	if ($action_report == "2")
		$stroption .= 'selected';
	$stroption .= '>Valor Total Caixa</option>';
}
$stroption .= '<option value= "3"';
if ($action_report == "3")
	$stroption .= 'selected';
$stroption .= '>Quantidade do Pacote</option>';

$stroption .= '</select>';
echo $stroption;

$field = '<input type="submit" class="frmsubmit"  value="Consultar" name="busca_submit">  </form></div></div><div class="clear"></div>';
echo $field;

if($action_report==1){
	$sql = "Select prof_name, client_name, client_indicacao, client_profissao, caixa_seq, caixa_tipo, caixa_lanc_seq, caixa_secl_seq, caixa_agenda_seq, caixa_client_seq, caixa_valor, caixa_comment, caixa_desc, caixa_data, date_format(caixa_data,'%d/%m/%y') as caixa_data2,caixa_forma_pagto, caixa_parcelas FROM inpulsebrasil.lancamento_caixa, client, profissional WHERE prof_seq=caixa_prof_seq AND client_seq=caixa_client_seq AND";
	
	if ($caixa_start_time != "")
		$sql .= " caixa_data >='" . $caixa_start_time . "' AND";
	
	if ($caixa_end_time != "")
		$sql .= " caixa_data <='" . $caixa_end_time . "' AND";
	
	if ($prof_seq != "")
		$sql .= " prof_seq=" . $prof_seq . " AND";
	
	if ($client_seq != "")
		$sql .= " client_seq=" . $client_seq . " AND";
		/*
	 * if ($tyse_seq !="")
	 * $sql.=" tyse_desc like %". $tyse_desc . " AND";
	 */
	
	$sql .= " caixa_seq > 0 ORDER BY caixa_data, caixa_desc ASC ";
	
	//echo $sql;
	echo "Forma Pagto - 0=dinheiro/1=cheque/2=credito/3=debito";
	$result = $conn->query ( $sql );
	if ($result) {
		echo "<div id='div_caixa'>";
		
		$stroption = '<div class="linha_caixa_title">Item do Caixa</div> ';
		$stroption .= '<div class="linha_caixa_title">' . utf8_encode ( "Data Lançamento" ) . '</div> ';
		$stroption .= '<div class="linha_caixa_title">' . utf8_encode ( "Valor Lançamento" ) . '</div> ';
		$stroption .= '<div class="linha_caixa_title">Cliente</div> ';
		$stroption .= '<div class="linha_caixa_title">' . utf8_encode ( "Prof. Vendeu" ) . '</div> ';
		$stroption .= '<div class="linha_caixa_title">' . utf8_encode ( "Indicação" ) . '</div> ';
		$stroption .= '<div class="linha_caixa_title">' . utf8_encode ( "Profissão" ) . '</div> ';
		$stroption .= '<div class="linha_caixa_title">' . utf8_encode ( "Forma Pagto" ) . '</div> ';
		/*
		0=dinheiro
		1=cheque
		2=cartãocréito
		3=cartão débito
		*/
		
		
		$stroption .= '<div class="clear"></div>';
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_caixa"><a target=”_blank”  href="dsp_form_caixa.php?caixa_seq=' . $linha ["caixa_seq"] . '&caixa_tipo=' . $linha ["caixa_tipo"] . '">' . $linha ["caixa_desc"] . '</a></div>';
			
			$stroption .= '<div class="linha_caixa">' . $linha ["caixa_data2"]; 
			if($linha ["caixa_agenda_seq"] != "")
				$stroption .='<br><a target=”_blank”  href="dsp_form_agenda.php?agenda_seq=' . $linha ["caixa_agenda_seq"] . '">Agendamento</a>';
			$stroption .='</div>';
			$stroption .= '<div class="linha_caixa">' . $linha ["caixa_valor"] . '</div>';
			$stroption .= '<div class="linha_caixa">' . $linha ["client_name"] . '</div>';
			$stroption .= '<div class="linha_caixa">' . $linha ["prof_name"] . '</div>';
			$stroption .= '<div class="linha_caixa">' . $linha ["client_indicacao"] . '</div>';
			$stroption .= '<div class="linha_caixa">' . $linha ["client_profissao"] . '</div>';
			$stroption .= '<div class="linha_caixa">' . $linha ["caixa_forma_pagto"] . '</div>';
			
			
			$stroption .= '<div class="clear"></div>';
		}
		echo $stroption;
		
		echo " </div>";
		
		
	} else {
		echo utf8_encode ( "Não foi encontrado nada para esta pesquisa" );
	}
}
// faz a somatoria do caixa
if ($action_report==2){
	$sql = "SELECT caixa_seq, caixa_data, SUM(caixa_valor) total ";
	$sql .= "FROM inpulsebrasil.lancamento_caixa WHERE ";
	if ($caixa_start_time != "")
		$sql .= " caixa_data >='" . $caixa_start_time . "' AND";
	
	if ($caixa_end_time != "")
		$sql .= " caixa_data <='" . $caixa_end_time . "' AND";
	$sql .= " caixa_seq > 0 ORDER BY caixa_desc ";
	$result = $conn->query ( $sql );
	echo "<div id='div_report_sum'>";
	$stroption = "";
	$stroption .= '<div class="linha_report_title">Caixa</div> ';
	$stroption .= '<div class="linha_report_title">Valor</div> ';
	$stroption .= '<div class="clear"></div>';
	//echo $sql;
	if ($result) {
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_report">Total</div>';
			$stroption .= '<div class="linha_report">' . number_format ( $linha ["total"], 2, ",", "." ) . '</div>';
			$stroption .= '<div class="clear"></div>';

		}
	}
	echo $stroption;
	$stroption = "";
}
if ($action_report==3){
	$sql = "SELECT caixa_desc, COUNT(caixa_desc) as total ";
	$sql .= "FROM inpulsebrasil.lancamento_caixa WHERE";
	if ($caixa_start_time != "")
		$sql .= " caixa_data >='" . $caixa_start_time . "' AND";
	
	if ($caixa_end_time != "")
		$sql .= " caixa_data <='" . $caixa_end_time . "' AND";
	
	if ($prof_seq != "")
		$sql .= " prof_seq=" . $prof_seq . " AND";
	
	if ($client_seq != "")
		$sql .= " client_seq=" . $client_seq . " AND";
	$sql .= " caixa_seq > 0 GROUP BY caixa_desc ";
	$sql .= "ORDER BY caixa_desc ";
	$result = $conn->query ( $sql );
	echo "<div id='div_report_sum'>";
	$stroption = "";
	$stroption .= '<div class="linha_report_title">Pacote</div> ';
	$stroption .= '<div class="linha_report_title">Quantidade</div> ';
	$stroption .= '<div class="clear"></div>';
	//echo $sql;
	if ($result) {
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_report">' . $linha["caixa_desc"] . '</div>';
			$stroption .= '<div class="linha_report">' . $linha ["total"] . '</div>';
			$stroption .= '<div class="clear"></div>';

		}
	}
	echo $stroption;
	$stroption = "";
}
?>
   
   
   
   