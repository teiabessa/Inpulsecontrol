
<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
// echo "dentro dsp_agenda_list.php";
/* Invoca o arquivo que exibe o formulario */
require_once 'dsp_form_date.php';

/* Invoca o arquivo que faz conexão com o db */
require 'db.php';

/* Imprime a lista de salas */
echo  '</div> <div class="clear"></div>';
echo  '<div id="salasblock">';

for ($i = 1; $i <= 4; $i++) 
{
	echo  '<div class="salasclinica"> Sala '.$i . '</div>';

}
echo  '</div> <div class="clear"></div>';



echo '<div class="columns"> <div class="timeblock">';
//echo  '</div> <div id="schedule"><div id="timeblock">';
/* Array com os horários de atendimento */

$horario = array("07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00");

	/* Imprime o DIV com os horários de atendimento */
	foreach($horario as $i) {
		//echo  $i. '<br>';
		echo  '<div id="scheduletime"> '.$i . '</div>';

	}

echo  '</div>'; //fecha div do quadro de horario  '%Y/%m/%d'

/* Começa a listar o agendamento por salas de atendimento = 4 salas */
for ($i = 1; $i <= 4; $i++) 
{   
	echo '<div class="sala" >';
	$leftdiv=120;
	$topdiv=120;
		
	//É necessário esse laço para imprimir todos os divs em branco da agenda
	$linha=0;
		
		while($linha <= 26)
		{
			$sql = "SELECT agenda_desc,agenda_seq,time_format(agenda_start_time,'%H:%i') as agenda_time_ini, time_format(agenda_end_time,'%H:%i') as agenda_time_end, prof_name, agenda_start_time, agenda_end_time, client_name FROM agenda";
			$sql.= " left outer join profissional on (agenda_prof_seq=prof_seq)left outer join client on (agenda_client_seq=client_seq)";
			//$sql .=	"WHERE prof_seq=agenda_prof_seq AND client_seq=agenda_client_seq AND agenda_seq > 0 AND agenda_sala=". $i ." AND";
			$sql .=	" WHERE agenda_seq > 0 AND agenda_sala=". $i ." AND";
			$sql .=	" agenda_start_time='". $agenda_start_time." ". $horario[$linha]. ":00'";
			$sql .=" ORDER BY  agenda_start_time";
			$result = $conn->query($sql);
		     //echo $sql;
			$heightdiv=0;
			if ($result->num_rows > 0) 
		 	{
				// output data of each row
				while($row = $result->fetch_assoc()) 
				{
					//enquanto não chegar no horario do término, soma a altura do div
					//ini=8 end=9:00 heig=25 horario[linha]=8/ linha=8:30 heigh=50/ linha=9
				         while($row["agenda_time_end"] != $horario[$linha]  )
						{
							$heightdiv=$heightdiv + 39;
							$linha=$linha + 1;
						}
						//echo  '<div class="event_fill" > Cliente: '.$row["client_name"] .'<br>Prof: '.$row["prof_name"] . $row["agenda_time_ini"] .$row["agenda_time_end"].'</div>';
				    //echo  '<div class="event_fill" style="height:'. $heightdiv .'px;"> Cliente: '.$row["client_name"] .'<br>Prof: '.$row["prof_name"] . '</div>';

				    $strdiv='<div id="event_fill" style="height:'. $heightdiv .'px;">';
					//$strdiv="";
				    $strdiv.='<a href="act_del_agenda.php?agenda_seq='. $row["agenda_seq"]. '">'; 
				    $strdiv.= '<img src="images/delete.jpg"> </a>';
				    
				    $strdiv.='<a href="dsp_form_agenda.php?agenda_seq='. $row["agenda_seq"]. '">';
                    $strdiv.='Cliente: '. $row["client_name"] . '<br> ' .  $row["prof_name"]  . '/ ' . $row["agenda_desc"] .  '</a> </div>';
                    //$strdiv.='Cliente: '.$row["client_name"] .'<br>Prof: '.$row["prof_name"] . '</a>';
				    echo $strdiv;
				          	
		 	     } // while da query
		  }//if 
			else
			{
				//echo '<div id="event_fill">'. $linha. ' <br></div>';
				echo '<img  class="img_empty"	 src="images/image_cedula.jpg" alt="Insira uma nova agenda" ><br>';
				$linha=$linha + 1;
				
			}
		
		}// while 23
 	
 echo '</div>';
} //fecha o for das salas
echo '</div> <div class="clear"></div>'; // div column


mysqli_close($conn);
?>
