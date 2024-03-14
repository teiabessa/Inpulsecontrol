<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
</script>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu faço a busca no banco de dados de todos os registros de clientes

/* Invoca o arquivo que faz conexão com o db */
require_once 'dsp_header.php';

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

// seta agendamento default para avulso
$aget_tyse_seq = array();

if (isset($_REQUEST['agenda_seq']))
	{
		$agenda_seq=$_REQUEST['agenda_seq'];
		$sql = "SELECT  agenda_prof_log,agenda_log_comments, agenda_concluded, agenda_valor, agenda_cheque, agenda_prof_log,agenda_parcelas, agenda_seq,  time_format(agenda_end_time,'%H:%i') as time_end, time_format(agenda_start_time,'%H:%i') as time_start, date_format(agenda_start_time,'%Y-%m-%d') as agenda_date, agenda_prof_seq,  agenda_type_pagto, agenda_type, agenda_client_seq, agenda_sala, agenda_outros, agenda_secl_seq FROM agenda WHERE agenda_seq=".$agenda_seq;
	    
		$result = $conn->query($sql);
		
		if ($result->num_rows)
		{
			// Atribui o código HTML para montar uma tabela
			while ($linha = $result->fetch_assoc())
			{
			//Inicia o valor das variáveis da agenda
			$agenda_prof_seq=$linha["agenda_prof_seq"];
			$agenda_client_seq=$linha["agenda_client_seq"];
			$agenda_type=$linha["agenda_type"];
			$agenda_date=$linha["agenda_date"];
			$agenda_start_time=$linha["time_start"];
			$agenda_end_time=$linha["time_end"];
			$agenda_concluded=$linha["agenda_concluded"];
			$agenda_type_pagto=$linha["agenda_type_pagto"];
			$agenda_secl_seq=$linha["agenda_secl_seq"];
			$agenda_sala=$linha["agenda_sala"];
			$agenda_outros=$linha["agenda_outros"];
			$agenda_valor=$linha["agenda_valor"];
			$agenda_parcelas=$linha["agenda_parcelas"];
			$agenda_cheque=$linha["agenda_cheque"];
			$agenda_log_comments=$linha["agenda_log_comments"];
			$agenda_prof_log=$linha["agenda_prof_log"];
			$b_secl_action='Update';
			}
			
		}
       //Seleciona os tipos de servicos dessa agenda
		$sql2= "Select aget_tyse_seq  from agenda_service where aget_agenda_seq=" .  $agenda_seq ;
		$result2 = $conn->query($sql2);
		if ($result2->num_rows > 0)
		{
			while ($linha2 = $result2->fetch_assoc())
			  array_push($aget_tyse_seq, $linha2["aget_tyse_seq"]);
		}
		if (isset($_GET["agenda_desconto"]))
			$agenda_desconto=$_GET['agenda_desconto'];
		else
			$agenda_desconto=0;
		
	}
else
	{ 
		
	//Inicia o valor das variáveis dos pacotes do cliente selecionado
	$agenda_seq=0;
	$agenda_type=1;
	$agenda_start_time='';
	$agenda_end_time='';
	$agenda_concluded='';
	$agenda_type_pagto='';
	$agenda_sala='';
	$agenda_secl_seq='';
	$agenda_outros='';
	$agenda_desconto=0;	
	$agenda_valor=0;
	$agenda_parcelas=1;
	$agenda_cheque='';
	$b_secl_action='Inserir';
	$aget_tyse_seq[0]=0;
	$agenda_prof_log=0;
	$agenda_log_comments="Agendamento Criado";
	
        //verifica se foi definido uma data da tela de agendamento
	if (isset($_REQUEST["agenda_date"]))
		$agenda_date=$_REQUEST['agenda_date'];
	else
		$agenda_date="";	
	//verifica se foi definido o cliente
	if (isset($_REQUEST["client_seq"]))
		$agenda_client_seq=$_REQUEST['client_seq'];
	else
		$agenda_client_seq="";
	
	//verifica se foi definido o profissional
	if (isset($_REQUEST["prof_seq"]))
		$agenda_prof_seq=$_REQUEST['prof_seq'];
	else 
		$agenda_prof_seq='';
		
	}
    echo '<div id="divallfrmagenda">';
    /* Invoca o arquivo que exibe o formulario */
    require_once 'uploadfile.php';
    
    
	?>

