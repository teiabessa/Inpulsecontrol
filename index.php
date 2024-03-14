<?php
//echo "inicio index.php<br>";
session_start();

include 'dsp_header.php';

//echo "valor=". $_SESSION['login'];


if (isset($_SESSION['login']))
{

if (! isset($_REQUEST['menu_seq']))
	{
		$v_menu=0;

	}
	else{
	$v_menu=$_REQUEST['menu_seq'];
	}

	switch ($v_menu)
		{
  		case 1:
    	include 'dsp_form_client_list.php';
   		break;
  		
  		case 2:
    	include 'dsp_form_pacote_list.php';
    	break;
    	
  		case 3:
    	include 'dsp_report.php';
    	break;
    	
    	case 4:
    	include 'dsp_crm_list.php';
    	break;
    	
    	case 5:
    	include 'dsp_caixa_list.php';     	
    	break;
    	    		 

    	case 6:
    	include 'act_logout.php';
    	break;
    	
    	
    	
  		default:
     	include 'dsp_agenda_list.php';
	}
}

else 
	include 'login.html';

?>
