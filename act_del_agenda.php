<?php
//Eu apago a agenda passada por parametro
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$sql= "Update agenda set agenda_seq=agenda_seq*-1 where agenda_seq=" . $_GET["agenda_seq"];
$result = $conn->query($sql);

$sql="INSERT INTO agenda_log (log_action,log_comments,log_agenda_seq, log_user_seq) VALUES(1,'Apagado'," .$agenda_seq . ", " . $_SESSION['user_seq'] . ")";
$result = $conn->query($sql);
    
//echo $sql;   
// $path_inpulsecontrol, definido no arquivo db.php
    mysqli_close($conn);
   


//verifica se foi definido uma data da tela de agendamento
echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=0&agenda_start_time=". $_REQUEST['agenda_date']."'</script>";
	

?>