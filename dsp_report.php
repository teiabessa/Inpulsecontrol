<?php
ini_set ( 'display_errors', 1 );
error_reporting ( E_ALL );
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

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
	
	// Eu apresento o formulário de data da tela de agendamento
	
// obtém o valor passado da datta a ser pesquisada
if (isset ( $_REQUEST ['agenda_start_time'] )) 
{
	$agenda_start_time = $_REQUEST ['agenda_start_time'];
	$agenda_end_time = $_REQUEST ['agenda_end_time'];
} 
else 
{
	//$agenda_start_time = date ( 'Y-m-d' );	
	//$agenda_end_time = date ( 'Y-m-d' );
	$agenda_start_time = "";
	$agenda_end_time = "";

}

// obtém o valor passado da datta a ser pesquisada
if (isset ( $_REQUEST ['action_report'] ))
	$action_report = $_REQUEST ['action_report'];
else
	$action_report = "1";
	
	// echo $_REQUEST['action_report'];
	
echo "&nbsp;&nbsp; <a target=”_blank” href='docs/precos_inpulse.pdf'". "'> Tabela de Valores </a>";
echo "&nbsp;&nbsp; <a target=”_blank” href='docs/tratamentos_inpulse.pdf'". "'> Tratamentos In Pulse </a></div>";
echo "&nbsp;&nbsp; <a target=”_blank” href='docs/ficha_foto.pdf'". "'> Ficha de Foto </a></div>";
echo "&nbsp;&nbsp; <a target=”_blank” href='docs/ficha_corporal.pdf'". "'> Corporal </a></div>";
echo "&nbsp;&nbsp; <a target=”_blank” href='docs/ficha_facial.pdf'". "'> Facial </a></div>";
echo "&nbsp;&nbsp; <a target=”_blank” href='docs/ficha_micro.pdf'". "'> Micropigmentacao </a></div>";



$field = '<div class="clear"></div><div id="centralizareport"><div id="divsearchreport"> <form ACTION="index.php?menu_seq=3" METHOD="POST" id="formdate2" name="formdate2">';
$field .= 'Data Pesquisa:<input  type="date" value="' . $agenda_start_time . '" class="frmclient" name="agenda_start_time" id="agenda_start_time">';
echo $field;

$field = 'Data Final :<input  type="date" value="' . $agenda_end_time . '" class="frmclient" name="agenda_end_time" id="agenda_end_time">';
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

// Campo select com opção de agendamento
$stroption = 'Tipo de Relatório <select class="frmclient" name="action_report" id="action_report" >';

$stroption .= '<option value= "1" ';
if ($action_report == "1")
	$stroption .= 'selected';
$stroption .= '>Todos Agendamentos</option>';

$stroption .= '<option value= "2"';
if ($action_report == "2")
	$stroption .= 'selected';
$stroption .= '>Agendamentos Programados </option>';

$stroption .= '<option value= "3"';
if ($action_report == "3")
	$stroption .= 'selected';
$stroption .= '>Agendamentos Concluídos</option>';
// utf8_encode('Agendamentos Concluídos').

$stroption .= '<option value= "4"';
if ($action_report == "4")
	$stroption .= 'selected';
$stroption .= '> Agendamentos Cancelados </option>';

$stroption .= '<option value= "5"';
if ($action_report == "5")
	$stroption .= 'selected';
$stroption .= '>Ciclo Tratamento Vencido </option>';

$stroption .= '<option value= "6"';
if ($action_report == "6")
	$stroption .= 'selected';
$stroption .= '>Somatório por tratamento avulso </option>';

$stroption .= '<option value= "7"';
if ($action_report == "7")
	$stroption .= 'selected';
$stroption .= '>Somatório por pacotes de tratamento </option>';

$stroption .= '<option value= "8"';
if ($action_report == "8")
	$stroption .= 'selected';
$stroption .= '>Somatório Geral </option>';


$stroption .= '<option value= "9"';
if ($action_report == "9")
	$stroption .= 'selected';
$stroption .= '>Clientes Novos </option>';

$stroption .= '</select>';

echo $stroption;

$field = '<input type="submit" class="frmsubmit"  value="Consultar" name="busca_submit">  </form></div></div>';

echo $field;

