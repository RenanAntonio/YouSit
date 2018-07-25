<?php
header('Content-Type: text/html; charset=utf-8'); 

require('php/conexao.php');

//Consultar DataBase
$activeDB = new USER();


$reservas = $activeDB->runQuery("SELECT r.*, c.nome, c.telefone from cliente c INNER JOIN reserva r on r.cliente = c.id_cliente WHERE r.id_reserva not in (SELECT mr.id_reserva FROM mesa_reserva mr) ORDER BY cliente ASC");
$reservas->execute(array(":notificationValue"=>0));
$reservasRow = $reservas->fetchAll(PDO::FETCH_ASSOC);

/* Get Token */
$token = $_GET['token'];

?>


<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<!-- <script type="text/javascript" src="js/scripts.js"></script> -->
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="style_app.css">
	<title>YouSit | Mapa do Restaurante</title>
</head>
<body id="app">
	<div class="hiddenToken"><?php echo $token; ?></div>
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
			<div class="logo"><a href="index.php">YouSit</a></div>
		</div>
		<div class="page">
			<div class="areaLista">
				<div class="wrapLista">
					<div class="lista">
						<ul class="resUl">
							<?php foreach ($reservasRow as $reservasRow1) { ?>
								<li class="reservaLi" especial="<?php echo "".$reservasRow1['especial'].""; ?>" cod-reserva="<?php echo "".$reservasRow1['id_reserva'].""; ?>">
									<div class="lugares"><?php if ($reservasRow1['qtdelugares'] < 2) {echo "<span>".$reservasRow1['qtdelugares']."</span> lugar";} else {echo "<span>".$reservasRow1['qtdelugares']."</span> lugares";} ?></div>
									<div class="wrapRound">
										<div class="nome"><?php echo "".$reservasRow1['nome'].""; ?></div>
										<div class="estimativa">Tempo estimado: 00 minutos</div>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>
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

		//Destaca usuario na fila
		$('.resUl li').each(function(){
			if ($(this).attr('cod-reserva') == $('.hiddenToken').text()) {
				$('.resUl li .estimativa').hide();
				$(this).addClass('destaque');
				$(this).find('.estimativa').show();
			}
		});
	</script>


</body>
</html>