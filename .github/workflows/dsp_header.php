<html>
<title>In Pulse Estética</title>

  
<link href="css/inpulse_home.css" rel="stylesheet" type="text/css" />
<script src="js/addInput.js" language="Javascript" type="text/javascript"></script>
<script src="js/pacote.js" language="Javascript" type="text/javascript"></script>



<!DOCTYPE html>
<head>
<link href="css/estilo_tablets2.css" rel="stylesheet" type="text/css"  media="all"/>
<link href="css/control.css" rel="stylesheet" type="text/css"  media="all"/>
<!--
<link href="css/control.css" rel="stylesheet" type="text/css"  media="all"/>
dispositivos com largura máxima de 800px (por exemplo tablets) -->
<link href="css/estilo_tablets2.css" rel="stylesheet"  type="text/css" media="all" />
<!-- dispositivos com largura máxima de 320px (por exemplo smartphones) -->
<link href="css/smartphones.css" rel="stylesheet" type="text/css" media="screen and (max-width:320px)" />
<!--
<meta name="viewport" content="width=device-width, user-scalable=no">   -->

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="description" content="In Pulse - Melhor Clínica de Estética, de Fotodepilação e de Micropigmentação de São José dos Campos">
<meta name="keywords" content="clinica estetica, sjc, são josé dos campos,sao jose dos campos,Fotodepilação,fotodepilacao,gordura,flacidez,estrias,celulite,pelos,sobrancelhas, designer sobrancelhas, micropigmentacao, drenagem linfática,massagem relaxante, massagem modeladora, micropigmentação,dermopigmentacao,dermopigmentação,sonofocus,lipocavitação, lipocavitacao,radiofrequência, pós-operatório">
<meta name="author" content="Téia Katagi">


</head>
<body>


<div id= "header_inpulse">
		
	<div id="logo">

		<img src="images/logo_inpulse.jpg" alt="In Pulse">
	</div>
       	
	<div id="menu">
	<ul>

	<?php
		$lista_class = array("normal", "normal", "normal", "normal", "normal", "normal");
		$lista_desc= array("Agenda","Clientes", "Pacotes", "Relatórios", "Ligações", "Sair");
		
		//$lista_class = array("normal", "normal");
		//$lista_desc= array("Agenda","Clientes");
		

	//Se nao tiver menu definido, obtém na marra

	if (! isset($_REQUEST['menu_seq']))
	{
		$v_menu=3;

	}
	else{
	$v_menu=$_REQUEST['menu_seq'];
	}


	//Obtém a classe selected do menu atual 
	for ($x=0; $x<=5; $x++) {
		if($v_menu == $x) $lista_class[$x]="selected";
			
	}

	//Exibe o menu principal
	for ($x=0; $x<=5; $x++) 	
	echo '<li><a class="'.$lista_class[$x].'" href="index.php?menu_seq='.$x. '">'. $lista_desc[$x]. '</a></li>';

	?>

	</ul>
	</div>


 </div>
	