// Opção de consulta= Todos Agendamentos
if ($action_report == "1" || $action_report == "2" || $action_report == "3" || $action_report == "4") {
	$sql = " SELECT agenda_valor,agenda_seq, prof_name, client_seq,date_format(agenda_start_time, '%d/%m/%Y %H:%i:%s') as data_sessao_ini, time_format(agenda_end_time, '%H:%i:%s') as hora_sessao_end, client_name, agenda_desc FROM profissional, client, inpulsebrasil.agenda";
	$sql .= " WHERE agenda_prof_seq=prof_seq AND client_seq=agenda_client_seq AND ";
	
	// Verifica apenas agendamentos programados
	if ($action_report == "2")
		$sql .= " (agenda_concluded=0 OR agenda_concluded is null)  AND";
		// Verifica apenas agendamentos concluídos
	if ($action_report == "3")
		$sql .= " agenda_concluded=3 AND";
		// Verifica apenas agendamentos cancelados
	if ($action_report == "4")
		$sql .= " agenda_concluded=2 AND ";
	
	if ($agenda_start_time != "")
		$sql .= " agenda_start_time >='" . $agenda_start_time . "' AND";
	
	if ($agenda_end_time != "")
		$sql .= " agenda_end_time <='" . $agenda_end_time . "' AND";
	
	if ($prof_seq != "")
		$sql .= " prof_seq=" . $prof_seq . " AND";
	
	if ($client_seq != "")
		$sql .= " client_seq=" . $client_seq . " AND";
	
	$sql .= " agenda_seq > 0 Order by agenda_start_time,agenda_desc ASC LIMIT 200";
	
	// echo $sql;
	
	$result = $conn->query ( $sql );
	
	echo "<div id='div_report2'>";
	$stroption = '<div class="linha_report_title">Tratamento</div> ';
	$stroption .= '<div class="linha_report_title">Profissional</div> ';
	$stroption .= '<div class="linha_report_title">Data Atendimento</div> ';
	$stroption .= '<div class="linha_report_title">Cliente</div> ';
	$stroption .= '<div class="linha_report_title">Valor</div> ';
	
	$stroption .= '<div class="clear"></div>';
	
	if ($result) {
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_report"><a target=”_blank”  href="dsp_form_agenda.php?agenda_seq=' . $linha ["agenda_seq"] . '">' . $linha ["agenda_desc"] . '</a></div>';
			$stroption .= '<div class="linha_report">' . $linha ["prof_name"] . '</div>';
			$stroption .= '<div class="linha_report">' . $linha ["data_sessao_ini"] . ' ' . $linha ["hora_sessao_end"] . '</div>';
			$stroption .= '<div class="linha_report"> <a target=”_blank” href="index.php?menu_seq=1&client_seq=' . $linha ["client_seq"] . ' " >' . $linha ["client_name"] . '</a></div>';
			$stroption .= '<div class="linha_report">' . $linha ["agenda_valor"] . '</div>';
		}
	}
	
	echo $stroption;
	echo " </div>";
}

// Verifica se é venceram o ciclo

if ($action_report == "5") {
	// anterior
	$sql = "SELECT  distinct client_seq,client_name,client_email, agenda_start_time,tyse_desc From agenda, client, type_service,agenda_service where client_seq=agenda_client_seq ";
	$sql .= "AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq AND datediff('" . $agenda_start_time . "',agenda_start_time) > tyse_duracao";
	
	$sql = "SELECT distinct client_seq,client_name,client_email, agenda_start_time, if(tyse_parent_seq=7, 'fotodepilação', tyse_desc) as tratamento From agenda, client, type_service,agenda_service where agenda_seq > 0 AND client_seq=agenda_client_seq AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq AND agenda_concluded=3 AND client_seq <> 1298 AND datediff('2016-01-01',agenda_start_time) > tyse_duracao  and client_seq not in(";
	$sql .= "SELECT distinct client_seq From agenda, client, type_service,agenda_service where agenda_seq > 0 AND client_seq=agenda_client_seq AND tyse_seq=aget_tyse_seq AND agenda_seq=aget_agenda_seq AND agenda_concluded=3 AND client_seq <> 1298 AND datediff('2016-01-01',agenda_start_time) < tyse_duracao order by client_name,agenda_start_time desc)";
	$sql .= "order by tratamento,client_name,agenda_start_time asc LIMIT 200";
	
	// echo $sql;
	
	$result = $conn->query ( $sql );
	
	echo "<div id='div_report'>";
	
	$stroption = '<div class="linha_report_title">Nome</div> ';
	$stroption .= '<div class="linha_report_title">Email</div> ';
	$stroption .= '<div class="linha_report_title">Tratamento</div> ';
	$stroption .= '<div class="linha_report_title">Data Tratamento</div> ';
	$stroption .= '<div class="clear"></div>';
	
	if ($result->num_rows > 0) {
		while ( $linha = $result->fetch_assoc () ) {
			
			$stroption .= '<div class="linha_report"> <a target=”_blank” href="index.php?menu_seq=1&client_seq=' . $linha ["client_seq"] . ' " >' . $linha ["client_name"] . '</a></div>';
			$stroption .= '<div class="linha_report">' . $linha ["client_email"] . '</div>';
			$stroption .= '<div class="linha_report">' . $linha ["tratamento"] . '</div>';
			$stroption .= '<div class="linha_report">' . $linha ["agenda_start_time"] . '</div>';
		}
	}
	
	echo $stroption;
	
	echo " </div>";
}

