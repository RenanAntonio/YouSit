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
	<title>YouSit | Cadastrar Funcionário</title>
</head>
<body>
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
			<div class="menuIcon"></div>
			<?php foreach ($nomeLogadoRow as $nomeLogadoRow1) { ?>
				<p>Olá, <?php echo "".$nomeLogadoRow1['nome'].""; ?></p>
			<?php } ?>
			<div class="logo"><a href="index.php">YouSit</a></div>
		</div>
		<div class="page">
			<form name="form1" id="form1" action="php/addFunc.php" method="post">
				<h1 class="titulo">Cadastrar Funcionário</h1>
				<div class="formulario">
					<div class="campo"><label>Nome:</label> <input type="text" class="nome" name="nome"></input></div>
					<div class="campo"><label>CPF: </label>  <input type="text" class="cpf" maxlength="14" name="cpf" autocomplete="new-cpf"></input></div>
					<div class="campo"><label>Senha: </label>  <input type="password" class="senha" maxlength="8" name="senha" autocomplete="new-password"></input></div>
					<div class="campo"><label>Telefone: </label>  <input type="text" class="telefone" name="telefone"></input></div>
					<div class="campo"><label>Endereço: </label>  <input type="text" class="endereco" name="endereco"></input></div>
					<div class="campo"><label>Cargo: </label>	
						<select class="cargo" name="cargo">
							<option value="1">Recepcionista</option>
							<option value="2">Garçom</option>
						</select>
					</div>
				</div>
				<input type="submit" class="cadastrar" value="Cadastrar" />
			</form>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			//Mascara de campos
			$('#form1 .cpf').mask("999-999-999-99");
			$('#form1 .telefone').mask("(99) 99999-9999");
		});

		//Existe CPF
		if (window.location.href.indexOf('message=error') >= 0) {
			$('#form1 .campo input[type="text"].cpf').each(function(){
				if ($(this).val() == "") {
					$(this).css('background', '#C34D4D').css('color','#FFF');
					$(this).attr('placeholder','CPF informado já existe');
				} else {
					$(this).css('background', '#FFF').css('color','#000');
				}
			});
		} else if (window.location.href.indexOf('message=success') >= 0) {
			$('.msgErro').remove();
	      	$('.cargo').after('<div class="msgErro success">Funcionário cadastrado com sucesso.</div>');
		}

		//Campos vazios
		$('body').on('click', '#form1 .cadastrar', function(event){
			var valida = true;
			$('#form1 input[type="text"], #form1 input[type="password"]').each(function(){
				if ($(this).val() == "") {
			      	$(this).css('background', '#C34D4D').css('color','#FFF');
			      	valida = false;
	      			$('.msgErro').remove();
	      	      	$('.cargo').after('<div class="msgErro">Preencha todos os campos.</div>');
			   	} else {
			   		$(this).css('background', '#FFF').css('color','#000');
			   	}

			   	//Valida CPF
			   	if ($(this).hasClass('cpf')) {
					if (!window.CPF.valida($('.cpf').val())) {
				      	$(this).css('background', '#C34D4D').css('color','#FFF');
				      	valida = false;
		      			$('.msgErro').remove();
		      	      	$('.cargo').after('<div class="msgErro">CPF inválido.</div>');
				   	} else {
				   		$(this).css('background', '#FFF').css('color','#000');
				   	}
				}
				
			});

			if (!valida) {
				event.preventDefault();
			}
		});

	</script>

</body>
</html>