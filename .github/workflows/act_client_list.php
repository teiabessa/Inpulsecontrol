<html>
<link href="css/control.css" rel="stylesheet" type="text/css" />
<head>
  

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
		document.getElementById("client_data_ultimo_atend").innerHTML= "";
		document.getElementById("client_name").value = v_client_name;
		
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
						document.getElementById("client_telefone_fixo").value = clientsArray[6];
						document.getElementById("client_telefone_cel1").value = clientsArray[7];
						document.getElementById("client_data_nascimento").value = clientsArray[9];
						document.getElementById("client_data_ultimo_atend").innerHTML= clientsArray[10];
						document.getElementById("client_address").value = clientsArray[11];
						
						
						if(clientsArray[8].trim()==1)
							document.getElementById("client_activate").checked = true;
						else
							document.getElementById("client_activate").checked = false;
							 
					}
			
				}
			}
			xmlhttp.open("GET", "get_clients.php?client_name=" + v_client_name, true);
			xmlhttp.send();
    		

    	  }
    	
    	break;
    	return;
	   
    	
	}
	document.getElementById('list_client_name').value="";
}
  
</script>
</head>


  
<?php
//Eu faço a busca no banco de dados de todos os registros de clientes


	/* Invoca o arquivo que faz conexão com o db */
	require_once 'db.php';
	//Sentença de busca no banco
	$sql = "Select * FROM client where client_seq > 0 ORDER BY client_name"; 
	$result = $conn->query($sql);
?>
<body>

<div id='divformsearch'>
<form action="action_page.php" method="get">

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
	<INPUT type="button" value="Novo Cliente" name="newclient" onClick="showdivform(action='new')">
	<INPUT type="button" value="Alterar Cliente" name="alterclient" onClick="showdivform(action='modify')">
	<INPUT type="button" value="Apagar Cliente" name="delclient" onClick="showdivform(action='delete')">
	<INPUT type="button" value="Pesquisar" name="searchlient" onClick="showdivform(action='search')">

</form>
</div>


<body>



<div id='frmclient'>
<p><b>Informações do Cliente:</b></p>
	<FORM ACTION="act_ins_clients.php" METHOD="POST" id='formclient' name='formclient'>
		Última Visita: <span id="client_data_ultimo_atend"> </span>
		Name: <input class="frmclient" type="text" name="client_name" id="client_name" size="30" required>
		Endereço: <input class="frmclient" type="text" name="client_address" id="client_address" size="40">
		Email: <input class="frmclient" type="text" name="client_email" id="client_email" size="20">
		CPF: <input class="frmclient" type="text" name="client_cpf" id="client_cpf" size="20">
		RG: <input class="frmclient" type="text" name="client_rg" id="client_rg" size="20">
		Telefone Fixo: <input class="frmclient" type="text" name="client_telefone_fixo"  id="client_telefone_fixo" size="15">
		Telefone Celular: <input class="frmclient" type="text" name="client_telefone_cel1" id="client_telefone_cel1" size="15">
		Data de Nascimento: <input  class="frmclient"   type="date" name="client_data_nascimento" id="client_data_nascimento" size="7">
		Cliente Ativo: <input  type="checkbox"  name="client_activate" name="client_activate" id="client_activate" value="1">
		<input type="hidden" id="client_seq" name="client_seq" >
		<input type="submit" name="bsubmit" id="bsubmit">
		
	</form>
</div>
</body>

<?php 


mysqli_close($conn); 
?>
</html>