// Somatório por tratamento

if ($action_report == "6") {
	$sql = " SELECT count(tyse_seq) as qtd_trat,tyse_desc ";
	if ($prof_seq != "")
		$sql .= ",prof_name ";
	if ($client_seq != "")
		$sql .= ",client_name ";
	
	$sql .= "FROM profissional,client,type_service,agenda,agenda_service";
	$sql .= " WHERE  aget_agenda_seq=agenda_seq AND tyse_seq=aget_tyse_seq AND agenda_prof_seq=prof_seq AND client_seq=agenda_client_seq AND ";
	
	$sql .= " agenda_concluded=3 AND";
	
	if ($agenda_start_time != "")
		$sql .= " agenda_start_time >='" . $agenda_start_time . "' AND";
	
	if ($agenda_end_time != "")
		$sql .= " agenda_end_time <='" . $agenda_end_time . "' AND";
	
	if ($prof_seq != "")
		$sql .= " prof_seq=" . $prof_seq . " AND";
	
	if ($client_seq != "")
		$sql .= " client_seq=" . $client_seq . " AND";
	
	$sql .= " agenda_seq > 0 GROUP BY  tyse_desc";
	if ($prof_seq != "")
		$sql .= ",prof_name";
	if ($client_seq != "")
		$sql .= ",client_name";
	
	$sql .= " ORDER BY  tyse_parent_seq,tyse_desc";
	if ($prof_seq != "")
		$sql .= ",prof_name";
	if ($client_seq != "")
		$sql .= ",client_name";
		
		// echo $sql;
	
	$result = $conn->query ( $sql );
	
	echo "<div id='div_report2'>";
	$stroption = '<div class="linha_report_title">Tratamento</div> ';
	if ($prof_seq != "")
		$stroption .= '<div class="linha_report_title">Profissional</div> ';
	if ($client_seq != "")
		$stroption .= '<div class="linha_report_title">Cliente</div> ';
	
	$stroption .= '<div class="linha_report_title">Quantidade</div> ';
	
	$stroption .= '<div class="clear"></div>';
	
	if ($result) {
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_report">' . $linha ["tyse_desc"] . '</div>';
			if ($prof_seq != "")
				$stroption .= '<div class="linha_report">' . $linha ["prof_name"] . '</div>';
			
			if ($client_seq != "")
				$stroption .= '<div class="linha_report">' . $linha ["client_name"] . '</div>';
			$stroption .= '<div class="linha_report">' . $linha ["qtd_trat"] . '</div>';
			$stroption .= '<div class="clear"></div>';
		}
	}
	
	echo $stroption;
	echo " </div>";
}

