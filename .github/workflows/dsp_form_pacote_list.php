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
	$secl_days_weak = "";
	$agenda_start_time="";
	$agenda_end_time="";
	$secl_data_agendamento=date('Y-m-d');
	$secl_agenda_sala="1";
	$secl_agenda_type_pagto="0";
	$secl_agenda_cheque="";
		
	
	 
	$b_secl_action='Inserir';
	
	//Sentença de busca no banco
    $sql = "Select  client_seq, client_name FROM client ORDER BY client_name"; 
	$result = $conn->query($sql);
	
	$strfield='<div id="divallfrmpacote">';
	$strfield.='<FORM ACTION="act_ins_secl.php" METHOD="POST" id="formclient" name="formclient">';
	$strfield.= '<div id="divfrmsecl">';
	//$strfield.='Cliente:<select class="frmclient" name="client_seq" id="client_seq">';
	$strfield.='Cliente:<select class="frmclient" name="client_seq" id="client_seq" onchange="modifyclient()">';
	echo $strfield;
	$stroption="";
	while ($linha = $result->fetch_assoc())
	{
		$stroption.='<option value="'. $linha["client_seq"]. '"';
		//echo $linha["client_name"];
		 if ($linha["client_seq"] == $_GET['client_seq'])
			$stroption.='selected';
		//$stroption.='<option value="'. $linha["client_seq"]. '"';
		$stroption.='>'. $linha["client_name"]. '</option>';
			
	}
	echo $stroption;
	echo '</select>';
	echo ' <div id="dynpacotes">';
	// Só entra aqui se foi definido o cliente
	if (isset($_REQUEST['client_seq']))
	{
		$sql= "SELECT * FROM pacote_servico_cliente Where secl_client_seq=". $_REQUEST['client_seq'];
	
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$return='Pacotes:<select name="secl_seq" id="secl_seq" class="frmclient"  onchange="modifyall()" >';
			$return.= '<option value="0"></option>';
			// Atribui o código HTML para montar uma tabela
			while ($linha = $result->fetch_assoc())
			{
				
				if ($linha["secl_seq"] == $_REQUEST['secl_seq'])
					$return.= '<option value="'. $linha["secl_seq"]. '"selected >'. $linha["secl_desc"]. '</option>';
				else
					$return.= '<option value="'. $linha["secl_seq"]. '">'. $linha["secl_desc"]. '</option>';
		  	}
			//fecha o select
			$return.= '</select>';
			echo  $return;
		}
	}
		
		echo  '</div>';
		
		
	//	echo $strsecl;
		echo "</div>";
		
		
	
	/* Invoca o arquivo que mostra o formulario dos tipos dos pacotes */
	require_once 'peci_form.php';
	
	echo '<INPUT type="submit" value="Criar Pacote" id="ins_secl" name="ins_secl"  class="frmsubmit">';
	//if($b_secl_action == 'Editar')
	echo '<INPUT type="submit" value="Alterar Pacote" id="upd_secl" name="upd_secl"  class="frmsubmit">';
		
	//else 
	
		
		echo '<INPUT type="submit" value="'. utf8_encode('Criar Sessões'). '" id="ins_agenda" name="ins_agenda"  class="frmsubmit">';
	
	echo" </form> </div>";                              

 
 mysqli_close($conn); 

?>


