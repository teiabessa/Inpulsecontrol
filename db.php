<?php

if ($_SERVER['SERVER_NAME'] == "localhost")
{
	$path_inpulsecontrol ="http://". $_SERVER['SERVER_NAME'] ."/control/InPulseControl";
	$servidor = '186.202.152.237';
	$usuario = 'inpulsebrasil';
	$senha = 'kgb070711';
	$banco = 'inpulsebrasil';
	
}
else
{
	$path_inpulsecontrol ="http://". $_SERVER['SERVER_NAME'] ."/inpulsecontrol";
	$servidor = '186.202.152.237';
	$usuario = 'inpulsebrasil';
	$senha = 'kgb070711';
	$banco = 'inpulsebrasil';
	
}


// Create connection
//global $conn ;
$conn= mysqli_connect($servidor, $usuario, $senha, $banco);



mysqli_set_charset($conn,'utf8');
//mysqli_query($spojeni, "SET COLLATION_CONNECTION = 'utf8';");



// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}


?>