// Pacotes
if ($action_report == "7") {
	
	$sql = " SELECT count(tyse_seq) as qtd_trat,tyse_desc ";
	if ($prof_seq != "")
		$sql .= ",prof_name ";
	if ($client_seq != "")
		$sql .= ",client_name ";
	
	$sql .= "FROM profissional, client, type_service,inpulsebrasil.agenda,pacotes_type_services";
	$sql .= " WHERE agenda_secl_seq=peqi_secl_seq AND tyse_seq=peqi_tyse_seq AND agenda_prof_seq=prof_seq AND client_seq=agenda_client_seq AND ";
	$sql .= " agenda_concluded=3 AND";
	
	if ($agenda_start_time != "")
		$sql .= " agenda_start_time >='" . $agenda_start_time . "' AND";
	
	if ($agenda_end_time != "")
		$sql .= " agenda_end_time <='" . $agenda_end_time . "' AND";
	
	if ($prof_seq != "")
		$sql .= " prof_seq=" . $prof_seq . " AND";
	
	if ($client_seq != "")
		$sql .= " client_seq=" . $client_seq . " AND";
	
	$sql .= " agenda_seq > 0 GROUP BY  tyse_desc";
	if ($prof_seq != "")
		$sql .= ",prof_name";
	if ($client_seq != "")
		$sql .= ",client_name";
	
	$sql .= " ORDER BY  tyse_desc";
	if ($prof_seq != "")
		$sql .= ",prof_name ";
	if ($client_seq != "")
		$sql .= ",client_name";
		
		// echo $sql;
	
	$result = $conn->query ( $sql );
	
	echo "<div id='div_report2'>";
	$stroption = '<div class="linha_report_title">Tratamento</div> ';
	if ($prof_seq != "")
		$stroption .= '<div class="linha_report_title">Profissional</div> ';
	if ($client_seq != "")
		$stroption .= '<div class="linha_report_title">Cliente</div> ';
	
	$stroption .= '<div class="linha_report_title">Quantidade</div> ';
	
	$stroption .= '<div class="clear"></div>';
	
	if ($result->num_rows > 0) {
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_report">' . $linha ["tyse_desc"] . '</div>';
			if ($prof_seq != "")
				$stroption .= '<div class="linha_report">' . $linha ["prof_name"] . '</div>';
			if ($client_seq != "")
				$stroption .= '<div class="linha_report">' . $linha ["client_name"] . '</div>';
			$stroption .= '<div class="linha_report">' . $linha ["qtd_trat"] . '</div>';
			$stroption .= '<div class="clear"></div>';
		}
	}
	
	echo $stroption;
	echo " </div>";
}

