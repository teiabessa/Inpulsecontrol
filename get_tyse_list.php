
<?php
//Eu faço a busca no banco de dados de todos os tratamentos

    require_once 'db.php';
	
 	
		
		$sql = "Select tyse_seq,tyse_desc FROM type_service WHERE tyse_parent_seq=0";
		$result = $conn->query($sql);
		echo '<div id="div1">';
		$vreturn='Selecione o Tratamento, Pressione Add Campo e depois entre com os valores <select name="sel_tyse_seq"  id="sel_tyse_seq" required class="frmclient" >';
			
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
					
				// Verifica se existe no array
				if (in_array($linha2['tyse_seq'], $aget_tyse_seq))
					$vreturn.= 'selected';
					
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
						  if (isset($_REQUEST['secl_seq']))
						  {
					       
						  		//	Seleciona os tipos de tratamentos do agendamento atual
						  		$sql = "Select tyse_desc,tyse_seq,aget_value, tyse_valor  FROM type_service,agenda_service WHERE tyse_seq=aget_tyse_seq AND aget_agenda_seq=". $agenda_seq;
						  		$result = $conn->query($sql);
						  		if ($result->num_rows > 0)
						  		{	
						  			while ($linha = $result->fetch_assoc())
						  			{
										echo ' <input type="hidden"  name="tyse_seq[]"value="'. $linha["tyse_seq"]. '">';
								  		echo '<br><input type="text" name="tyse_desc[]" value="'. $linha["tyse_desc"]. '">';
								  		//echo '<input type="text" size="5"  name="aget_value[]" value="' . $linha["tyse_valor"]. '">';
								  		echo '<input type="text" size="5"  name="aget_value[]" value="14">';
									}
						  		}
						  }
						  
					     echo "</div>";
				
			mysqli_close($conn);
	
?>

