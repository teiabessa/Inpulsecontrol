
<?php
//Eu fa�o a somat�ria dos valores dos tratamentos

    require_once 'db.php';	
	$agenda_valor=0;
	$a=rtrim($_GET["tyse_seq_list"], ",");
	
	//seleciona todos os tratamentos passados 	
	$sql = "Select tyse_valor FROM type_service WHERE tyse_seq in (". $a. ")";
	$vreturn=$sql;
	
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0)
	{
		// Coloca os tratamentos na variav�l return 
		while ($linha = $result->fetch_assoc())
			$agenda_valor= $agenda_valor + $linha["tyse_valor"];
			
		$vreturn=$agenda_valor;
	}
			
    
    else
    	// Se a consulta n�o retornar nenhum valor, exibi mensagem para o usu�rio
    	$vreturn= "Null";
    
    //retorna a variavel com a somatoria
    
    echo $vreturn;
	
	mysqli_close($conn);
    
?>

