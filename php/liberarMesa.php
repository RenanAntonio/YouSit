<?php 
	require_once('conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	//Liberar mesa ocupada
	$mesaID = $_POST['mesaID'];
	$insertSQL = $activeDB->runQuery("UPDATE MESAS SET mesa_dispon='s' WHERE id_mesa = '$mesaID'");
	$insertSQL->execute();
?>