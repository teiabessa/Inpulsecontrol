<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


//Eu faço a busca no banco de dados de todos os registros de clientes

/* Invoca o arquivo que faz conexão com o db */
require_once 'dsp_header.php';

/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';


if (isset($_REQUEST['crm_seq']))
	{
		$crm_seq=$_REQUEST['crm_seq'];
		$sql = "SELECT  crm_client_name, crm_client_seq,crm_contato, crm_historico, crm_acao_futura, crm_status_conversa, crm_situacao_cliente, crm_prof_seq, crm_indicacao FROM CRM WHERE crm_seq=".$crm_seq;
	    
		$result = $conn->query($sql);
		
		if ($result->num_rows)
		{
			// Atribui o código HTML para montar uma tabela
			while ($linha = $result->fetch_assoc())
			{
			//Inicia o valor das variáveis do crm
				
			$crm_client_name=$linha["crm_client_name"];
			$crm_contato=$linha["crm_contato"];
			$crm_historico=$linha["crm_historico"];
			$crm_acao_futura=$linha["crm_acao_futura"];
			$crm_status_conversa=$linha["crm_status_conversa"];
			$crm_situacao_cliente=$linha["crm_situacao_cliente"];
			$crm_prof_seq=$linha["crm_prof_seq"];
			$crm_indicacao=$linha["crm_indicacao"];	
			$crm_client_seq=$linha["crm_client_seq"];	

			$b_secl_action='Update';
		    
			}
			
		}
      
	}
