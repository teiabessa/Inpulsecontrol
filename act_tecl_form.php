
  
<?php
//Eu faço a busca no banco de dados dos telefones de clientes

	
?>

                               
<span id="divtitlesearch"><?php echo utf8_encode("Entre com as informações do Telefone:");?></span>
	<div id="dynamicInput">
	$strfield="";
	<?php
	    $strfield.= '<input type="hidden" name="tecl_seq[]">';
	    $strfield.= '<input type="text" name="tecl_desc[]" >';
		$strfield.= '<input type="text" name="tecl_operadora[]">';
	     
		echo $strfield;
    
	?>
	<INPUT type="button" value="ADD Telefone" name="newtratamento" onClick="addtelefone('dynamicInput')" class="frmsubmit">
	
	</div>
	
