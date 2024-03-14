<?php
//Eu faço a lista dos pacotes dos clientes
require_once 'db.php';

// Verifica se o cliente está ativo
	
	$sql= "SELECT * FROM pacote_servico_cliente Where secl_client_seq=". $_REQUEST['client_seq'];
	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		$return='Pacotes:<select name="secl_seq" id="secl_seq" class="frmclient" >';
		// Atribui o código HTML para montar uma tabela
		while ($linha = $result->fetch_assoc())
		{
			if ($secl_seq == $_REQUEST['secl_seq'])
				$return.= '<option value="'. $linha["secl_seq"]. '"selected >'. $linha["secl_desc"]. '</option>';
			else
				$return.= '<option value="'. $linha["secl_seq"]. '">'. $linha["secl_desc"]. '</option>';
		}
		//fecha o select
		$return.= "</select>";
		
	}
	
	
	 else 
	  	 // Se a consulta não retornar nenhum valor, exibi mensagem para o usuário 
	  	 echo "Null"; 
	
	 
	 //$return.= "</div>";
	 echo $return;
	 
mysqli_close($conn);
?>