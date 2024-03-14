<?php
//Eu faço a busca no banco de dados dos registros de clientes passado via variaável "client_name"


/* Invoca o arquivo que faz conexão com o db */
require_once 'db.php';

$sql= "DELETE FROM telefone_clients Where tecl_client_seq=". $_GET["client_seq"];
$result = $conn->query($sql);

$sql= "UPDATE client set client_seq=client_seq*-1 where client_seq=". $_GET["client_seq"];
$result = $conn->query($sql);

echo $sql ;
mysqli_close($conn);

// $path_inpulsecontrol, definido no arquivo db.php
echo "<script>location.href='". $path_inpulsecontrol. "/index.php?menu_seq=1". "'</script>"; 
  
 
?>