if ($action_report == "8") {
	
	// Imprime a somatória de entrada por profissional
	$sql = " SELECT sum(agenda_valor) as sum_trat ,prof_name ";
	
	$sql .= "FROM profissional,agenda";
	$sql .= " WHERE agenda_prof_seq=prof_seq AND agenda_concluded=3 AND";
	if ($agenda_start_time != "")
		$sql .= " agenda_start_time >='" . $agenda_start_time . "' AND";
	if ($agenda_end_time != "")
		$sql .= " agenda_end_time <='" . $agenda_end_time . "' AND";
	
	$sql .= " agenda_seq > 0 GROUP BY  prof_name";
	if ($prof_seq != "")
		$sql .= ",prof_name";
	if ($client_seq != "")
		$sql .= ",client_name";
	
	$sql .= " ORDER BY agenda_desc,prof_name LIMIT 200";
	
	$result = $conn->query ( $sql );
	
	echo "<div id='div_report_sum'>";
	$stroption = "";
	$stroption .= '<div class="linha_report_title">Profissional</div> ';
	$stroption .= '<div class="linha_report_title">Valor</div> ';
	$stroption .= '<div class="clear"></div>';
	
	if ($result) {
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_report">' . $linha ["prof_name"] . '</div>';
			$stroption .= '<div class="linha_report">' . number_format ( $linha ["sum_trat"], 2, ",", "." ) . '</div>';
			$stroption .= '<div class="clear"></div>';
		}
	}
	
	echo $stroption;
	$stroption = "";
	
	// Imprime a somatoria geral
	$sql = " SELECT sum(agenda_valor) as sum_trat ";
	$sql .= "FROM agenda";
	$sql .= " WHERE agenda_valor > 0 AND agenda_concluded=3 AND";
	if ($agenda_start_time != "")
		$sql .= " agenda_start_time >='" . $agenda_start_time . "' AND";
	if ($agenda_end_time != "")
		$sql .= " agenda_end_time <='" . $agenda_end_time . "' AND";
	
	$sql .= " agenda_seq > 0";
	$sql .= " ORDER BY agenda_desc";
	
	// echo $sql;
	$result = $conn->query ( $sql );
	if ($result) {
		while ( $linha = $result->fetch_assoc () ) {
			
			$stroption .= '<div class="linha_report">TOTAL</div>';
			$stroption .= '<div class="linha_report">' . number_format ( $linha ["sum_trat"], 2, ",", "." ) . '</div>';
			
			$stroption .= '<div class="clear"></div>';
		}
	}
	
	echo $stroption;
	echo " </div>";
	
	// Imprime a somatoria por procedimento
	
	$sql = " SELECT sum(agenda_valor) as sum_trat,tyse_desc ";
	if ($prof_seq != "")
		$sql .= ",prof_name ";
	if ($client_seq != "")
		$sql .= ",client_name ";
	
	$sql .= "FROM profissional,client,type_service,agenda,agenda_service";
	$sql .= " WHERE  aget_agenda_seq=agenda_seq AND tyse_seq=aget_tyse_seq AND agenda_prof_seq=prof_seq AND client_seq=agenda_client_seq AND ";
	$sql .= " agenda_valor > 0 AND agenda_concluded=3 AND";
	
	if ($agenda_start_time != "")
		$sql .= " agenda_start_time >='" . $agenda_start_time . "' AND";
	
	if ($agenda_end_time != "")
		$sql .= " agenda_end_time <='" . $agenda_end_time . "' AND";
	
	if ($prof_seq != "")
		$sql .= " prof_seq=" . $prof_seq . " AND";
	
	if ($client_seq != "")
		$sql .= " client_seq=" . $client_seq . " AND";
	
	$sql .= " agenda_seq > 0 GROUP BY  tyse_desc";
	if ($prof_seq != "")
		$sql .= ",prof_name";
	if ($client_seq != "")
		$sql .= ",client_seq";
	
	$sql .= " ORDER BY tyse_desc";
	if ($prof_seq != "")
		$sql .= ",prof_name";
		
		// echo $sql;
	
	$result = $conn->query ( $sql );
	
	echo "<div id='div_report'>";
	$stroption = '<div class="linha_report_title">Tratamento</div> ';
	if ($prof_seq != "")
		$stroption .= '<div class="linha_report_title">Profissional</div> ';
	if ($client_seq != "")
		$stroption .= '<div class="linha_report_title">Cliente</div> ';
	$stroption .= '<div class="linha_report_title">Valor</div> ';
	$stroption .= '<div class="clear"></div>';
	if ($result) {
		while ( $linha = $result->fetch_assoc () ) {
			$stroption .= '<div class="linha_report">' . $linha ["tyse_desc"] . '</div>';
			if ($prof_seq != "")
				$stroption .= '<div class="linha_report">' . $linha ["prof_name"] . '</div>';
			
			if ($client_seq != "")
				$stroption .= '<div class="linha_report">' . $linha ["client_name"] . '</div>';
			$stroption .= '<div class="linha_report">' . number_format ( $linha ["sum_trat"], 2, ",", "." ) . '</div>';
			$stroption .= '<div class="clear"></div>';
		}
	}
	
	echo $stroption;
	echo " </div>";
}


// verifica se a opçao é clientes novos
if ($action_report == "9") {

	// Imprime a somatória de entrada por profissional
	
	$sql = " SELECT SUM(caixa_valor) valor_gasto, caixa_desc, client_name ";
	$sql .= "FROM lancamento_caixa, client WHERE caixa_client_seq=client_seq AND ";
	if ($agenda_start_time != "")
		$sql .= " caixa_data >='" . $agenda_start_time . "' AND";
	if ($agenda_end_time != "")
		$sql .= " caixa_data <='" . $agenda_end_time . "' AND";

	$sql .= " caixa_seq > 0 GROUP BY caixa_desc, client_name";

	$result = $conn->query ( $sql );
	

	echo "<div id='div_report'>";
	$stroption = "";
	$stroption .= '<div class="linha_report_title">Cliente</div> ';
	$stroption .= '<div class="linha_report_title">Tratamento</div> ';
	$stroption .= '<div class="linha_report_title">Valor Gasto</div> ';
	$stroption .= '<div class="clear"></div>';

	if ($result) 
		{
		while ( $linha = $result->fetch_assoc () ) 
		{
			$stroption .= '<div class="linha_report">' . $linha ["client_name"] . '</div>';
			$stroption .= '<div class="linha_report">' . $linha ["caixa_desc"] . '</div>';
			$stroption .= '<div class="linha_report">' . number_format ( $linha ["valor_gasto"], 2, ",", "." ) . '</div>';
			$stroption .= '<div class="clear"></div>';
		}
	}

	echo $stroption;

	echo " </div>";
}


?>