<div id="frmagenda">
<b>Dados do Agendamento:</b>
	<FORM ACTION="act_ins_agenda.php" METHOD="POST" id='formagenda' name='formagenda'>
	     <?php
	     require_once 'dsp_client_browser.php';
	     //$stroption='<br> Cliente: <select class="frmclient" name="client_seq" id="client_seq" >';
	     ?>
	     Cliente: <select class="frmclient" name="client_seq" id="client_seq" onchange="addPacote('tratamento')">
	      <?php
	     //Busca o cliente no banco
	     $sql = "Select * FROM client where client_seq > 0 ORDER BY client_name ";
	     $result = $conn->query($sql);
	     
	     $stroption.='<option value="">';
	     // Monta o select do cliente
	     while ($linha = $result->fetch_assoc())
	     {
	     	$stroption.='<option value="'. $linha["client_seq"]. '"';
	     	if ($linha["client_seq"] == $agenda_client_seq)
	     		$stroption.='selected';
	     	$stroption.='>'. $linha["client_name"]. '</option>';
	     
	     }
	     
	     $stroption.='</select>';
	     echo $stroption;
	     // Tem que deixar nesse formato o código
	     ?> 
	     Tipo de Agendamento<select class="frmclient" name="agenda_type" id="agenda_type" onchange="addPacote('tratamento')" >
	     
	     <?php
	     		     		
	     	$stroption='<option value= "1"';
	     	if ($agenda_type == "1")
	     		$stroption.='selected';
	     	$stroption.='>Avulso </option>';
	     	
	     	$stroption.='<option value= "2"';
	     	if ($agenda_type == "2")
	     		$stroption.='selected';
	     	$stroption.='>Pacote</option>';
	     	
	     	$stroption.='<option value= "3"';
	     	if ($agenda_type == "3")
	     		$stroption.='selected';
	     	$stroption.='>Cortesia</option>';
	     	
	     	$stroption.='<option value= "4"';
	     	if ($agenda_type == "4")
	     		$stroption.='selected';
	     	$stroption.='>Treinamento</option>';
	     	
	     	$stroption.='<option value= "5"';
	     	if ($agenda_type == "5")
	     		$stroption.='selected';
	     	$stroption.='>Permuta</option>';
	     	
	     	$stroption.='<option value= "6"';
	     	if ($agenda_type == "6")
	     		$stroption.='selected';
	     	$stroption.='>' .utf8_encode('Avaliação'). '</option>';
	     	 
	     	$stroption.='<option value= "7"';
	     	if ($agenda_type == "7")
	     		$stroption.='selected';
	     	$stroption.='>Outros</option>';
	     		
	     	$stroption.= '</select>';
	     	echo $stroption;
	     	 
	     	
	     	  
	     	// Campo select com opção de agendamento
	     	$stroption='Sala de Atendimento:';
	     	$stroption.= '<select class="frmclient" name="agenda_sala" id="agenda_sala" >';
	     	 
	     	$stroption.='<option value= "1" ';
	     	if ($agenda_sala == 1)
	     		$stroption.='selected';
	     	$stroption.='>'. utf8_encode('Suíte'). '</option>';
	     	
	     	$stroption.='<option value= "2"';
	     	if ($agenda_sala == 2)
	     		$stroption.='selected';
	     	$stroption.='>'. utf8_encode('Sala Estética'). '</option>';
	     	
	     	$stroption.='<option value= "3"';
	     	if ($agenda_sala == 3)
	     		$stroption.='selected';
	     	$stroption.='>'. utf8_encode('Fotodepilação'). '</option>';
	   	
	     	$stroption.='<option value= "4"';
	     	if ($agenda_sala == 4)
	     		$stroption.='selected';
	     	$stroption.='>Sala Facial</option>';
	     	
	     	$stroption.='<option value= "5"';
	     	if ($agenda_sala == 5)
	     		$stroption.='selected';
	     	$stroption.='>Nutricionista</option>';
	     	 
	     	
	     	$stroption.= '</select>';
	     	echo $stroption;
	     	
	     	 	
	        // Campo das profissionais
	        $stroption='Profissional que vai realizar atendimento:';
			    
			$sql = "Select * FROM profissional WHERE prof_seq > 0 ORDER BY prof_name ";
			$result = $conn->query($sql);
			$stroption.=' <select class="frmclient" name="prof_seq" id="prof_seq" required >';
			
			$stroption.='<option value="">';
			while ($linha = $result->fetch_assoc())
			   {
			    $stroption.='<option value="'. $linha["prof_seq"]. '"';
			    if ($linha["prof_seq"] == $agenda_prof_seq)
			    $stroption.='selected';
			    
			    $stroption.='>'. $linha["prof_name"]. '</option>';
			    	
			    }
			    $stroption.='</select>';
			 echo $stroption;
		
					
			$strfield= 'Data: <input class="frmclient" type="date" name="agenda_date" id="agenda_date" size="30" required ';
			$strfield.= 'value="'.$agenda_date.'">';
			echo $strfield;
		
			$strfield= 'Hora Inicial: <input class="frmclient" required min="07:00" max="20:00" type="time" name="agenda_start_time" id="agenda_start_time" size="30"';
			$strfield.= 'value="'.$agenda_start_time.'">';
			echo $strfield;
			
			
			$strfield= 'Hora Final: <input class="frmclient" type="time" required min="09:00" max="20:00" name="agenda_end_time" id="agenda_end_time" size="30"';
			$strfield.= 'value="'.$agenda_end_time.'">';
			echo $strfield;
			
		       
			// Campo select com opção o status do agendamento
			$stroption='Status do Agendamento:';
			$stroption.= '<select class="frmclient" name="agenda_concluded" id="agenda_concluded" onchange="resetacomments()" >';
			   
			$stroption.='<option value= "0"';
			if ($agenda_concluded == "0")
				$stroption.='selected';
			$stroption.='>Programado</option>';
			   
			//$stroption.='<option value= "1"';
			//if ($agenda_concluded == "1")
			// 	$stroption.='selected';
			//$stroption.='>Faltou </option>';
			   
			$stroption.='<option value= "2"';
			if ($agenda_concluded == "2")
			   $stroption.='selected';
			$stroption.='>Cancelado</option>';
			   
			$stroption.='<option value= "3"';
			if ($agenda_concluded == "3")
			   	$stroption.='selected';
			$stroption.='>'.utf8_encode('Concluído').'</option>';
			
			$stroption.='<option value= "4"';
			if ($agenda_concluded == "4")
				$stroption.='selected';
			$stroption.='>Confirmado</option>';
				
			   	
			$stroption.= '</select>';
			echo $stroption;
			
			

			// Campo das profissionais que mudaram
			$stroption='Profissional que Aterou Status:';
			 
			$sql = "Select * FROM profissional WHERE prof_seq > 0 ORDER BY prof_name ";
			$result = $conn->query($sql);
			$stroption.=' <select class="frmclient" name="agenda_prof_log" id="agenda_prof_log" required>';
				
			$stroption.='<option value="">';
			while ($linha = $result->fetch_assoc())
			{
				$stroption.='<option value="'. $linha["prof_seq"]. '"';
				if ($linha["prof_seq"] == $agenda_prof_log)
					$stroption.='selected';
				 
				$stroption.='>'. $linha["prof_name"]. '</option>';
			
			}
			$stroption.='</select>';
			echo $stroption;
			
			   	
			$strfield= utf8_encode('Comentários:'). '<input type="text" class="frmclient" required name="agenda_log_comments" id="agenda_log_comments" size="40"';
			$strfield.= 'value="'.$agenda_log_comments .'">';
			echo $strfield;
				

			// Campo select com opção do tipo do pagamento
			$stroption='Tipo de Pagamento:';
			$stroption.= '<select class="frmclient" name="agenda_type_pagto" id="agenda_type_pagto" required >';
			
			$stroption.='<option value=""';
			$stroption.='></option>';
				
			
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
			$stroption.='>'.utf8_encode('Crédito').'</option>';
			
			$stroption.='<option value= "3"';
			if ($agenda_type_pagto == "3")
			   	$stroption.='selected';
			$stroption.='>'.utf8_encode('Débito').'</option>';
						
			$stroption.='<option value= "4"';
			if ($agenda_type_pagto == "4")
			   $stroption.='selected';
			$stroption.='>Cheque</option>';
			   
			$stroption.= '</select>';
			echo $stroption;
			

			echo '<div id="tratamento">';
				
			//Se for definido o cliente, mostra o campo dos pacotes do cliente atual
			if ($agenda_type == 2)
			{
				$sql= "SELECT secl_seq, secl_desc,date_format(secl_data_venda,'%d/%m/%Y') as secl_data_venda2 FROM pacote_servico_cliente Where secl_client_seq=". $agenda_client_seq;
				$result = $conn->query($sql);
			
					
				if ($result->num_rows > 0)
				{
					echo 'Pacote:<select name="secl_seq" class="frmclient" id="secl_seq" required>';
					// Atribui o código HTML para montar uma tabela
					while ($linha = $result->fetch_assoc())
					{
						if ($agenda_secl_seq == $linha['secl_seq'])
							echo '<option value="'. $linha["secl_seq"]. '"selected >'. $linha["secl_desc"]. $linha["secl_data_venda2"]. '</option>';
						else
							echo '<option value="'. $linha["secl_seq"]. '">'. $linha["secl_desc"]. $linha["secl_data_venda2"]. '</option>';
					}
					//fecha o select
					echo "</select>";
				}
			
			}
			/*
			if ($agenda_type == 1 || $agenda_type == 3 || $agenda_type == 5)
			{
				$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=0";
				$result = $conn->query($sql);
				$vreturn='Tratamento: '. utf8_encode('(Pressionar CTR para múltiplos)').'<select multiple  onchange="somatratamento()"  name="tyse_seq[]" id="tyse_seq"  class="frmclient"  size="10" required >';
			
				// Coloca os tratamentos na variavél return
				while ($linha = $result->fetch_assoc())
				{
					$vreturn.= '<option class="frmnegrito" value="'. $linha["tyse_seq"]. '">'. $linha["tyse_desc"]. '</option>';
					//Faz a busca em nichos
			
					$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=". $linha["tyse_seq"] . " Order by tyse_desc ASC";
					$result2 = $conn->query($sql);
					$vreturn.= $linha["tyse_desc"];
					// Atribui o código HTML para montar uma tabela
					while ($linha2 = $result2->fetch_assoc())
					{
							
						$vtyse_desc=$linha2["tyse_desc"];
						$vreturn.= '<option value="'. $linha2["tyse_seq"].'" ';
							
						// Verifica se existe no array
						if (in_array($linha2['tyse_seq'], $aget_tyse_seq))
							$vreturn.= 'selected';
							
						$vreturn.= ' >'. $vtyse_desc . '</option>';
					}
			
				}
			
				$vreturn.='</select>';
				echo $vreturn;
			
			}
			*/
			//original em cima ^
			if ($agenda_type == 1 || $agenda_type == 3 || $agenda_type == 5)
			{
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
				//pra poder colocar o input, se não o js no onClick() não funciona
				?>
				<INPUT type="button" value="Add Campo" name="newtratamento" onClick="addInputtyse('dynamicInput')" class="frmsubmit">
				<INPUT type="button" value="Apaga Campo" name="delpeci" onClick="DeleteFiels('dynamicInput')" class="frmsubmit">

				</div>
				<div id="dynamicInput">
				  <?php
				  				  	
				  		//	Seleciona os tipos de tratamentos do agendamento atual
				  		$sql = "Select tyse_desc,tyse_seq,aget_value  FROM type_service,agenda_service WHERE tyse_seq=aget_tyse_seq AND aget_agenda_seq=". $agenda_seq;
				  		
				  		$result = $conn->query($sql);
				  		if ($result->num_rows > 0)
				  		{	
				  			while ($linha = $result->fetch_assoc())
				  			{
								echo ' <input type="hidden"  name="tyse_seq[]"value="'. $linha["tyse_seq"]. '">';
						  		echo '<br><input type="text" name="tyse_desc[]" value="'. $linha["tyse_desc"]. '">';
						  		echo '<input type="text" size="5"  name="aget_value[]" value="' . $linha["aget_value"]. '">';
							}
				  		}
				  
				  
			     echo "</div>";

			}

			if ($agenda_type == 4 || $agenda_type == 6 )
			{
				$strfield= 'Motivo Agendamento: <input class="frmclient" type="text" name="agenda_outros" id="agenda_outros" size="30"';
				$strfield.= 'value="'.$agenda_outros.'">';
				echo $strfield;
			}
				
			echo '</div>';
				
			
			$strfield="<div id='valores_avulso'>";
			echo $strfield;
			//Somente se for avulso aparece valor
			if ($agenda_type == 1)
			{
				$strfield= 'Desconto:(%) <input  class="frmclientside" type="number" name="agenda_desconto" id="agenda_desconto" min="0" max="15" ' ;
				$strfield.= 'value="'.$agenda_desconto.'">';
				echo $strfield;

				$strfield= 'Valor Total: <input  class="frmclientside" type="text" name="agenda_valor" id="agenda_valor" size="10" ' ;
				$strfield.= 'value="'.$agenda_valor.'">';
				echo $strfield;
			
				$strfield= 'Parcelamento:(Credito ou Cheque) <input class="frmclientside" min="1" type="number" min="1" max="10" name="agenda_parcelas" id="agenda_parcelas" size="5"';
				$strfield.= 'value="'.$agenda_parcelas.'">';
				echo $strfield;
			
				$strfield= utf8_encode('Cheque:') . '<input class="frmclient" type="text" name="agenda_cheque" id="agenda_cheque" size="30"';
				$strfield.= 'value="'.$agenda_cheque .'">';
				echo $strfield;
			
			}
			
			else
			{ 
				// se o tipo não for avulso, cria o campo hidden
				
				$strfield= '<input type="hidden" name="agenda_valor" id="agenda_valor" value="0">';
				$strfield.= '<input type="hidden" name="agenda_parcelas" id="agenda_parcelas" value="0">';
				$strfield.= '<input type="hidden" name="agenda_cheque" id="agenda_cheque" value="0">';
				echo $strfield;
			}
				
			$strfield="</div>";				
			echo $strfield;
					
			
			   
			$strfield='<input type="hidden" id="agenda_seq" name="agenda_seq"'. 'value="'.$agenda_seq.'">';
			echo $strfield;
			
			// Verifica se foi agendamento vindo de ligação
			if (isset($_REQUEST["clica_seq"]))
				$strfield='<input type="hidden" id="clica_seq" name="clica_seq" value="'.$_REQUEST["clica_seq"] .'">';
			
			//exibe o campo submit
			$strfield.= '<input type="submit" name="bsubmit" class="frmsubmit" id="bsubmit" value ="' .$b_secl_action . '">';
    
			//exibe o campo submit
			$strfield.= '<input type="submit" name="b_delete" onclick="click_del();"  class="frmsubmit" id="b_delete" value ="Apagar">';
				
			
			echo $strfield . '</form> </div>';
			
					
			
			mysqli_close($conn); 
?>
</div>

