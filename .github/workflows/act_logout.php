<?php
if ($_SERVER['SERVER_NAME'] == "localhost")
{
	$path_inpulsecontrol ="http://". $_SERVER['SERVER_NAME'] ."/control/InPulseControl";

}
else
{
	$path_inpulsecontrol ="http://". $_SERVER['SERVER_NAME'] ."/inpulsecontrol";

}

//eu saio do sistema
session_start();
unset ($_SESSION['login']); 
unset ($_SESSION['senha']);
echo "<script>location.href='". $path_inpulsecontrol. "/index.php'</script>";
?>

