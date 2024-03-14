
<?php
//Eu faço a busca no banco de dados de todos os tratamentos

// verifica se a chamada é da agenda ou de outro
if (isset($agenda_type)== false)
    require_once 'db.php';
	
	echo tratamento($conn);
 
 if (isset($agenda_type)==false)	
	mysqli_close($conn);
	
	
	function tratamento($conn)
	{
	//Sentença de busca no bancgo
	$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=0"; 
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
		$vreturn='Tratamento:<select multiple  name="tyse_seq" id="tyse_seq[]"  class="frmclient" size="10">';
		
		// Coloca os tratamentos na variavél return 
		while ($linha = $result->fetch_assoc())
		{
			$vreturn.= '<option class="frmnegrito"  value="'. $linha["tyse_seq"]. '">'. $linha["tyse_desc"]. '</option>'; 
			//Faz a busca em nichos
			$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=". $linha["tyse_seq"]. " Order by tyse_desc ASC" ;
			
			$result2 = $conn->query($sql);
			$vreturn.= $linha["tyse_desc"];
			// Atribui o código HTML para montar uma tabela
			while ($linha2 = $result2->fetch_assoc())
             {
				$vtyse_desc=$linha2["tyse_desc"];
				$vreturn.= '<option value="'. $linha2["tyse_seq"].'">'. $vtyse_desc . '</option>';
             }
         			
		}
		
		$vreturn.='</select>';
	
		
	}
    
    else
    	// Se a consulta não retornar nenhum valor, exibi mensagem para o usuário
    	$vreturn= "Null";
    
    //retorna a variavel com o select
    return $vreturn;
    
	}  
    
?>

