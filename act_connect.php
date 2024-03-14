<?php 
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	require_once 'db.php';
	/* set the cache expire to 30 minutes */
	session_cache_expire(30);
	$cache_expire = session_cache_expire();

	// session_start inicia a sessão 
	session_start();
 	// as variáveis login e senha recebem os dados digitados na página anterior 
	$login = $_POST['login'];
	$senha = $_POST['senha'];
  	/* Invoca o arquivo que faz conexão com o db */
 
	$sql="select user_password, user_seq FROM user WHERE user_login= '". $login . "' AND user_password='".  $senha .  "'";
    //echo $sql;
    // A vriavel $result pega as varias $login e $senha, faz uma pesquisa na tabela de usuarios 
   	$result = $conn->query($sql);
    /* Logo abaixo temos um bloco com if e else, verificando se a variável $result foi bem sucedida, ou seja se ela estiver encontrado algum registro idêntico o seu valor será igual a 1, se não, se não tiver registros seu valor será 0. Dependendo do resultado ele redirecionará para a pagina site.php ou retornara para a pagina do formulário inicial para que se possa tentar novamente realizar o login */
   	if ($result->num_rows )
     { 
     	while ($linha = $result->fetch_assoc())
     	{
     	$_SESSION['login'] = $login; 
     	$_SESSION['senha'] = $senha;
     	$_SESSION['user_seq'] = $linha["user_seq"];
     	//echo "user_seq" . $_SESSION['user_seq'];     	
     	}
     }	 
     else
     { 
     	echo "else" . $_SESSION['user_seq'];
     	unset ($_SESSION['login']); 
     	unset ($_SESSION['senha']);
     	unset ($_SESSION['user_seq']);
     	
     } 
    
    //echo "valor=". $_SESSION['login'];
    //echo "user_seq =".$_SESSION['user_seq'];
    
    mysqli_close($conn);
   	echo "<script>location.href='". $path_inpulsecontrol. "/index.php'</script>";
  
  ?>

