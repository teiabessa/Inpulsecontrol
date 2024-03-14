
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

$sql="select msg_nome, msg_telefone FROM mensagem_cliente where msg_seq > 0 and msg_status not in (3,4)";
$result = $conn->query($sql);
echo"<div id='tabela_lig_pend'>";
while($row = $result->fetch_assoc()){
	//echo"<div id='LigacoesPendentes'><a href='index.php?menu_seq=4&prof_seq=".$row['clica_prof_seq']."&search_clica=yes'>".$row['prof_name'].utf8_encode(" | Ligações pendentes:").$row['ligacoes']."</a></div>";
	echo"<div id='LigacoesPendentes'><a href='index.php?menu_seq=6&msg_seq=".$row['msg_seq']."'>".$row['msg_nome']. "|" .$row['msg_telefone']."</a></div>";
}
echo"</div>";


$diasemana=array('Domingo','Segunda-Feira', 'Terça-Feira', 'Quarta-Feira','Quinta-Feira','Sexta-Feira', 'Sábado');
$diasemana_n=date('w',strtotime($agenda_start_time));


//echo "<div id='novoagendamento'><a href='dsp_form_agenda.php?&agenda_date=". $agenda_start_time. "'>Novo Agendamento</a></div>";
$field='<div class="divalldata">';
$field.='<div id="novoagendamento"><a href="dsp_form_agenda.php?&agenda_date="'. $agenda_start_time. "'>Novo Agendamento</a></div>';
 
 
$field.='Agendamento:<input  type="date" value="'. $agenda_start_time . '" class="frmdataagenda" name="agenda_start_time" id="agenda_start_time">';
$field.='<input type="submit" class="frmsubmitagenda"  value="Search"> </form> </div>';



//date('Y-m-d h:m:s');
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

	

