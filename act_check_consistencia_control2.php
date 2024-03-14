<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu checo se há alguma inconsistência no cadastro do agendamento 

$destination="";



// 1- Verifica se a hora inicial ou final é diferente de 30 ou 00


$pos= strpos($_POST["agenda_start_time"],":");
$minuto_agenda=substr($_POST["agenda_start_time"],$pos+1);
//echo "minuto ini:".$minuto_agenda. "<BR>";
if((trim($minuto_agenda) != "00" AND trim($minuto_agenda) != "30"))
{
	$destination= "<script>alert('A hora inicial do agendamento tem que ser cheia ou de 30 min !')</script>";
}
$pos= strpos($_POST["agenda_end_time"],":");
$minuto_agenda=substr($_POST["agenda_end_time"],$pos+1);
//echo "minuto end:".$minuto_agenda;
if((trim($minuto_agenda) != "00" AND trim($minuto_agenda) != "30"))
	$destination= "<script>alert('A hora final do agendamento tem que ser cheia ou de 30 min !')</script>";


// 2- Verifica se este agendamento já foi cadastrada na mesma hora inicial ou final
    
	$sql = "SELECT agenda_seq FROM agenda WHERE agenda_sala=". $agenda_sala ." AND ";
	$sql.=	"agenda_seq > 0 AND ((agenda_concluded = 0 OR agenda_concluded = 3) AND ('";
        $sql.= $agenda_start_time . "' > agenda_start_time AND '" . $agenda_start_time . "' < agenda_end_time) OR ('" ;
        $sql.= $agenda_end_time . "' > agenda_start_time  AND '" . $agenda_end_time . "' <  agenda_end_time ) OR " ;
        $sql.=	"(agenda_start_time = '". $agenda_start_time . "' OR agenda_end_time ='". $agenda_end_time . "'))" ;
        //$sql .=	" agenda_seq > 0 AND (agenda_concluded = 0 OR agenda_concluded = 3) AND (agenda_start_time = '". $agenda_start_time . "' OR " . "agenda_end_time ='". $agenda_end_time . "')" ;
	$sql .=" ORDER BY  agenda_start_time";
        echo "dentro check= ".$sql;
         echo "<br> Data check= ".$agenda_start_time;
	$result_verify = $conn->query($sql);
        
	
	$strhorario= "Existe outro agendamento nesse horario : " . $agenda_start_time . " " . $_POST["agenda_end_time"];

	if ($agenda_seq == "0")
        {
         if($result_verify->num_rows > 0)
		$destination= "<script>alert('". $strhorario. "')</script>";
	
	}	
	else
        {
       	// Se tiver agendamentos, verifica se é o mesmo horário, senao pode atualizar
   		if($result_verify->num_rows > 0)	
		{
		while($row = $result_verify->fetch_assoc())
			{ 
				if ($row["agenda_seq"] != $agenda_seq  )
				
				$destination= "<script>alert('". $strhorario. "')</script>";
			
		         }
		}
        }	


// 3- Verifica se a hora inicial é menor que a hora final

        $hora_ini=strtotime($_POST["agenda_start_time"]);	
//        echo $hora_ini;
	$hora_end=strtotime($_POST["agenda_end_time"]);	
//echo $hora_end;
	if($hora_ini >= $hora_end)
         $destination= "<script>alert('O Horário inicial tem que ser menor que o horário final do agendamento !')</script>";
        
        

if (trim($destination) !="" )
{
	
//Verifica se a chamada é pelo pacote ou pelo agendamento do cliente
if (isset($_POST["ins_agenda"]))

  $destination.="<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=2&client_seq=". $client_seq . "&secl_seq=" . $secl_seq  . " '</script>";	

else
   {
	if ($agenda_seq == 0)
		$destination.="<script>location.href='". $path_inpulsecontrol. "/dsp_form_agenda.php'</script>";
	else
		$destination.="<script>location.href='". $path_inpulsecontrol. "/dsp_form_agenda.php?agenda_seq=". $agenda_seq . "'</script>";
   }
}



?>


		
	
		