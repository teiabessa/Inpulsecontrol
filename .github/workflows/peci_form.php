<div id='divfrmpeci'>
<span id="divtitlesearch"><?php echo utf8_encode("Entre com as informações do Pacote:");?></span>
  
<?php


if (isset($_REQUEST['secl_seq']))
{
		
	$sql= "SELECT secl_agenda_sala, secl_agenda_type_pagto, secl_agenda_cheque, secl_days_weak, secl_qtd_sessao_realizada,secl_valor_vendido,secl_prof_seq, secl_status_pagto,secl_contrato_seq, date_format(secl_data_agendamento,'%Y-%m-%d') as agenda_date,time_format(secl_data_agendamento,'%H:%i') as time_start, time_format(secl_end_time,'%H:%i') as time_end   FROM pacote_servico_cliente Where secl_seq=". $_REQUEST['secl_seq'];
	//echo $sq; 
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		while ($linha = $result->fetch_assoc())
		{
			$secl_days_weak =" ". $linha["secl_days_weak"];
			$secl_prof_seq = $linha["secl_prof_seq"];
			$agenda_start_time=$linha["time_start"];
			$agenda_end_time=$linha["time_end"];
			$secl_data_agendamento=$linha["agenda_date"];
			$secl_qtd_sessao_realizada=$linha["secl_qtd_sessao_realizada"];
			$secl_valor_vendido=$linha["secl_valor_vendido"];
			$secl_status_pagto=$linha["secl_status_pagto"];
			$secl_contrato_seq=$linha["secl_contrato_seq"];
			$agenda_sala=$linha["secl_agenda_sala"];
			$agenda_type_pagto=$linha["secl_agenda_type_pagto"];
			$secl_agenda_cheque=$linha["secl_agenda_cheque"];
			
				
		}
	}
}




$sql = "Select * FROM profissional ORDER BY prof_name";
$result = $conn->query($sql);
$stroption="";

echo 'Profissional:<select class="frmclient" name="prof_seq" id="prof_seq">';
// Atribui o código HTML para montar uma tabela
while ($linha = $result->fetch_assoc())
{	
	if ($linha["prof_seq"] == $secl_prof_seq)
		$stroption.='<option value=" '. $linha["prof_seq"]. ' " selected >'. $linha["prof_name"]. '</option>';
	else
		$stroption.='<option value="'. $linha["prof_seq"] . '">'. $linha["prof_name"]. '</option>';
 }

echo $stroption;
echo '</select>';

// Campo select com opção de agendamento
$stroption='Sala de Atendimento:';
$stroption.= '<select class="frmclient" name="secl_agenda_sala" id="secl_agenda_sala" >';

$stroption.='<option value= "1" ';
if ($agenda_sala == 1)
	$stroption.='selected';
$stroption.='>Sala 1</option>';
 
$stroption.='<option value= "2"';
if ($agenda_sala == 2)
	$stroption.='selected';
$stroption.='>Sala 2</option>';
 
$stroption.='<option value= "3"';
if ($agenda_sala == 3)
	$stroption.='selected';
$stroption.='>Sala 3</option>';
 
$stroption.='<option value= "4"';
if ($agenda_sala == 4)
	$stroption.='selected';
$stroption.='>Sala 4</option>';
 
$stroption.= '</select>';
echo $stroption;
 
	
$strsecl.='Valor Vendido:<input class="frmclient"   type="number" name="secl_valor_vendido"  id="secl_valor_vendido"  size="15" value="'.$secl_valor_vendido .'">';
$strsecl.='Quantidade de '. utf8_encode('Sessões'). ' Realizadas:<input class="frmclient" type="number" name="secl_qtd_sessao_realizada" id="secl_qtd_sessao_realizada" size="15" value="'.$secl_qtd_sessao_realizada .'">';


$strsecl='Valor Vendido:<input class="frmclient"   type="number" name="secl_valor_vendido"  id="secl_valor_vendido"  size="15" value="'.$secl_valor_vendido .'">';
$strsecl.='Quantidade de '. utf8_encode('Sessões'). ' Realizadas:<input class="frmclient" type="number" name="secl_qtd_sessao_realizada" id="secl_qtd_sessao_realizada" size="15" value="'.$secl_qtd_sessao_realizada .'">';
// seta agendamento default para avulso


$strfield= 'Data do Agendamento: <input class="frmclient" type="date" name="secl_data_agendamento" id="secl_data_agendamento" size="30" ';
$strfield.= 'value="'.$secl_data_agendamento.'">';
echo $strfield;

$strfield= 'Hora Inicial: <input class="frmclient"  type="time" name="agenda_start_time" id="agenda_start_time" size="30"';
$strfield.= 'value="'.$agenda_start_time.'">';
echo $strfield;

