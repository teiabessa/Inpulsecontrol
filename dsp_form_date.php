
<?php
// Eu apresento o formulário de data da tela de agendamento
// obtém o valor passado da datta a ser pesquisada
if (isset($_REQUEST['agenda_start_time']))

	$agenda_start_time= $_REQUEST['agenda_start_time'];
else
	$agenda_start_time=date('Y-m-d');

//Verifica se foi clicado no next ou last day
if (isset($_GET['dia']))
{
//Obtém a funcao do mysql que adicona 1 dia a data atual	
	$sql="select date_add('". $agenda_start_time. "', INTERVAL ". $_GET['dia'].  " DAY) as dia_mais";
	//echo " <br>Sql date add antes de exec.: <br> " . $sql;
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc())
		$agenda_start_time=$row["dia_mais"];
	
}

echo "<div id='novoagendamento'><a href='dsp_form_agenda.php?&agenda_date=". $agenda_start_time. "'>Novo Agendamento</a></div>";
/*

$sql="select msg_nome, msg_telefone FROM mensagem_cliente where msg_seq > 0 and msg_status not in (3,4)";
$result = $conn->query($sql);
echo"<div id='tabela_lig_pend'>";
while($row = $result->fetch_assoc()){
	
	//echo"<div id='LigacoesPendentes'><a href='index.php?menu_seq=4&prof_seq=".$row['clica_prof_seq']."&search_clica=yes'>".$row['prof_name'].utf8_encode(" | Ligações pendentes:").$row['ligacoes']."</a></div>";
	echo"<div id='LigacoesPendentes'><a href='index.php?menu_seq=6&msg_seq=".$row['msg_seq']."'>".substr($row["msg_nome"], 0,15). "|" .substr($row["msg_telefone"], 0,15)."</a></div>";
}
echo"</div>";

*/



$sql="select prof_name, count(clica_seq) as ligacoes, count(case when clica_stali_seq=7 then clica_seq end) as aguardando, clica_prof_seq FROM inpulsebrasil.client_call, inpulsebrasil.profissional where prof_seq=clica_prof_seq and clica_activate=0 and prof_seq>0 group by prof_name";
$result = $conn->query($sql);
echo"<div id='tabela_lig_pend'>";
while($row = $result->fetch_assoc()){
	//echo"<div id='LigacoesPendentes'><a href='index.php?menu_seq=4&prof_seq=".$row['clica_prof_seq']."&search_clica=yes'>".$row['prof_name'].utf8_encode(" | Ligações pendentes:").$row['ligacoes']."</a></div>";
	echo"<div id='LigacoesPendentes'><a href='index.php?menu_seq=4&prof_seq=".$row['clica_prof_seq']."&search_clica=yes'>".$row['prof_name']. ":" .$row['ligacoes']."|".$row['aguardando']."</a></div>";
}
echo"</div>";



$diasemana=array('Domingo','Segunda-Feira', 'Terça-Feira', 'Quarta-Feira','Quinta-Feira','Sexta-Feira', 'Sábado');
$diasemana_n=date('w',strtotime($agenda_start_time));
//date('Y-m-d h:m:s');

$field='<div class="divalldata"> <div class="divfrmdate">  <form ACTION="index.php?menu_seq=0" METHOD="POST" id="formdate2" name="formdate2">';
$field.='Agendamento:<input  type="date" value="'. $agenda_start_time . '" class="frmdataagenda" name="agenda_start_time" id="agenda_start_time">';
$field.='<input type="submit" class="frmsubmitagenda"  value="Search"> </form> </div>';

$field.="<br><div class='toolbar'>";
$field.="<a  class='nextdate'  href='index.php?menu_seq=0&dia=-1&agenda_start_time=". $agenda_start_time ."'> < </a>";
$field.="<span >". utf8_encode($diasemana[$diasemana_n]) . "<span>";
$field.="<a  class='nextdate'  href='index.php?menu_seq=0&dia=1&agenda_start_time=". $agenda_start_time ."'> > </a>";
$field.="</div></div>";
echo $field;

//echo "sair";
/*
$field="<div id='toolbar_nova'>";
$field.="<a href='dsp_form_agenda.php?&agenda_date=". $agenda_start_time. "'>N Agenda</a>";
$field.="<br>  <a target=”_blank” href='docs/tabela_precos.pdf'". "'> Tabela de Preco </a>";
$field.="</div>";
echo $field;

*/

?>

	

