<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
//
session_start();
$user_seq= $_SESSION['user_seq'];

//Eu insiro uma nova agenda no banco
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

//Coloca a data como padrao do php
$caixa_data= $_POST["caixa_data"]. " ". $_POST["caixa_time"] . ":00";


//Seleciona os tipos de servicos dessa agenda


// Verifica se foi checado o formulário do pacote de cliente
if (isset($_POST["secl_seq"]))
{
	$sql= "Select secl_desc, secl_qtd_sessao_realizada From pacote_servico_cliente Where secl_seq=". $_POST["secl_seq"];
	$result1 = $conn->query($sql);
	while($row = $result1->fetch_assoc())
	{
	
		$agenda_desc= $row["secl_desc"];
	}

}
 




// Verifica se é para alterar ou inserir caixa
if (isset($_POST["caixa_seq"]))
{
	$caixa_seq=$_POST["caixa_seq"];
	
	$sql = "UPDATE lancamento_caixa SET";
	$sql.= "caixa_tipo=" . $_POST["caixa_tipo"]. ",";
	$sql.= "caixa_client_seq=" . $_POST["caixa_client_seq"] . ",";
	$sql.= "caixa_valor=" . $_POST["caixa_valor"] . ",";
	$sql.= "caixa_comment='" . $_POST["caixa_comment"] . "',";
	$sql.= "caixa_desc='" . $_POST["caixa_desc"] . "',";
	$sql.= "caixa_data='" . $caixa_data. "',";
	$sql.= "caixa_forma_pagto=" . $_POST["caixa_forma_pagto"]. ",";
	$sql.= "caixa_parcelas=" . $_POST["caixa_parcelas"];
	$sql.= "WHERE caixa_seq=" . $caixa_seq;
	
}
else
	
{
	$sql = "INSERT INTO lancamento_caixa (caixa_tipo,caixa_client_seq,caixa_valor,caixa_comment, caixa_desc, caixa_data, caixa_forma_pagto, caixa_parcelas)";
	$sql.= "VALUES (". $_POST["caixa_tipo"] . " , " . $_POST["caixa_client_seq"] . " , " . $_POST["caixa_valor"] . " , '" . $_POST["caixa_comment"] . "' , '" . $_POST["caixa_desc"]  . "' , '" . $caixa_data  . "'," . $_POST["caixa_forma_pagto"]  . "," . $_POST["caixa_parcelas"] . ")";
	$result = $conn->query($sql);
	//obtem o identificador do caixa inserida
	$caixa_seq= $conn->insert_id;

	

}
mysqli_close($conn);
$destination="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=5&caixa_seq=". $caixa_seq ."'</script>";
//echo $destination;


?>