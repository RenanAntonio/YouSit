<?php 
//Conecta com o banco
require('php/conexao.php');

//Consultar DataBase
$activeDB = new USER();

session_start(); 

unset($_SESSION['cpf']); 
unset($_SESSION['senha']); 
header('location: login.php'); 
?>