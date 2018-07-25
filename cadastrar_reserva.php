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
	<title>YouSit | Nova Reserva</title>
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
			<form name="form1" id="form1"  method="post">
				<h1 class="titulo">Nova Reserva</h1>
				<div class="formulario">
					<div class="campo"><label>Nome:</label> <input type="text" class="nome" name="nome"></input></div>
					<div class="campo"><label>Celular:</label> <input type="text" class="telefone" name="telefone" maxlength="12" onkeypress="return isNumberKey(event);"></input></div>
					<div class="campo"><label>Número de Lugares:</label> 
						<select class="lugares" name="lugares">
							<option value="1">1 lugar</option>
							<option value="2">2 lugares</option>
							<option value="3">3 lugares</option>
							<option value="4">4 lugares</option>
							<option value="5">5 lugares</option>
							<option value="6">6 lugares</option>
							<option value="7">7 lugares</option>
							<option value="8">8 lugares</option>
							<option value="9">9 lugares</option>
							<option value="10">10 lugares</option>
							<option value="11">11+ lugares</option>
						</select>
					</div>
					<div class="campo check"><label><input type="checkbox" name="especial" value="s" class="preferencial"/>É cliente preferencial?</label></div>
				</div>
				<input type="button" class="cadastrar" value="Cadastrar" />
			</form>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			//Mascara de campos
			$('#form1 .telefone').mask("(99) 99999-9999");
		});
		//Campos vazios
		$('body').on('click', '#form1 .cadastrar', function(event){
		   if ($('#form1 .nome').val() == "" || $('#form1 .telefone').val() == "") {
		      event.preventDefault();
		      $('.msgErro').remove();
		      $('.lugares').after('<div class="msgErro">Preencha todos os campos.</div>');
		      $('#form1 .campo input[type="text"]').each(function(){
				if ($(this).val() == "") {
					$(this).css('background', '#C34D4D').css('color','#FFF');
				} else {
					$(this).css('background', '#FFF').css('color','#000');
				}
			});
		   } else {
		   	var nome = $('#form1 input.nome').val();
		   	var telefone = $('#form1 input.telefone').val().replace(/[^0-9]+/g, '');
		   	if ($('#form1 input[name="especial"]').is(':checked')) {
		   		var especial = true;
		   	} else {
		   		var especial = false;
		   	}
		   	
		   	var lugares = parseInt($('#form1 select.lugares').val());

	   		$.ajax({
	   	    	type: "POST",
	   	    	dataType: 'json',
	   	    	contentType: 'application/json',
	   	    	crossDomain: true,
	   	        url: window.dominio+"/api/reserva/criaReservaEnviaSMS",
	   	        data: JSON.stringify({'nome': nome, 'telefone': telefone, 'especial': especial, 'lugares': lugares}),
	   	        success: function(){
	   	        	console.log('Inserido com sucesso');
	   	        	window.location.href = "index.php";
	   	        	//
        	        /*//Enviar SMS WS
        			$.ajax({
        		    	type: "POST",
        		    	dataType: 'HTML',
        		        url: "http://localhost:9000/api/sms/enviaSMS",
        		        data: "token="+reservaID,
        		        success: function(){
        		        	console.log('Enviado SMS com sucesso');
        		        }
        		    });*/
	   	        }
	   	    });
		   }
		});
	</script>
</body>
</html>