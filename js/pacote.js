function confirm_del_agenda(valorurl)
{
	if (confirm('Você realmente quer apagar esse agendamento?' + valorurl)) {
        //alert(valorurl);
         location.href=valorurl;
         //return true;
          
    } else {
        return false;
    }   
	
}


function confirm_del_agenda0()
{
	if (confirm('Você realmente quer apagar esse agendamento?')) {
        
        location.href = 'index.php';
    } else {
        return false;
    }   
	
}



function click_del()
{
	if (confirm('Você realmente quer apagar esse agendamento?')) {
        yourformelement.submit();
    } else {
        return false;
    }   
	
}




function addPacote(divName){
	
	var client_seq=document.getElementById("client_seq").value;
		//variavel que vai ser usada nos divs dos tratamentos
	var newdiv = document.createElement('div');
	document.getElementById(divName).innerHTML= "";
	var tipo_agendamento=document.getElementById("agenda_type").value;
	// Se for Avulso ou Treinamento, chama a página que tras os tratamentos genéricos
	if ( tipo_agendamento == 1  || tipo_agendamento == 3 || tipo_agendamento == 5 || tipo_agendamento == 2)
	{
		var vurl="get_tyse_list.php";
	
		// Verifica se o agedamento é para pacote, para assim fazer a busca de acordo
		if ( tipo_agendamento == 2)
			vurl="get_secl_list.php?client_seq=" + client_seq;
		// Inicia o processo do ajax
		var xmlhttp = new XMLHttpRequest();
	
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
			{
				if(xmlhttp.responseText == 'Null')
					{
						document.getElementById("agenda_type").value=1;
						alert("Este Cliente não possui pacote associado!");
					}
				
				else
					{   
						newdiv.innerHTML = xmlhttp.responseText;
						document.getElementById(divName).appendChild(newdiv);
					}
					
				//alert(xmlhttp.responseText);
			}
		}
		xmlhttp.open("GET", vurl  , true);
		xmlhttp.send();
	}
	else
	{
	// Se o tipo do agendamento for treinamento ou outros, só coloca um novo campo texto( == 4 tipo_agendamento == 6)
		newdiv.innerHTML = "Motivo do Agendamento: <input type='text' class='frmclient' name='agenda_outros' value=''>";
        document.getElementById(divName).appendChild(newdiv);
	
	
    }

}


function modifyclient() 
{  
	v_client_seq=document.getElementById('client_seq').value;
	
	var xmlhttp = new XMLHttpRequest();
	
	//reseta formulario com o pacote
	var myNode = document.getElementById("dynpacotes");
	while (myNode.firstChild) {
		   myNode.removeChild(myNode.firstChild);
	}
	//reseta o form dos peci
	var myNode = document.getElementById("dynamicInput");
	while (myNode.firstChild) {
		   myNode.removeChild(myNode.firstChild);
	}
	
	
	 xmlhttp.onreadystatechange = function()
	 {
		 if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		 {
			 strclients = xmlhttp.responseText;
			 // Verifica se foi encontrado algum registro
		
			 if(strclients.trim() != "Null")
			 {
				 var newdiv = document.createElement('div');
				 
				 newdiv.innerHTML = strclients;
				 document.getElementById("dynpacotes").appendChild(newdiv);
				 
				

			 	}
		 	}
	 }
	xmlhttp.open("GET", "act_list_secl.php?client_seq=" + v_client_seq, true);
	xmlhttp.send();

}


