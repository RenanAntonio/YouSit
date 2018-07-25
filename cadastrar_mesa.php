<?php
	header('Content-Type: text/html; charset=utf-8'); 
	require('php/conexao.php');

	//Consultar DataBase
	$activeDB = new USER();

	session_start(); 
	if((!isset ($_SESSION['cpf']) == true) and (!isset ($_SESSION['senha']) == true)) { 
		unset($_SESSION['cpf']); 
		unset($_SESSION['senha']); 
		header('location: login.php'); 
	} 

	$logado = $_SESSION['cpf']; 
	$nomeLogado = $activeDB->runQuery("SELECT nome FROM funcionario WHERE cpf = '$logado'");
	$nomeLogado->execute(array(":cpf"=>$logado));
	$nomeLogadoRow = $nomeLogado->fetchAll(PDO::FETCH_ASSOC);

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>YouSit | Nova Mesa</title>
	<script>
		function isNumberKey(evt){
		    var charCode = (evt.which) ? evt.which : evt.keyCode
		    return !(charCode > 31 && (charCode < 48 || charCode > 57));
		}
	</script>
</head>
<body>
	<div class="container">
		<div class="menu">
			<ul class="paginas">
				<a href="index.php"><li class="mapaRes">Mapa do Restaurante</li></a>
				<a href="cadastrar_reserva.php"><li class="novaReserva">Cadastrar Reserva</li></a>
				<a href="cadastrar_funcionario.php"><li class="cadFunc">Cadastrar Funcionário</li></a>
				<a href="cadastrar_mesa.php"><li class="cadFunc">Cadastrar Mesa</li></a>
				<a href="logout.php"><li class="logout">Sair</li></a>
			</ul>
		</div>
		<div id="barraTopo">
			<div class="menuIcon"></div>
			<?php foreach ($nomeLogadoRow as $nomeLogadoRow1) { ?>
				<p>Olá, <?php echo "".$nomeLogadoRow1['nome'].""; ?></p>
			<?php } ?>
			<div class="logo"><a href="index.php">YouSit</a></div>
		</div>
		<div class="page">
			<form name="form1" id="form1" action="php/addMesa.php" method="post">
				<h1 class="titulo">Nova Mesa</h1>
				<div class="formulario">
					<div class="campo"><label>Número de Lugares:</label> 
						<select class="lugares" name="lugares">
							<option value="2">2 lugares</option>
							<option value="4">4 lugares</option>
							<option value="6">6 lugares</option>
							<option value="8">8 lugares</option>
							<option value="10">10 lugares</option>
						</select>
					</div>
				</div>
				<input type="submit" class="cadastrar" value="Cadastrar" />
			</form>
		</div>
	</div>

	<script>
	//Existe Nome
	if (window.location.href.indexOf('message=error') >= 0) {
		$('#form1 .campo input[type="text"]').each(function(){
			if ($(this).val() == "") {
				$(this).css('background', '#C34D4D').css('color','#FFF');
				$(this).attr('placeholder','Nome informado já existe');
			} else {
				$(this).css('background', '#FFF').css('color','#000');
			}
		});
	}

	//Campos vazios
/*	$('body').on('click', '#form1 .cadastrar', function(event){
	   	if ($('#form1 .nome').val() == "") {
	    	event.preventDefault();
	    	$('.msgErro').remove();
		    $('.lugares').after('<div class="msgErro">Preencha o número da mesa.</div>');
	      	$('#form1 .campo input[type="text"]').each(function(){
				if ($(this).val() == "") {
					$(this).css('background', '#C34D4D').css('color','#FFF');
				} else {
					$(this).css('background', '#FFF').css('color','#000');
				}
			});
	   	}
	});*/
	</script>

</body>
</html>