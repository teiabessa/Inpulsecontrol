<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';
/* set the cache expire to 30 minutes */
session_cache_expire(30);
$cache_expire = session_cache_expire();

// session_start inicia a sess�o 
session_start();
 // as vari�veis login e senha recebem os dados digitados na p�gina anterior 
 $login = $_POST['login'];
  $senha = $_POST['senha'];
  /* Invoca o arquivo que faz conex�o com o db */
 
  $sql="select prof_password FROM profissional WHERE  prof_login= '". $login . "' AND prof_password='".  $senha .  "'";
//  echo $sql;
    // A vriavel $result pega as varias $login e $senha, faz uma pesquisa na tabela de usuarios 
   $result = $conn->query($sql);
    /* Logo abaixo temos um bloco com if e else, verificando se a vari�vel $result foi bem sucedida, ou seja se ela estiver encontrado algum registro id�ntico o seu valor ser� igual a 1, se n�o, se n�o tiver registros seu valor ser� 0. Dependendo do resultado ele redirecionar� para a pagina site.php ou retornara para a pagina do formul�rio inicial para que se possa tentar novamente realizar o login */
   if ($result->num_rows > 0)
     { 
     	$_SESSION['login'] = $login; 
     	$_SESSION['senha'] = $senha; 
     }	 
     else
     { 
     unset ($_SESSION['login']); 
     unset ($_SESSION['senha']); 
    } 
    
    echo "valor=". $_SESSION['login'];
    
    mysqli_close($conn);
 echo "<script>location.href='". $path_inpulsecontrol. "/index.php'</script>";
  
  ?>