function modifyall() 
{

	v_secl_seq=document.getElementById('secl_seq').value;
	 var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
				{   strclients = xmlhttp.responseText;
				
				// Verifica se foi encontrado algum registro
					if(strclients.trim() != "Null")
					{
						clientsArray = strclients.split('|');
						// retornará o nome do cliente
						document.getElementById("prof_seq").value = clientsArray[0];
						document.getElementById("secl_valor_vendido").value = clientsArray[1];
						document.getElementById("secl_qtd_sessao_realizada").value = clientsArray[2];
						document.getElementById("secl_contrato_seq").value = clientsArray[3];
						document.getElementById("secl_data_agendamento").value = clientsArray[4];
						document.getElementById("agenda_start_time").value = clientsArray[5];
						document.getElementById("agenda_end_time").value = clientsArray[6];
						document.getElementById("secl_agenda_type_pagto").value = clientsArray[7];
						document.getElementById("secl_agenda_cheque").value = clientsArray[8];
						document.getElementById("secl_agenda_sala").value = clientsArray[9];
						var str_days= "a"+clientsArray[10];
						document.getElementById("secl_days_weak").value ='0';
						
						/*
						for( i = 0; i <=5; i ++)
					    {
							//alert (str_days.indexOf(i));
							
					      if(str_days.indexOf(i)) 
					        {
					        document.getElementById("secl_days_weak").selectedIndex = i;
					        //	document.getElementById("secl_days_weak").[i].selected =true;
					        	
					        }
					        
					    }
					    */
						
					  var myNode = document.getElementById("dynamicInput");
						while (myNode.firstChild) {
							   myNode.removeChild(myNode.firstChild);
						}
						
						var recordcount = clientsArray[11];
						if(recordcount > 0)
						{
							// Não altere esse valor mesmo se for incluir novo campo
							var arraytel=12;
							var newdiv = document.createElement('div');
							var peqi_tyse_seq="";
							var peqi_secl_seq="";
							var peqi_qtd_sessoes="";
							var peqi_desc="";
							strfields="";
							
							for(i=0; i<recordcount; i++)
							{  
								  peqi_tyse_seq=" <br><input type='hidden' name='peqi_tyse_seq[]'  value='" + clientsArray[arraytel] + "'>";
							   	  arraytel++;
							   	  peqi_desc ="<input type='text' name='peqi_desc[]' value='" + clientsArray[arraytel] + "'>";
							   	  arraytel++;
							   	  peqi_qtd_sessoes="<input type='text' size='5' name='peqi_qtd_sessoes[]' value='" + clientsArray[arraytel] + "'>";
							   	  arraytel++;
							   	 strfields= strfields + peqi_tyse_seq + peqi_desc + peqi_qtd_sessoes;
							   	 
							}
							   	newdiv.innerHTML =strfields ;
							   
							   	
							   	document.getElementById("dynamicInput").appendChild(newdiv);
							   		
								
						}
						
					// else
				//		{
					//	document.getElementById("dynamicInput").value = "";
						//document.getElementById("peqi_qtd_sessoes").value = "";
						
				//		}
							
			
				    	   	
					}
				}
			}
			xmlhttp.open("GET", "act_secl.php?secl_seq=" + v_secl_seq, true);
			xmlhttp.send();

}

function DeleteFiels(divname)
{
	var myNode = document.getElementById(divname);
	while (myNode.firstChild) {
		   myNode.removeChild(myNode.firstChild);
	}
	
}


function changestatushist()
{
	var client_seq=document.getElementById("clica_client_seq").value;
	var clica_seq=document.getElementById("clica_seq").value;
	var tipo_status=document.getElementById("clica_stali_seq").value;
	// Se for Avulso ou Treinamento, chama a página que tras os tratamentos genéricos
	if ( tipo_status == 11 )
	{
		vurl="act_change_hist11.php?client_seq=" + client_seq+ "&clica_seq=" + clica_seq;
		// Inicia o processo do ajax
		var xmlhttp = new XMLHttpRequest();
	
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
			{
				if(xmlhttp.responseText == 'Null')
					{
						alert("Houve um erro ao enviar o E-mail!");
					}
				
				else
					{   
					alert("O E-mail foi enviado com sucesso!");
					}
					
				//alert(xmlhttp.responseText);
			}
		}
		xmlhttp.open("GET", vurl  , true);
		xmlhttp.send();
	}
		
}