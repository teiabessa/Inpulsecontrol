
<?php
//Eu conto quantas sessoes tem no pacote
    require_once 'db.php';	
	
	$secl_seq=$_GET["secl_seq"];
	
	//seleciona todos os tratamentos passados 	
	$sql = "Select count(agenda_secl_seq) as qtd_sessao FROM agenda WHERE agenda_seq > 0 AND agenda_concluded=3 AND agenda_secl_seq=".$secl_seq;
	$qtd_sesao=0;
	//echo $sql;
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0)
	{
		// Coloca os tratamentos na variav�l return 
		while ($linha = $result->fetch_assoc())
			$qtd_sesao=$linha['qtd_sessao']; 
			
		$vreturn=$qtd_sesao;
	}
			
    
    else
    	// Se a consulta n�o retornar nenhum valor, exibi mensagem para o usu�rio
    	$vreturn= "Null";
    
    //retorna a variavel com a somatoria
    
    echo $vreturn;
	
	mysqli_close($conn);
    
?>

