<?php
	require('conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	if ($_POST) {
		$nome = $_POST['nome'];
		$telefone = $_POST['telefone'];
		$lugares = $_POST['lugares'];
		if ( isset($_POST['especial']) ) {
		    $especial = "s";
		} else { 
		    $especial = "n";
		}
		
	    $aplicativo = "n";

	    $data = array(
		    'nome'      => 'Teste',
		    'telefone'    => 11999999999,
		    'especial'       => true,
		    'lugares' => 3
		);

		$json = json_encode($data);

		$client = new Zend_Http_Client('localhost:9000/api/reserva/criaReserva');
		$client->setRawData($json, 'application/json')->request('POST');
			
		/*$insertSQL = $activeDB->runQuery("INSERT INTO RESERVAS VALUES (null, '$nome', '$telefone', '$lugares', '$especial', '$aplicativo', '', 0)");
		$insertSQL->bindparam(":nome", $nome);
		$insertSQL->bindparam(":telefone", $telefone);
		$insertSQL->bindparam(":lugares", $lugares);
		$insertSQL->bindparam(":especial", $especial);
		$insertSQL->bindparam(":aplicativo", $aplicativo);
		$insertSQL->execute();
		  
		header('Location: ../reserva_cadastrada.php');*/
	  
	}

?>