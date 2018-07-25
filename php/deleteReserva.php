<?php 
	require_once('conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	//Update na tabela de reservas
	$tokenReserva = $_POST['tokenReserva'];
	$insertSQL2 = $activeDB->runQuery("DELETE FROM reserva WHERE id_reserva = '$tokenReserva'");
	$insertSQL2->execute();

?>