else
	{ 
		
	//Inicia o valor das variáveis do crm
	$crm_seq="";
	$crm_client_name="";
	$crm_contato="";
	$crm_historico="";
	$crm_acao_futura="";
	$crm_status_conversa="1";
	$crm_situacao_cliente="1";
	$crm_prof_seq=1;
	$crm_indicacao="";
	$b_secl_action='Inserir';
	$crm_client_seq="";

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
    // estava dando erro
  //  require_once 'uploadfile.php';
    
    
	
echo '<div id="frmagenda"><b>Dados do CRM:</b><br>';


			 ?>
	<FORM ACTION="act_ins_crm.php" METHOD="POST" id='formcrm' name='formcrm'>
	     <?php
	     require_once 'dsp_client_browser_crm.php';
	     //$stroption='<br> Cliente: <select class="frmclient" name="client_seq" id="client_seq" >';
	    
	     
	     $strfield='<input type="hidden" id="crm_seq" name="crm_client_seq"'. 'value="'.$crm_client_seq.'">';
	     echo $strfield;
	     
	     
	     //Nome cliente
	     $strfield= '<div id="dcrm_client_name">Nome do Cliente <input type="text" class="frmclient" required name="crm_client_name" id="crm_client_name" size="200"';
	     $strfield.= 'value="'.$crm_client_name .'"></div>';
	     echo $strfield;
	     	
	     //contato cliente
	     $strfield= 'Contato <input type="text" class="frmclient" required name="crm_contato" id="crm_contato" size="45"';
	     $strfield.= 'value="'.$crm_contato .'">';
	     echo $strfield;
	     
	    
	     $strfield=utf8_encode("Histórico"). '<br><textarea class="crm" name="crm_historico">';
	     $strfield.= $crm_historico .'</textarea>';
	     echo $strfield;
	     
	     //crm acao futura
	     $strfield= '<br>'. utf8_encode("Ação Futura"). '<input type="text" class="frmclient" required name="crm_acao_futura" id="crm_acao_futura" size="400"';
	     $strfield.= 'value="'.$crm_acao_futura .'">';
	     echo $strfield;
	      
	     //crm indicacao
	     $strfield= 'Indicacao <input type="text" class="frmclient" required name="crm_indicacao" id="crm_indicacao" size="100"';
	     $strfield.= 'value="'.$crm_indicacao .'">';
	     echo $strfield;
	     
	   	// CStatus da Conversa
	     	//Iniciando Contato=1/Entrar em contato Furamente=2/Desistiu=3/Cliente Agendado=4
	     	$stroption='Status da Conversa:';
	     	 
	     	$sql = "Select * FROM status_crm WHERE scrm_seq > 0 ORDER BY scrm_seq ";
	     	$result = $conn->query($sql);
	     	$stroption.=' <select class="frmclient" name="crm_status_conversa" id="crm_status_conversa" required >';
	     		
	     	$stroption.='<option value="">';
	     	while ($linha = $result->fetch_assoc())
	     	{
	     		$stroption.='<option value="'. $linha["scrm_seq"]. '"';
	     		if ($linha["scrm_seq"] == $crm_status_conversa)
	     			$stroption.='selected';
	     		 
	     		$stroption.='>'. $linha["scrm_desc"]. '</option>';
	     	
	     	}
	     	$stroption.='</select>';
	     	echo $stroption;
	     	
	     	
	     	 
	     	
	     	// Campo das profissionais
	        $stroption='Profissional:';
			    
			$sql = "Select * FROM profissional WHERE prof_seq > 0 ORDER BY prof_name ";
			$result = $conn->query($sql);
			$stroption.=' <select class="frmclient" name="crm_prof_seq" id="crm_prof_seq" required >';
			
			$stroption.='<option value="">';
			while ($linha = $result->fetch_assoc())
			   {
			    $stroption.='<option value="'. $linha["prof_seq"]. '"';
			    if ($linha["prof_seq"] == $crm_prof_seq)
			    $stroption.='selected';
			    
			    $stroption.='>'. $linha["prof_name"]. '</option>';
			    	
			    }
			    $stroption.='</select>';
			 echo $stroption;
		
			 
			// Campo select com opção o status do agendamento
			$stroption='Situacao do cliente:';
			$stroption.= '<select class="frmclient" name="crm_situacao_cliente" id="crm_situacao_cliente" >';
			   
			$stroption.='<option value= "1"';
			if ($crm_situacao_cliente == "1")
				$stroption.='selected';
			$stroption.='>Pre Cliente</option>';
			   
			$stroption.='<option value= "2"';
			if ($crm_situacao_cliente == "2")
				$stroption.='selected';
			$stroption.='>Cliente a ser Resgatado</option>';
				
			$stroption.='<option value= "3"';
			if ($crm_situacao_cliente == "3")
				$stroption.='selected';
			$stroption.='>Cliente Fiel</option>';
			   	
			$stroption.= '</select>';
			echo $stroption;
			
			

			
			   	
			$strfield='<input type="hidden" id="crm_seq" name="crm_seq"'. 'value="'.$crm_seq.'">';
			echo $strfield;
			
					//exibe o campo submit
			$strfield.= '<input type="submit" name="bsubmit" class="frmsubmit" id="bsubmit" value ="' .$b_secl_action . '">';
    
			//exibe o campo submit
			$strfield.= '<input type="submit" name="b_delete" onclick="click_del();"  class="frmsubmit" id="b_delete" value ="Apagar">';
				
			
			echo $strfield . '</form> </div>';
			
			$strfield=  "<div id=historico_status>";
			echo $strfield;
			$stroption='<div class="linha_status">Status</div> ';
			$stroption.='<div class="linha_status">'.utf8_encode("Comentário"). '</div> ';
			$stroption.='<div class="linha_status">Data </div> ';
			$stroption.='<div class="clear"></div>';
				
			//verifica se tem registro de crm
			if($crm_seq !="")
			{
				$sql = "SELECT  date_format(hcrm_date,'%d/%m/%Y  %H:%i') as hcrm_date2, hcrm_comment,scrm_desc FROM hist_crm,status_crm where hcrm_scrm_seq=scrm_seq and hcrm_crm_seq=" . $crm_seq ;
				$result = $conn->query($sql);
			
			
				while ($linha = $result->fetch_assoc())
				{
				$stroption.='<div class="linha_report">'. $linha["scrm_desc"].  '</div>';
				$stroption.='<div class="linha_report">'. $linha["hcrm_comment"].  '</div>';
				$stroption.='<div class="linha_report">'. $linha["hcrm_date2"].  '</div>';
				$stroption.='<div class="clear"></div>';
					
				}			
			
			}
				echo $stroption. "</div>";
			
			mysqli_close($conn); 
?>
</div>

