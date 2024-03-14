<?php
//Eu invoco o hearder
//Mês Aniversário:<input type="month" name="mes_aniversario" id="mes_aniversario">
//Cliente Ativo: <input  type="checkbox"  name="search_activate" name="search_activate" id="search_activate" value="1">


	/* Invoca o arquivo que faz conexão com o db */
	require_once 'dsp_header.php';
	
?>


<script>
 
function showdivform(action) 
{

	v_client_name=document.getElementById('list_client_name').value;
	switch (action) {

	   case "new":
    	
    	for(i=0; i<document.formclient.elements.length; i++)
			document.formclient.elements[i].value="";
    	//Altera o texto de botão Submit de acordo com a ação
		document.getElementById("bsubmit").value = "Insere";
		document.getElementById("btelefone").value = "Add Telefone";
		document.getElementById("client_data_ultimo_atend").innerHTML= "";
		document.getElementById("client_name").value = v_client_name;
		
	   break;
	   return;

	   case "delete":
		   var v_client_seq=document.getElementById("client_seq").value;
		   if (v_client_seq == "")
		 		alert("Você precisa definir um cliente para eliminá-lo!");
			
	   		else
		   	{
		   		alert(v_client_seq);
		   		var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() 
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					{
						alert(xmlhttp.responseText);
					  	//apaga todos os valores dos formularios
	    				for(i=0; i<document.formclient.elements.length; i++)
						document.formclient.elements[i].value="";
	    				//Altera o texto de botão Submit de acordo com a ação
						document.getElementById("bsubmit").value = "Insere";
						document.getElementById("btelefone").value = "Add Telefone";

					}
				}
				xmlhttp.open("GET", "act_del_clients.php?client_seq=" + v_client_seq, true);
				xmlhttp.send();
		   	}
		   break;
		   return;
	   

   case "modify":
	    	
    	document.getElementById("bsubmit").value = "Update";
    	if (v_client_name.length > 0) 
			{
    		var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
				{
					strclients = xmlhttp.responseText;
					// Verifica se foi encontrado algum registro
					if(strclients.trim() != "Null")
					{
						clientsArray = strclients.split('|');
						// retornará o nome do cliente
						document.getElementById("client_name").value = clientsArray[0]; 
						// retornará o seq do cliente
						document.getElementById("client_seq").value = clientsArray[1];
						document.getElementById("client_email").value = clientsArray[2];
						document.getElementById("client_cpf").value = clientsArray[3];
						document.getElementById("client_rg").value = clientsArray[4];
						document.getElementById("client_email").value = clientsArray[5];
						if(clientsArray[6].trim()==1)
							document.getElementById("client_activate").checked = true;
						else
							document.getElementById("client_activate").checked = false;
                        
						document.getElementById("client_data_nascimento").value = clientsArray[7];
						document.getElementById("client_data_ultimo_atend").innerHTML= clientsArray[8];
						document.getElementById("client_cep").value = clientsArray[9];
						document.getElementById("client_bairro").value = clientsArray[10];
						document.getElementById("client_address").value = clientsArray[11];
						// variável com recordcount
						var record_telefone=clientsArray[12];

					    var newdiv = document.createElement('div');
						var arraytel=13;
						var tecl_seq="";
						var tecl_desc="";
						var operadora="";
						strfields="";
						//Resseta todo o div dos telefones
						var myNode = document.getElementById("dynamicInput");
						var divdynamic="dynamicInput";
						while (myNode.firstChild) {
							   myNode.removeChild(myNode.firstChild);
						}
							
						if(record_telefone > 0)
						{
							for(i=0; i<record_telefone; i++)
							{
						   	  tecl_seq=" <br><input type='hidden' name='tecl_seq[]'  value='" + clientsArray[arraytel] + "'>";
						   	  arraytel++;
						   	  tecl_desc="Telefone:<input type='text' name='tecl_desc[]' value='" + clientsArray[arraytel] + "'>";
						   	  arraytel++;
						   	  operadora="Operadora:<input type='text' size='5' name='tecl_operadora[]' value='" + clientsArray[arraytel] + "'>";
						   	  arraytel++;
						   	  strfields= strfields + tecl_seq + tecl_desc + operadora;
							}
							newdiv.innerHTML =strfields ;
						   	document.getElementById("dynamicInput").appendChild(newdiv);
						   	
						 
						}
						else
						{
						document.getElementById("tecl_seq").value = "";
						document.getElementById("tecl_desc").value = "";
						document.getElementById("tecl_operadora").value = "";
						}	
					}
			
				}
			}
			xmlhttp.open("GET", "get_clients.php?client_name=" + v_client_name, true);
			xmlhttp.send();
    		

    	  }
    	
    	break;
    	return;
	   
    	
	

	case "search":
	
	//Altera o texto de botão Submit de acordo com a ação
	alert(document.getElementById("mes_aniversario").value);
	
   break;
   return;
	
	
	
	document.getElementById('list_client_name').value="";
	}
}
  
