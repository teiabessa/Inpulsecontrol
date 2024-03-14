<?php
//Eu faço a busca no banco de dados de todos os registros de clientes


	/* Invoca o arquivo que faz conexão com o db */
	//require_once 'db.php';
	//Sentença de busca no banco
	$sql = "Select * FROM client where client_seq > 0 ORDER BY client_name"; 
	$result = $conn->query($sql);
	
	
	
	$submit_action="Insert";
	if (isset($_REQUEST['client_seq']))
	{
		$submit_action="Update";
		$sql = "Select * FROM client where client_seq=". $_REQUEST['client_seq'];
		$result = $conn->query($sql);
	
		while ($linha = $result->fetch_assoc())
		{
			$client_name=$linha["client_name"];
			$client_seq=$linha["client_seq"];
			$client_cpf=$linha["client_cpf"];
			$client_rg=$linha["client_rg"];
			$client_address=$linha["client_address"];
			$client_bairro=$linha["client_bairro"];
			$client_cep=$linha["client_cep"];
			$client_email=$linha["client_email"];
			$client_activate=$linha["client_activate"];
			$client_data_nascimento=$linha["client_data_nascimento"];
			$client_data_ultimo_atend=$linha["client_data_ultimo_atend"];
	
		}
	
	}
	else
	{
		$client_name="";
		$client_seq="0";
		$client_cpf="";
		$client_rg="";
		$client_address="";
		$client_bairro="";
		$client_cep="";
		$client_email="";
		$client_activate="";
		$client_data_nascimento="";
		$client_data_ultimo_atend="";
	
	}
	
?>

<div id="agenda_clientes">
<b> Dados do Cliente:</b>

<form action="action_page.php" method="get"> 

<input list="browsers" name="browser" id="client_name" size="50">
<datalist id="browsers" >
<?php
if ($result->num_rows > 0)
{
	// Atribui o código HTML para montar uma tabela
	while ($linha = $result->fetch_assoc())
		echo '<option value="'. $linha["client_name"]. '">';

}
?>

	</datalist>
	<INPUT type="button" value="Pesquisar" name="searchclient" onClick="seleciona_cliente();">

</form> 


<div id='frmclient4'>
	<FORM ACTION="act_ins_clients.php" METHOD="POST" id='formclient' name='formclient'>
	<?php

	
		$strfield= 'Visitou a In Pulse em: <span id="client_data_ultimo_atend">'; 
		$strfield.= $client_data_ultimo_atend . '</span>';
		echo $strfield;
		
		$strfield= '<br>Nome: <input class="frmclient" type="text" name="client_name" id="client_name" size="30" required';
		$strfield.= ' value="'.$client_name.'">';
		echo $strfield;
		
		$strfield= 'Email: <input type="text" name="client_email" class="frmclient" id="client_email" size="30"';
		$strfield.= ' value="'.$client_email.'">';
		echo $strfield;
		
		
		$strfield= utf8_encode("Endereço:"). '<input class="frmclient" type="text" name="client_address" id="client_address" size="40"';
		$strfield.= ' value="'.$client_address.'">';
		echo $strfield;
		
		$strfield= 'Bairro: <input class="frmclient" type="text" name="client_bairro" id="client_bairro" size="20"';
		$strfield.= ' value="'.$client_bairro.'">';
		echo $strfield;
		
		
		$strfield= 'Cep: <input class="frmclient" type="text" name="client_cep" id="client_cep" size="20"';
		$strfield.= ' value="'.$client_cep.'">';
		echo $strfield;

		
		$strfield= '<br>CPF: <input class="frmclient" type="text" name="client_cpf" id="client_cpf" size="20"';
		$strfield.= ' value="'.$client_cpf.'">';
		echo $strfield;
		
		$strfield= 'RG: <input class="frmclient" type="text" name="client_rg" id="client_rg" size="20"';
		$strfield.= 'value="'.$client_rg.'">';
		echo $strfield;
		
		$strfield= 'Data de Nascimento: <input  class="frmclient"   type="date" name="client_data_nascimento" id="client_data_nascimento" size="7"';
		$strfield.= ' value="' .$client_data_nascimento .'">';
		echo $strfield;
		
		$strfield= 'Cliente Ativo: <input  type="checkbox"  name="client_activate" name="client_activate" id="client_activate" ';
		if($client_activate == 1)
			$strfield.= 'checked';
		$strfield.='>';
		echo $strfield;
		
		$strfield= '<input type="hidden" id="client_seq" name="client_seq"';
		$strfield.= ' value="'.$client_seq .'">';
		echo $strfield;
		
		echo '<div id="dynamicInput">';
		
		$sql = "Select * FROM telefone_clients where tecl_client_seq=". $client_seq ;
		$result = $conn->query($sql);
		
		if ($result->num_rows == 0)
		{
				$strfield= '<br><input type="hidden" class="frmclient" id="tecl_seq" name="tecl_seq[]" VALUE="">';
	    		$strfield.= 'Telefone:<input type="text"  name="tecl_desc[]" id="tecl_desc" VALUE="">';
				$strfield.= 'Operadora:<input  type="text" size= "8" name="tecl_operadora[]" id="tecl_operadora" required VALUE="">';
	    		echo $strfield;
		
		}
		else 
		{
			while ($linha = $result->fetch_assoc())
			{
			
				$strfield= '<br><input type="hidden" class="frmclient" id="tecl_seq" name="tecl_seq[]" VALUE="'.  $linha[tecl_seq] . '">';
	    		$strfield.= 'Telefone:<input type="text"  name="tecl_desc[]" id="tecl_desc" VALUE="'. $linha[tecl_desc]. '">';
				$strfield.= 'Operadora:<input   type="text" size= "8" name="tecl_operadora[]" id="tecl_operadora"  required VALUE="'. $linha[tecl_operadora]. '">';
	    		echo $strfield;
			}
		}
   
	   ?>
	   
	   </div> <INPUT type="button" value="ADD Telefone" name="btelefone"  id="btelefone" onClick="addTelefone('dynamicInput')" class="frmsubmit">
	   <?php
	   echo '<input class="frmsubmit" type="submit" name="bsubmit" id="bsubmit" value="'. $submit_action. '">';
	   
	   $strfield=	'<INPUT type="button" value="Envia E-Marke" name="send_info" onClick="sendinfo()" class="frmsubmit"  size="40">';
	   echo $strfield;
	   
	   
	   
	   echo '</form></div> ';
 
   	//mysqli_close($conn);
   	
   	echo '</div> ';
	
