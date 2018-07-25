<?php
header('Content-Type: text/html; charset=utf-8'); 

require('php/conexao.php');

//Consultar DataBase
$activeDB = new USER();


$reservas = $activeDB->runQuery("SELECT * FROM RESERVAS WHERE notificationValue = 0 ORDER BY id_reserva");
$reservas->execute(array(":notificationValue"=>0));
$reservasRow = $reservas->fetchAll(PDO::FETCH_ASSOC);

$telefone = $activeDB->runQuery("SELECT telefone FROM RESERVAS WHERE notificationValue = 0 ORDER BY id_reserva");
$telefone->execute(array(":notificationValue"=>0));
$telefoneRow = $telefone->fetchAll(PDO::FETCH_ASSOC);

$lugares = $activeDB->runQuery("SELECT qtdelugares FROM RESERVAS WHERE notificationValue = 0 ORDER BY id_reserva");
$lugares->execute(array(":notificationValue"=>0));
$lugaresRow = $lugares->fetchAll(PDO::FETCH_ASSOC);

$remover = $activeDB->runQuery("SELECT COUNT(id_reserva) FROM RESERVAS WHERE notificationValue = 0");
$remover->execute(array(":notificationValue"=>0));
$removerRow = $remover->fetchAll(PDO::FETCH_ASSOC);

$aplicativo = $activeDB->runQuery("SELECT aplicativo FROM RESERVAS WHERE notificationValue = 0 ORDER BY id_reserva");
$aplicativo->execute(array(":notificationValue"=>0));
$aplicativoRow = $aplicativo->fetchAll(PDO::FETCH_ASSOC);


/* Mesas */
$mesas = $activeDB->runQuery("SELECT * FROM MESAS");
$mesas->execute();
$mesasRow = $mesas->fetchAll(PDO::FETCH_ASSOC);

session_start(); 
if((!isset ($_SESSION['cpf']) == true) and (!isset ($_SESSION['senha']) == true)) { 
	unset($_SESSION['cpf']); 
	unset($_SESSION['senha']); 
	header('location: login.php'); 
}

$logado = $_SESSION['cpf']; 

$nomeLogado = $activeDB->runQuery("SELECT nome FROM FUNCIONARIO WHERE cpf = '$logado'");
$nomeLogado->execute(array(":cpf"=>$logado));
$nomeLogadoRow = $nomeLogado->fetchAll(PDO::FETCH_ASSOC);



?>


<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>YouSit | Mapa do Restaurante</title>
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
			<div class="areaMapa">
				<h1 class="titulo">Mapa do Restaurante</h1>
				<div class="mapa">
					<?php foreach ($mesasRow as $mesasRow1) { 
						if ($mesasRow1['mesa_dispon'] == 's') {?>
						<div class="mesa disponivel" lugares="<?php echo "".$mesasRow1['qtdelugares'].""; ?>" cod-mesa="<?php echo "".$mesasRow1['id_mesa'].""; ?>"><span><?php echo "".$mesasRow1['nome'].""; ?></span><span class="sub"><?php echo "".$mesasRow1['qtdelugares'].""; ?> lugares</span></div>
					<?php } else { ?>
						<div class="mesa" lugares="<?php echo "".$mesasRow1['qtdelugares'].""; ?>" cod-mesa="<?php echo "".$mesasRow1['id_mesa'].""; ?>"><span><?php echo "".$mesasRow1['nome'].""; ?></span><span class="sub"><?php echo "".$mesasRow1['qtdelugares'].""; ?> lugares</span></div>
					<?php } } ?>
					<div class="legenda">
						<div class="legendaDisp"><p>Mesa Disponível</p></div>
						<div class="legendaIndisp"><p>Mesa Indisponível</p></div>
					</div>
				</div>
			</div>
			<div class="areaLista">
				<h1 class="titulo espera">Lista de Espera</h1>
				<div class="wrapLista">
					<div class="labelInfo">
						<ul class="resUl resNomePrinc"><div class="labels">Nome</div></ul>
						<ul class="resUl resTelefone"><div class="labels">Celular</div></ul>
						<ul class="resUl resLugares"><div class="labels">Lugares</div></ul>
						<ul class="resUl resRemover"><div class="labels">Remover</div></ul>
					</div>
					<div class="lista">
						<ul class="resUl resNome">
							<?php foreach ($reservasRow as $reservasRow1) { ?>
								<li class="reservaLi" especial="<?php echo "".$reservasRow1['especial'].""; ?>" cod-reserva="<?php echo "".$reservasRow1['id_reserva'].""; ?>"><?php echo "".$reservasRow1['nome'].""; ?></li>
							<?php } ?>
						</ul>
						<ul class="resUl resTelefone">
							<?php foreach ($telefoneRow as $telefoneRow1) { ?>
								<li class="reservaLi"><?php echo "".$telefoneRow1['telefone'].""; ?></li>
							<?php } ?>
						</ul>
						<ul class="resUl resLugares">
							<?php foreach ($lugaresRow as $lugaresRow1) { ?>
								<li class="reservaLi"><?php echo "".$lugaresRow1['qtdelugares'].""; ?></li>
							<?php } ?>
						</ul>
						<ul class="resUl resRemover">
							<?php foreach ($removerRow as $removerRow1) { $qntdFinal = "".$removerRow1['COUNT(id_reserva)'].""; };
							for ($i=0; $i<$qntdFinal; $i++) { ?>
								<li class="reservaLi"></li>
							<?php } ?>
						</ul>
					</div>
					<div class="preferencial">Cliente preferencial</div>
				</div>	
			</div>		
		</div>
	</div>
	<div class="bgMensagem"></div>
	<div class="mensagemLightbox">
		<p class="msgMesa">Deseja liberar essa mesa?</p>
		<input type="button" class="btMesaAlerta sim" value="Sim" />
		<input type="button" class="btMesaAlerta nao" value="Não" />
	</div>
	<div class="mensagemLightboxDisp">
		<p class="msgMesa">Deseja ocupar essa mesa?</p>
		<input type="button" class="btMesaAlerta sim" value="Sim" />
		<input type="button" class="btMesaAlerta nao" value="Não" />
	</div>

	<script>
		while ($('.mapa > .mesa').length > 0) {
			$('.mapa').append('<div class="wrapMesas" />');
			for (i=0; i<4; i++) {
				$('.wrapMesas:last').append($('.mapa > .mesa').eq(0));
			}
		}
		$('.mapa').append($('.mapa .legenda'));

		//Sem Reserva
		if ($('.reservaLi').length < 1) {
			$('.wrapLista').hide();
			$('.semFila').remove();
			$('.wrapLista').after('<div class="semFila">No momento estamos sem fila de espera. =)</div>');
		}
	</script>


</body>
</html>