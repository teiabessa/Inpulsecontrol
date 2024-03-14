<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

//Eu invoco o hearder
	/* Invoca o arquivo que faz conexão com o db */
	require_once 'db.php';
	//Inicia o valor das variáveis dos pacotes do cliente selecionado
	$secl_seq='';
	$secl_desc='';
	$secl_prof_seq='';
	$secl_valor_vendido='0';
	$secl_qtd_sessao_realizada='10';
	$secl_contrato_seq='0';
	$secl_status_pagto='';
	$secl_days_weak = "1";
	$agenda_start_time="";
	$agenda_end_time="";
	$secl_data_agendamento=date('Y-m-d');
	$secl_data_venda="";
	$secl_agenda_sala="1";
	$secl_agenda_type_pagto="0";
	$secl_agenda_cheque="";
	$secl_parcelas="1";
	$secl_prof_venda="";
	$secl_form_venda="0";
		
	
	 
	$b_secl_action='Inserir';
	
	//Sentença de busca do cliente
    $sql = "Select  client_seq, client_name FROM client where client_seq > 0 ORDER BY client_name"; 
	$result = $conn->query($sql);
	echo '<div id="all">';
	$strfield='<div  id="divallfrmpacote">';
	echo $strfield;
	
	$field='<div id="formularioCliente"><form ACTION="index.php?menu_seq=2" METHOD="POST" id="formclient2" name="formclient2">';
	echo $field;

	//$strfield.= '<div id="divfrmsecl">';
	//$strfield.='Cliente:<select class="frmclient" name="client_seq" id="client_seq">';
	$strfield='Cliente:<select class="frmclient" name="client_seq" id="client_seq" required>';
	echo $strfield;
	$stroption="";
	$stroption.='<option value=""></option>';
	//exibe os clientes
	while ($linha = $result->fetch_assoc())
	{
		$stroption.='<option value="'. $linha["client_seq"]. '"';
		//echo $linha["client_name"];
		 if (($linha["client_seq"] == $_POST['client_seq']) || ($linha["client_seq"] == $_GET['client_seq']))
			$stroption.='selected';
		//$stroption.='<option value="'. $linha["client_seq"]. '"';
		$stroption.='>'. $linha["client_name"]. '</option>';
	}
	echo $stroption;
	echo '</select>';
	$field='<input type="submit" class="frmsubmit"  value="Consultar" name="busca_submit"></form>';
 	echo $field;
	//echo '</form>';
	// Só entra aqui se foi definido o cliente
	if (isset($_REQUEST['client_seq']))
	{
		$sql= "SELECT * FROM pacote_servico_cliente Where secl_client_seq=". $_REQUEST['client_seq'];
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
	 		echo "<div id='div_pacotes_clientes'>";
	 		$stroption='<div class="linha_report_title">Pacote</div> ';
	 		$stroption.='<div class="linha_report_title">Data</div> ';
	 		$stroption.='<div class="linha_report_title">Valor</div> ';		
	 		$stroption.='<div class="clear"></div>';
 	
	 		if ($result)
	 		{
	 			while ($linha = $result->fetch_assoc())
	 			{
	 				$stroption.='<div class="linha_report"><a target=”_blank”  href="index.php?menu_seq=2&secl_seq='. $linha["secl_seq"].'&client_seq='. $linha["secl_client_seq"].  '">'. $linha["secl_desc"]. '</a></div>';
	 				$stroption.='<div class="linha_report">'. $linha["secl_data_venda"].  '</div>';
	 				$stroption.='<div class="linha_report">'. $linha["secl_valor_vendido"].  '</div>';
	 	
	 			}
	 		}
 			echo $stroption;
 			echo "</div>";
 		}
 	}
	echo  '</div>';
		
		
	//	echo $strsecl;
	//echo "</div>";


			
		
	$strfield='<div id="formPacote"><FORM ACTION="act_ins_secl.php" METHOD="POST" id="formclient" name="formclient" >';	
	echo $strfield;
	
	/* Invoca o arquivo que mostra o formulario dos tipos dos pacotes */
	//if (isset($_GET['secl_seq']))
	//{
		require_once 'peci_form.php';
	
	
	//se foi definido pacote
		if (!isset($_GET['secl_seq']))
	
		{
		echo '<div id="botao_pacotes">';
		echo '<INPUT type="submit" value="Inserir Pacote" id="ins_secl" name="ins_secl"  class="frmsubmit">';
		}
		else

		{	
		echo '<INPUT type="submit" value="Alterar Pacote" id="upd_secl" name="upd_secl"  class="frmsubmit">';

		echo '<INPUT type="reset" value="Limpar Forms" id="clean_secl" name="clean_secl"  class="frmsubmit">';
		echo '<INPUT type="submit" value="'. utf8_encode('Criar Sessões'). '" id="ins_agenda" name="ins_agenda"  class="frmsubmit">';
		echo '<INPUT type="submit" value="Inserir Caixa" id="caixa_secl" name="caixa_secl"  class="frmsubmit">';
		echo '<INPUT type="submit" value="Apagar Pacote" id="del_secl" name="del_secl" onclick="click_del_secl();" class="frmsubmit">';
		echo '</div>';	
	}
	//}	
	
	echo" </form> </div>";                              
	echo '</div> </div>'; // all
 
 mysqli_close($conn); 

?>


