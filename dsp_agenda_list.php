<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
// echo "dentro dsp_agenda_list.php";
/* Invoca o arquivo que exibe o formulario */
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

require_once 'dsp_form_date.php';


/* Invoca o arquivo que faz conexão com o db */


//echo "depois chamar o banco";



/* Imprime a lista de salas */
echo  '<div class="clear"></div>';
echo  '<div id="salasblock">';

for ($i = 1; $i <= 5; $i++) 
{
	echo  '<div class="salasclinica">';
	if($i == 1)
		echo  ' Suíte ' ;
	
	if($i == 2)
		echo  ' Estética ' ;
		
	if($i == 3)
		echo  ' Fotodepilação ' ;
	
	elseif ($i == 4)
	echo  ' Facial ' ;
	
	elseif ($i == 5)
		echo  'Manicure ' ;
	
	//else 
	//	echo  ' Sala Estética '.$i ;
	
	echo  '</div>';

}
echo  '</div> <div class="clear"></div>';



echo '<div class="columns"> <div class="timeblock">';
//echo  '</div> <div id="schedule"><div id="timeblock">';
/* Array com os horários de atendimento */

$horario = array("07:00","07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30", "22:00");

	/* Imprime o DIV com os horários de atendimento */
	foreach($horario as $i) {
		//echo  $i. '<br>';
		echo  '<div id="scheduletime"> '.$i . '</div>';

	}

echo  '</div>'; //fecha div do quadro de horario  '%Y/%m/%d'

/* Começa a listar o agendamento por salas de atendimento = 5 salas */
for ($i = 1; $i <= 5; $i++) 
{   
	echo '<div class="sala" >';
	$leftdiv=120;
	$topdiv=120;
		
	//É necessário esse laço para imprimir todos os divs em branco da agenda
	$linha=0;
		
		while($linha <= 30)
		{
			
			$sql = "SELECT agenda_concluded, client_activate,agenda_desc,agenda_seq,time_format(agenda_start_time,'%H:%i') as agenda_time_ini, time_format(agenda_end_time,'%H:%i') as agenda_time_end, prof_name, agenda_start_time, agenda_end_time, client_name, client_seq FROM profissional, agenda, client";
			$sql .=	" WHERE agenda_client_seq=client_seq AND agenda_prof_seq=prof_seq AND agenda_seq > 0 AND agenda_sala=". $i ." AND";
			//$sql .=	" WHERE agenda_prof_seq=prof_seq AND agenda_client_seq <> 12 and agenda_seq > 0 AND agenda_sala=". $i ." AND";
			$sql .=	" agenda_concluded not in (1,2) AND agenda_start_time='". $agenda_start_time." ". $horario[$linha]. ":00'";
			$sql .=" ORDER BY  agenda_start_time";
			
            $result = $conn->query($sql);
		   //  echo $sql;
                      
			$heightdiv=0;
			if ($result->num_rows > 0) 
		 	{
				// output data of each row
				while($row = $result->fetch_assoc()) 
				{

                $tel_client="";
 				$sql2 = "Select  tecl_operadora, tecl_desc FROM telefone_clients where tecl_client_seq=".$row["client_seq"] ;
 				$result2 = $conn->query($sql2);
 				if ($result2)
 				{
 					//while ($linha2 = $result2->fetch_assoc())
					$linha2 = $result2->fetch_assoc();

 						$tel_client.="<br>".$linha2["tecl_desc"].  "  ".  $linha2["tecl_operadora"];

 				}

					//enquanto não chegar no horario do término, soma a altura do div
					//ini=8 end=9:00 heig=25 horario[linha]=8/ linha=8:30 heigh=50/ linha=9
				         while($row["agenda_time_end"] != $horario[$linha]  )
						{
							$heightdiv=$heightdiv + 39;
							$linha=$linha + 1;
						}
						//echo  '<div class="event_fill" > Cliente: '.$row["client_name"] .'<br>Prof: '.$row["prof_name"] . $row["agenda_time_ini"] .$row["agenda_time_end"].'</div>';
				    //echo  '<div class="event_fill" style="height:'. $heightdiv .'px;"> Cliente: '.$row["client_name"] .'<br>Prof: '.$row["prof_name"] . '</div>';
						$div_name="event_fill";

						if ($row["agenda_concluded"]==0 && $row["client_activate"]==2)
							$div_name="clientnew";
						 
				       	if ($row["agenda_concluded"]==3)
				       	$div_name="concluded";
				       
				       	if ($row["agenda_concluded"]==2)
				       	$div_name="canceled";
				       
				       	if ($row["agenda_concluded"]==4)
				       	$div_name="confirm";
				        
				       	
				       	
				       $strdiv='<div id="'. $div_name . '" style="height:'. $heightdiv .'px;">';
				       $strdiv.= $row["agenda_time_ini"] . " ".  $row["agenda_time_end"]. ' <a href="dsp_form_agenda.php?agenda_seq='. $row["agenda_seq"]. '">';
                       $strdiv.= substr($row["client_name"], 0,20)  . '/'. $tel_client. '/'. '<b>'. $row["prof_name"]  . '</b> /' . substr($row["agenda_desc"], 0,14) . '</a> </div>';
                       //$strdiv.=$row["client_name"] .'/' .$row["prof_name"] . '</a> </div>';
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
