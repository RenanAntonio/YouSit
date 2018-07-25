<?php
header('Content-Type: text/html; charset=utf-8'); 

require('php/conexao.php');

//Consultar DataBase
$activeDB = new USER();


$reservas = $activeDB->runQuery("SELECT r.*, c.nome, c.telefone from cliente c INNER JOIN reserva r on r.cliente = c.id_cliente WHERE r.id_reserva not in (SELECT mr.id_reserva FROM mesa_reserva mr) ORDER BY cliente ASC");
$reservas->execute(array(":notificationValue"=>0));
$reservasRow = $reservas->fetchAll(PDO::FETCH_ASSOC);


/* Mesas */
$mesas = $activeDB->runQuery("SELECT DISTINCT m.*, max(mu.id_mesa_reserva) as id_mesa_reserva FROM mesa m LEFT JOIN mesas_utilizadas mu ON m.id_mesa = mu.id_mesa group by m.id_mesa");
$mesas->execute();
$mesasRow = $mesas->fetchAll(PDO::FETCH_ASSOC);

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
				<div class="legenda">
					<div class="legendaDisp"><p>Mesa Disponível</p></div>
					<div class="legendaIndisp"><p>Mesa Indisponível</p></div>
				</div>
				<div class="mapa">
					<?php foreach ($mesasRow as $mesasRow1) { 
						if ($mesasRow1['mesa_dispon'] == '1') {?>
						<div class="mesa disponivel" token="<?php echo "".$mesasRow1['id_mesa_reserva'].""; ?>" lugares="<?php echo "".$mesasRow1['qtdelugares'].""; ?>" cod-mesa="<?php echo "".$mesasRow1['id_mesa'].""; ?>">
							<span>Mesa <?php echo "".$mesasRow1['id_mesa'].""; ?></span>
							<span class="sub"><?php echo "".$mesasRow1['qtdelugares'].""; ?> lugares</span>
						</div>
					<?php } else { ?>
						<div class="mesa" token="<?php echo "".$mesasRow1['id_mesa_reserva'].""; ?>" lugares="<?php echo "".$mesasRow1['qtdelugares'].""; ?>" cod-mesa="<?php echo "".$mesasRow1['id_mesa'].""; ?>"><span>Mesa <?php echo "".$mesasRow1['id_mesa'].""; ?></span><span class="sub"><?php echo "".$mesasRow1['qtdelugares'].""; ?> lugares</span></div>
					<?php } } ?>
				</div>
			</div>
			<div class="areaLista">
				<div class="wrapLista">
					<div class="lista">
						<ul class="resUl">
							<?php foreach ($reservasRow as $reservasRow1) { ?>
								<li class="reservaLi" especial="<?php echo "".$reservasRow1['especial'].""; ?>" cod-reserva="<?php echo "".$reservasRow1['id_reserva'].""; ?>">
									<div class="lugares"><?php if ($reservasRow1['qtdelugares'] < 2) {echo "<span>".$reservasRow1['qtdelugares']."</span> lugar";} else {echo "<span>".$reservasRow1['qtdelugares']."</span> lugares";} ?></div>
									<div class="wrapRound">
										<div class="nome"><?php echo "".$reservasRow1['nome'].""; ?></div>
										<div class="telefone"><?php echo "".$reservasRow1['telefone'].""; ?></div>
									</div>
									<div class="remover"></div>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>	
			</div>		
		</div>
		<div class="notificacao"><span>0</span></div>
		<div class="fade_aguardo"></div>
		<div class="lista_aguardo">
			<h1>Aguardando</h1>
			<div class="lista"></div>
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

		//Prefixo telefone
		$('.reservaLi .telefone').each(function(){
			var prefixo = $(this).text().substr(0, 2);
			var numeroFull = $(this).text().substr(2);
			$(this).text('(' + prefixo + ') ' + numeroFull.substr(0,5) + '-' + numeroFull.substr(5));
		});



		//Sem Reserva
		if ($('.reservaLi').length < 1) {
			$('.wrapLista').hide();
			$('.semFila').remove();
			$('.wrapLista').after('<div class="semFila">No momento estamos sem fila de espera =)</div>');
		}
	</script>


</body>
</html>