</script>
</head>


  
<?php
//Eu faço a busca no banco de dados de todos os registros de clientes


	/* Invoca o arquivo que faz conexão com o db */
	require_once 'db.php';
	
	echo "<div id='divallfrmclient'>";
	$client_seq=$_REQUEST['client_seq'];
	/* Invoca o arquivo que faz conexão com o db */
	require_once 'dsp_clients_fotos.php';

	//Sentença de busca no banco
	$sql = "Select * FROM client where client_seq >0 ORDER BY client_name";
	$result = $conn->query($sql);
	
			
?>
<div id='divsearch'>
<span id="divtitlesearch"><?php echo utf8_encode("Entre com as informações do Cliente:");?></span>
<form action="action_page.php" method="get" name="search_client">

	<div class='divfrmfield'>
		<input list="browsers" name="browser" id="list_client_name" size="50">
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
	</div>
	<div class='divfrmbuttons'>
		<INPUT type="button" value="Novo Cliente" name="newclient" onClick="showdivform(action='new')" class="frmsubmit">
		<INPUT type="button" value="Alterar Cliente" name="alterclient" onClick="showdivform(action='modify')" class="frmsubmit">
		<INPUT type="button" value="Apagar Cliente" name="delclient" onClick="showdivform(action='delete')" class="frmsubmit">
	</div>
  </form>
</div>

	
   

<body>

<?php
/* Invoca o arquivo que faz conexão com o db */
require_once 'dsp_clients_fotos.php';


$submit_action="Insert";
if (isset($_REQUEST['client_seq']))
{
	$submit_action="Update";
	$sql = "Select * FROM client where client_seq=". $_REQUEST['client_seq'];
	$result = $conn->query($sql);
	
	while ($linha = $result->fetch_assoc())
	{
		$client_name=$linha["client_name"];
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
	$client_seq="";
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


echo '<div id="frmclient"><FORM ACTION="act_ins_clients.php" METHOD="POST" id="formclient" name="formclient">';
		$strfield= 'Visitou a In Pulse em: <span id="client_data_ultimo_atend">'; 
		$strfield.= $client_data_ultimo_atend . '</span>';
		echo $strfield;
		
		$strfield= '<br>Name: <input class="frmclient" type="text" name="client_name" id="client_name" size="30" required';
		$strfield.= ' value="'.$client_name.'">';
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

		$strfield= 'Email: <input class="frmclient" type="text" name="client_email" id="client_email" size="30"';
		$strfield.= ' value="'.$client_email.'">';
		echo $strfield;
		
		$strfield= 'CPF: <input class="frmclient" type="text" name="client_cpf" id="client_cpf" size="20"';
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
	   echo '</form></div> </body>';
 
   	mysqli_close($conn);
   	
   	echo '</div> ';
   	
   	
?>
</html>

