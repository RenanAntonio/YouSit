<?php
	header('Content-Type: text/html; charset=utf-8'); 
	require('php/conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	$token = strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 9));
	$reservaID = $activeDB->runQuery("SELECT id_reserva FROM RESERVAS ORDER BY id_reserva DESC LIMIT 1");
	$reservaID->execute();

	while ($reservaIDRow = $reservaID->fetch(PDO::FETCH_ASSOC)) {
		$finalID = "".$reservaIDRow['id_reserva'].""; 
	}

	$update = $activeDB->runQuery("UPDATE RESERVAS SET token='$token' WHERE id_reserva='$finalID'");
	$update->execute();

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>YouSit | Reserva Cadastrada</title>
</head>
<body class="reservaCadastrada">
	<div class="container">
		<div class="menu">
			<ul class="paginas">
				<a href="index.php"><li class="mapaRes">Mapa do Restaurante</li></a>
				<a href="cadastrar_reserva.php"><li class="novaReserva">Cadastrar Reserva</li></a>
				<a href="cadastrar_funcionario.php"><li class="cadFunc">Cadastrar Funcionário</li></a>
				<a href="cadastrar_mesa.php"><li class="novaReserva">Cadastrar Mesa</li></a>
				<a href="logout.php"><li class="logout">Sair</li></a>
			</ul>
		</div>
		<div id="barraTopo">
			<div class="menuIcon"><span>Navegação</span></div>
			<div class="logo"><a href="index.php">YouSit</a></div>
		</div>
		<div class="page">
			<h1 class="titulo">Reserva Cadastrada</h1>
			<div class="mensagem">
				A reserva foi efetuada com sucesso. <br> Esse foi o código gerado para a consulta no aplicativo:
			</div>
			<div class="boxCodigo"><?php echo $token; ?></div>
		</div>
	</div>
</body>
<script>
	function getRandomNum(lbound, ubound) {
		return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
	}

	function getRandomChar(number, lower, upper, other, extra) {
		var numberChars = "0123456789";
		var lowerChars = "abcdefghijklmnopqrstuvwxyz";
		var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		var otherChars = "`~!@#$%^&*()-_=+[{]}\\|;:'\",<.>/? ";
		var charSet = extra;
		if (number == true)
		charSet += numberChars;
		if (lower == true)
		charSet += lowerChars;
		if (upper == true)
		charSet += upperChars;
		if (other == true)
		charSet += otherChars;
		return charSet.charAt(getRandomNum(0, charSet.length));
	}

	function getPassword(length, extraChars, firstNumber, firstLower, firstUpper, firstOther,latterNumber, latterLower, latterUpper, latterOther) {
		var rc = "";
		if (length > 0)
			rc = rc + getRandomChar(firstNumber, firstLower, firstUpper, firstOther, extraChars);
		for (var idx = 1; idx < length; ++idx) {
			rc = rc + getRandomChar(latterNumber, latterLower, latterUpper, latterOther, extraChars);
		}
		return rc;
	}

	//var resultToken = getPassword(6, '', false, false, true, false, true, false, true, false);
	//$('.boxCodigo').append(resultToken);

</script>
<?php
	

	$reservaID = "SELECT id_reserva FROM RESERVAS ORDER BY id_reserva DESC LIMIT 1";
	$updateToken =  "UPDATE RESERVAS SET token='$token' WHERE id_reserva='$reservaID'";
  
  	//mysql_query($insertSQL, $conexao_top) or die(mysql_error());
  	//header('Location: http://yousit.topsongs.com.br');

?>
</html>