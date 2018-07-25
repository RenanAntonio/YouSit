<?php
	require('conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	if ($_POST) {
		$lugares = $_POST['lugares'];

		$insertSQL = $activeDB->runQuery("INSERT INTO mesa VALUES (null, 1, '$lugares')");
		$insertSQL->bindparam(":lugares", $lugares);
		$insertSQL->execute();
	  
	  	header('Location: ../index.php');
	}

?>