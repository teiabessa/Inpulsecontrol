<html>
<title>In Pulse Estética</title>

<!DOCTYPE html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>


<?php

// no arquivo tem que retirar as strings telefone, comercial, residencia, residencial, fixo, 
// tem que remover os espaços (12) 3949 para (12)3949

ini_set('display_errors', 1);
error_reporting(E_ALL);

//Eu insiro um novo cliente no banco


$linha=0;
$sql_final="";
/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$array_field=array('nome,' , 'contato,'  , 'ndereco,' ,  'bairro,'  ,  'idade,'  , 'uf,' , 'cep,' , 'ata_cadastro,' , 'data_nascimento,' , 'estado_civil,'  , ' dumpy,');



$myfile = fopen("sql/relatorio.csv", "r") or die("Unable to open file!");


$pos=0;
$n=0;
$sql="";
$sql_caption= "Insert into client ( client_name ,  client_address ,  client_bairro ,client_cidade ,client_uf, client_cep , client_data_ultimo_atend ,client_data_nascimento ,client_estado_civil) Values (" ;

while(!feof($myfile)) 
{	
	$linha_arquivo= fgets($myfile);
    //echo "linha=". $linha_arquivo .  "array_field=" . $array_field[$n].    "pos=" . strpos($linha_arquivo,$array_field[$n] ) . "<br>";
    // Se achar o código, inicia novamente a contagem
    if(strpos($linha_arquivo,'odigo,'))
    {
		$n=0;
		$nextpos=1;
		$contato="";
		$sql= "";
		$linha=$linha + 1;
    }
    // faz o loop enquanto encontrar o  campo na linha do arquivo
	while(strpos($linha_arquivo,$array_field[$n] ))
	 {
	    $pos= strpos($linha_arquivo,$array_field[$n]);
		$tam_field= strlen($array_field[$n]) ;
		$posini= $tam_field  + $pos;
		// posicao final do campo a ser procurado
		
		//  Não faz separaçao por ","  pois tem "," contido no campo endereco
		if ( $array_field[$n] == 'ndereco,')
			$pos2= strpos($linha_arquivo,$array_field[$nextpos]);
		else
			// se achar a ",", indica que é o proximo campo
			$pos2= strpos($linha_arquivo, "," ,  $posini);
		
		
		$tam_str=$pos2-$posini;
		// se não encontrou o array de campos na linha, é que chegou ao fim da linha
		if( $pos2 == 0)
		{ 
			$tam_str= strlen($linha_arquivo);
		}
		// remove o character "," final
		$texto=substr ($linha_arquivo , $posini ,$tam_str );
		$texto2 =  str_replace(", "  ,  "" ,$texto) ;
		//$texto2 = '"'. str_replace(", "  ,  "" ,$texto) . '"';
		
		// quando for o campo contato, a variavel será iniciada e mantida em $contato, depois será inserida na tabela telefones_clients
		if ( $array_field[$n] == 'contato,')
			$contato=$texto2;
		
		if ($array_field[$n] == 'estado_civil,')
		{
			
			$sql.=  ' " ' . $texto2 .  '")';
			$sql= $sql_caption .$sql;  
			//$sql_final.=$sql;
			echo  $sql . "<br>" ;
			$result = $conn->query($sql);
			//obtem o identificador da client inserida
			
		     $client_seq= $conn->insert_id;
            
            // faz o trabalho de desmembrar os telefones
			$contato_array = explode(" ", $contato);
			$arrlength = count($contato_array);
			$sql2="";
			for($x = 0; $x < $arrlength; $x++)
			{
				if  (trim($contato_array[$x]))
				{
					$sql2= "INSERT INTO telefone_clients (tecl_desc,  tecl_client_seq) VALUES ('" . $contato_array[$x] .  "'," . $client_seq .")";
				 // echo "sql2=" . $sql2 . "<br>" ;
					$result = $conn->query($sql2);
				}
							
			}
				
		}
		// SE nao for estado civil
		else
	{ 
			// quando for o campo contato, não é para inserir na tabela  clients
			if ( $array_field[$n] != 'contato,')
			{	
				//data_nascimento,02/04/1980 -	ultima_edicao,11/12/2012 17:06:15,
				// quando for datas, pega a funçao do mysql que trabalha com datas, pois não tem em php
				if (( $array_field[$n] == 'ata_cadastro,') ||  ($array_field[$n] == 'data_nascimento,') )
				{
				if (trim($texto2 >7)) 
					{
					 $data_certa=	"SELECT STR_TO_DATE('" .  substr ($texto2 , 0 ,10 ) . "','%d/%m/%Y') as data_alterada";
					 $result = $conn->query($data_certa);
					 while ($valor = $result->fetch_assoc())
					 
					$sql.='"' . $valor["data_alterada"] .  '",';
					}
					
					else 
						$sql.="NULL,";
				 }	
			
				else 
					$sql.=  '"'  . $texto2 .  '",';
			}
	}		
		
		//echo "texto2=" . $texto2 . "<br>" ;
		$n= $n + 1;
		$nextpos= $nextpos + 1;
		
	}
}
fclose($myfile);

echo "qtd linha = " . $linha;

//echo  $sql_final . "<br>";
//$result = $conn->query($sql_final);
mysqli_close($conn);

?>
</body>
</html>



