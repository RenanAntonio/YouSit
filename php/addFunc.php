<?php
	require('conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	if ($_POST) {
		$nome = $_POST['nome'];
		$cpf = $_POST['cpf'];
		$telefone = preg_replace('/\D/', '', $_POST['telefone']);
		$endereco = $_POST['endereco'];
		$cargo = $_POST['cargo'];
		$senha = $_POST['senha'];

		$numRows = $activeDB->runQuery("SELECT COUNT(nome) AS count_nome FROM funcionario WHERE cpf = '$cpf'");
		$numRows->execute(array(":cpf"=>$cpf));
		$numRows = $numRows->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($numRows as $numRows1) {
			$num_cpf = $numRows1['count_nome'];
		}

		if ($num_cpf < 1) { 
			$insertSQL = $activeDB->runQuery("INSERT INTO funcionario VALUES (null, '$cargo', '$cpf', '$endereco', '$nome', '$senha', '$telefone')");
			$insertSQL->bindparam(":nome", $nome);
			$insertSQL->bindparam(":cpf", $cpf);
			$insertSQL->bindparam(":senha", $senha);
			$insertSQL->bindparam(":telefone", $telefone);
			$insertSQL->bindparam(":endereco", $endereco);
			$insertSQL->execute();

		  	header('Location: ../cadastrar_funcionario.php?message=success');
		} else {
			header('Location: ../cadastrar_funcionario.php?message=error');
		}
	}

?>