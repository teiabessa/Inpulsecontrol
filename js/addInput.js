var counter = 1;
var limit = 10;
function addInput(divName){
     if (counter == limit)  {
          alert("You have reached the limit of adding " + counter + " inputs");
     }
     else {
    	 // alert(document.getElementById(tyse_seq).selectedIndex);
    	  var e = document.getElementById("tyse_seq");
    	  var tyse_seq = document.getElementById("tyse_seq").value;
    	  var strselect = e.options[e.selectedIndex].text;
    	  var newdiv = document.createElement('div');
    	  // pacoteArray = strselect.split('-');
    	  var peqi_seq=" <input type='hidden'  name='peqi_tyse_seq[]' value='" + tyse_seq + "'>";
    	  var peqi_secl=" <input type='text' readonly name='peqi_desc[]' value='" + strselect + "'>";
    	   var qtd_sessoes="<input type='text' size='5' name='peqi_qtd_sessoes[]' value='10'>";
    	    newdiv.innerHTML = peqi_seq + peqi_secl  + qtd_sessoes ;
          document.getElementById(divName).appendChild(newdiv);
          counter++;
     }
}


function addTelefone(divName){
    if (counter == limit)  {
         alert("You have reached the limit of adding " + counter + " inputs");
    }
    else 
    {
    	
   	 // alert(document.getElementById(tyse_seq).selectedIndex);
   	  //var e = document.getElementById("client_seq");
   	  //var strselect = e.options[e.selectedIndex].text;
   	  var newdiv = document.createElement('div');
   	  var tecl_seq=" <input type='hidden' name='tecl_seq[]' value=''>";
   	  var tecl_desc="Telefone:<input type='text' name='tecl_desc[]' value=''>";
   	  var operadora="Operadora:<input type='text' size='5' name='tecl_operadora[]' value='OI'>";
   	  newdiv.innerHTML = tecl_seq + tecl_desc + operadora ;
      document.getElementById(divName).appendChild(newdiv);
         counter++;
    }
}
