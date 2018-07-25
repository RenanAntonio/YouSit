<?php 
//Inicia a sessão 
session_start(); 

//Coleta infos digitadas
$cpf = $_POST['cpf']; 
$senha = $_POST['senha']; 

//Conecta com o banco
require('php/conexao.php');

//Consultar DataBase
$activeDB = new USER();

// A vriavel $result pega as varias $login e $senha, faz uma pesquisa na tabela de usuarios 
$result = $activeDB->runQuery("SELECT * FROM funcionario WHERE cpf = '$cpf' AND senha = '$senha'");
$result->execute(array(":cpf"=>$cpf, ":senha"=>$senha));
$resultRow = $result->fetchAll(PDO::FETCH_ASSOC);



$result = $activeDB->runQuery("SELECT COUNT(*) FROM funcionario WHERE cpf = '$cpf' AND senha = '$senha'");
$result->execute();
if ($result->fetchColumn() > 0){
	$_SESSION['cpf'] = $cpf; 
 	$_SESSION['senha'] = $senha; 
 	header('location: index.php');
} else {
	unset ($_SESSION['cpf']); 
	unset ($_SESSION['senha']); 
	header('location: login.php?message=error');
}

?>