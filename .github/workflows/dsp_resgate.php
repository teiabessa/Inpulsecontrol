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
	$action_report = "10";
	

	
	
// obtém o valor passado da datta a ser pesquisada
if (isset ( $_REQUEST ['sel_tyse_seq'] ))
	$tyse_seq = $_REQUEST ['sel_tyse_seq'];

	

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

//Tipo de tratamento
	$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=0";
				$result = $conn->query($sql);
				echo '<div id="div1">';
				$vreturn='Selecione o Tratamento, Pressione Add Campo e depois entre com os valores: <select name="sel_tyse_seq"  id="sel_tyse_seq" required class="frmclient" >';
			
				// Coloca os tratamentos na variavél return
				while ($linha = $result->fetch_assoc())
				{	
					$vreturn.= '<option value="'. $linha["tyse_seq"]. '">'. $linha["tyse_desc"]. '</option>';
					//Faz a busca em nichos
			
					$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=". $linha["tyse_seq"] . " Order by tyse_desc ASC";
					$result2 = $conn->query($sql);
					$vreturn.= $linha["tyse_desc"];
					// Atribui o código HTML para montar uma tabela
					while ($linha2 = $result2->fetch_assoc())
					{
							
						$vtyse_desc=$linha2["tyse_desc"];
						$vreturn.= '<option value="'. $linha2["tyse_seq"].'" ';
							
							
						$vreturn.= ' >'. $vtyse_desc . '</option>';
					}
			
				}
			
				$vreturn.='</select>';
	echo $vreturn;
			


// Campo select com opção de agendamento
$stroption = 'Tipo de Relatório <select class="frmclient" name="action_report" id="action_report" >';


$stroption .= '<option value= "10"';
if ($action_report == "10")
	$stroption .= 'selected';
$stroption .= '>Clientes para Resgatar </option>';

$stroption .= '</select>';

echo $stroption;

$field = '<input type="submit" class="frmsubmit"  value="Consultar" name="busca_submit">  </form></div></div>';

echo $field;





// Ultimos 5 meses de atendimento

if ($action_report == "10") {
	// anterior
	
$sql ="SELECT DISTINCT client_seq, prof_name, client_name as Nome, date_format(agenda_start_time,'%d/%m/%Y') as data_ult_atend, a1.agenda_desc as Tratamento, tecl1.tecl_desc as Contato  FROM   profissional,agenda_service, type_service,agenda a1, client LEFT JOIN telefone_clients tecl1 on tecl1.tecl_client_seq=client_seq where client_seq= a1.agenda_client_seq  and  agenda_start_time >= '". $agenda_start_time . "' and client_seq <> 2115 and aget_agenda_seq=a1.agenda_seq and aget_tyse_seq=tyse_seq and  client_seq > 0 and client_seq not in (1814,1211,1448) and  datediff(now(),agenda_start_time) > tyse_duracao and agenda_start_time < now() and 
    tecl1.tecl_seq in ( SELECT max(tecl2.tecl_seq)";

//Verifica se foi definido cliente, profissional, lista de tratamento
    if ($prof_seq != "")
		$sql .= " prof_seq=" . $prof_seq . " AND";
	
	if ($client_seq != "")
		$sql .= " client_seq=" . $client_seq . " AND";
		
	if ($tyse_seq != "")
		$sql .= " tyse_seq in (" . $tyse_seq . ") AND";
		

	$sql .="AND a1.agenda_prof_seq=prof_seq AND NOW() > agenda_start_time FROM  telefone_clients tecl2
where tecl2.tecl_client_seq=tecl1.tecl_client_seq and tecl_operadora <> 'FIXO' and tecl2.tecl_desc <> '' 
order by tecl2.tecl_desc desc ) 
and a1.agenda_seq in ( SELECT max(b2.agenda_seq) 
FROM agenda b2
where b2.agenda_client_seq=a1.agenda_client_seq  
order by b2.agenda_start_time desc
) 
order by agenda_start_time desc";

	// echo $sql;
	
	$result = $conn->query ( $sql );
	
	echo "<div id='div_report'>";
	
	$stroption = '<div class="linha_report_title">Nome</div> ';
	$stroption .= '<div class="linha_report_title">Tratamento</div> ';
	$stroption .= '<div class="linha_report_title">Data Ult. Atendimento</div>';
	$stroption .= '<div class="linha_report_title">Contato</div> ';
	$stroption .= '<div class="linha_report_title">Prof. Atend</div> ';
	$stroption .= '<div class="clear"></div>';
	
	if ($result->num_rows > 0) {
		while ( $linha = $result->fetch_assoc () ) {
			
			$stroption .= '<div class="linha_report"> <a target=”_blank” href="index.php?menu_seq=1&client_seq=' . $linha ["client_seq"] . ' " >' . $linha ["Nome"] . '</a></div>';
		
			$stroption .= '<div class="linha_report">' . $linha ["Tratamento"] . '</div>';
			$stroption .= '<div class="linha_report">' . 
			$linha ["data_ult_atend"] . '</div>';
			
			$stroption .= '<div class="linha_report">' . $linha ["Contato"] . '</div>';
			
			$stroption .= '<div class="linha_report">' . $linha ["prof_name"] . '</div>';
		}
	}
	
	echo $stroption;
	
	echo " </div>";
}

?>