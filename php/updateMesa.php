<?php 
	require_once('conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	//Update na tabela de mesas
	$mesaID = $_POST['mesaID'];
	$insertSQL = $activeDB->runQuery("UPDATE MESAS SET mesa_dispon = 'n' WHERE id_mesa = '$mesaID'");
	$insertSQL->execute();

	//Update na tabela de reservas
	$reservaID = $_POST['reservaID'];
	$insertSQL2 = $activeDB->runQuery("UPDATE RESERVAS SET notificationValue = 1 WHERE id_reserva = '$reservaID'");
	$insertSQL2->execute();
?>