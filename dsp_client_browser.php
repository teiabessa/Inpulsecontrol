<?php
//Eu faço a busca no banco de dados de todos os registros de clientes


	/* Invoca o arquivo que faz conexão com o db */
	//require_once 'db.php';
	//Sentença de busca no banco
	$sql = "Select * FROM client where client_seq > 0 ORDER BY client_name"; 
	$result = $conn->query($sql);
	echo utf8_encode("Busca Avançada");
?>

<!-- <div id='divformsearch'> -->
<!-- <form action="action_page.php" method="get"> -->

<input list="browsers" name="browser" id="client_name" size="40">
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
	<INPUT type="button" value="Pesquisar" name="searchclient" onClick="seleciona_cliente();">

<!--</form> 
</div>-->