$strfield= 'Hora Final: <input class="frmclient" type="time" name="agenda_end_time" id="agenda_end_time" size="30"';
$strfield.= 'value="'.$agenda_end_time.'">';
echo $strfield;

$lista_semana = array("Domingo","Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado");
$lista_days = array("0", "1","2", "3", "4", "5","6");

$lista_semana = array("Domingo","Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado");
$lista_days = array("0", "1","2", "3", "4", "5","6");


$vreturn='Dias da Semana:<select multiple name="secl_days_weak[]" id="secl_days_weak"  class="frmclient"  size="6" >';
for ($x = 0; $x <= 6; $x++) 
{
	$vreturn.= '<option value="'. $lista_days[$x].'" ';
	// Verifica se existe no array
	if (strpos($secl_days_weak,$lista_days[$x],0 ))
		$vreturn.= 'selected';
	$vreturn.= ' >'. utf8_encode($lista_semana[$x]) . '</option>';
}
$vreturn.='</select>';
echo $vreturn;

$strsecl.='Num. Contrato: <input  class="frmclient" type="number" name="secl_contrato_seq" id="secl_contrato_seq" size="7" value="'.$secl_contrato_seq .'">';
echo $strsecl;

// Campo select com opção do tipo do pagamento
$stroption='Tipo de Pagamento:';
$stroption.= '<select class="frmclientside" name="secl_agenda_type_pagto" id="secl_agenda_type_pagto" >';
 
$stroption.='<option value= "0"';
if ($agenda_type_pagto == "0")
	$stroption.='selected';
$stroption.='>Nenhum</option>';

$stroption.='<option value= "1"';
if ($agenda_type_pagto == "1")
	$stroption.='selected';
$stroption.='>Dinheiro</option>';

$stroption.='<option value= "2"';
if ($agenda_type_pagto == "2")
	$stroption.='selected';
$stroption.='>'. utf8_encode("Cartão"). '</option>';

$stroption.='<option value= "3"';
if ($agenda_type_pagto == "3")
	$stroption.='selected';
$stroption.='>Cheque</option>';

$stroption.= '</select>';
echo $stroption;
	

$strfield= 'Dados do Cheque: <input class="frmclient" type="text" name="secl_agenda_cheque" id="secl_agenda_cheque" size="20"';
$strfield.= 'value="'.$secl_agenda_cheque .'">';
echo $strfield;
	


//Eu faço a busca no banco de dados de todos os registros de clientes


	//Sentença de busca no banco
	$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=0"; 
	$result = $conn->query($sql);
	
?>

                               
	<div id="d1">
	Tratamentos:<select name="tyse_seq" id="tyse_seq" >
	<?php
	if ($result->num_rows > 0)
	{
	
		// Atribui o código HTML para montar uma tabela
		while ($linha = $result->fetch_assoc())
		{
			
			echo '<option value="'. $linha["tyse_seq"]. '">'. $linha["tyse_desc"]. '</option>'; 
			//Faz a busca em nichos
			$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=". $linha["tyse_seq"] . " Order by tyse_desc ASC";
			
			$result2 = $conn->query($sql);
			echo $linha["tyse_desc"];
			// Atribui o código HTML para montar uma tabela
			while ($linha2 = $result2->fetch_assoc())
             {
				$vtyse_desc=chr(32). $linha2["tyse_desc"];
				echo '<option value="'. $linha2["tyse_seq"].'">'. $vtyse_desc . '</option>';
             }
					
		}
		
	
    }
    
    
		?>
		</select>
		<INPUT type="button" value="Add Campo" name="newtratamento" onClick="addInput('dynamicInput')" class="frmsubmit">
		<INPUT type="button" value="Apaga Campo" name="delpeci" onClick="DeleteFiels('dynamicInput')" class="frmsubmit">
	
	</div>
	
	<div id="dynamicInput">
	  <?php
	  if (isset($_REQUEST['secl_seq']))
	  {
       
	  		//	Seleciona os tipos de tratamentos associados ao pacote da cliente atual
	  		$sql = "Select *  FROM pacotes_type_services WHERE peqi_secl_seq=".$_REQUEST['secl_seq'];
	  		$result = $conn->query($sql);
	  		if ($result->num_rows > 0)
	  		{	
	  			while ($linha = $result->fetch_assoc())
	  			{
					echo ' <input type="hidden"  name="peqi_tyse_seq[]"value="'. $linha["peqi_tyse_seq"]. '">';
			  		echo '<br><input type="text" name="peqi_desc[]" value="'. $linha["peqi_desc"]. '">';
			  		echo '<input type="text" size="10"  name="peqi_qtd_sessoes[]" value="' . $linha["peqi_qtd_sessoes"]. '">';
				}
	  		}
	  }
	  
     echo "</div>";

     				
		?>
     
	
</div>













