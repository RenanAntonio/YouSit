<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>YouSit - Login</title>
	<script>
		function isNumberKey(evt){
		    var charCode = (evt.which) ? evt.which : evt.keyCode
		    return !(charCode > 31 && (charCode < 48 || charCode > 57));
		}
	</script>
</head>
<body class="login">
	<div class="container">
		<div class="page">
			<h1 class="titulo">YouSit</h1>
			<form method="post" action="logar.php" id="formlogin" name="formlogin">
				<div class="formulario">
					<div class="campo"><input type="text" name="cpf" id="cpf" maxlength="14" placeholder="CPF do usuário:" class="cpf"  onkeypress="return isNumberKey(event);" autocomplete="new-cpf"></input></div>
					<div class="campo"><input type="password" name="senha" id="senha" maxlength="8" placeholder="Senha:" class="senha" autocomplete="new-password"></input></div>
				</div>
				<input type="submit" class="cadastrar" value="Entrar" />
			</form>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			//Mascara de campos
			setTimeout(function(){
				$('#formlogin #cpf').mask("999-999-999-99");
			}, 1000);
		});

		if (window.location.href.indexOf('message=error') >= 0) {
			$('.campo input[type="text"], .campo input[type="password"], .campo select').css('background', '#C34D4D').css('color','#FFF');
			$('#senha').after('<div class="msgErro">CPF ou senha inválidos.</div>');
		}
		//Campos vazios
		$('body').on('click', '#formlogin .cadastrar', function(event){
			$('.msgErro').remove();
		   	if ($('#cpf').val() == "" ) {
		    	event.preventDefault();
		      	$('.msgErro.cpfMsg').remove();
		      	$('.campo input[type="text"], .campo select').css('background', '#C34D4D').css('color','#FFF');
		      	$('#senha').after('<div class="msgErro cpfMsg">Digite o CPF.</div>');
		   	} else {
		   		$('.msgErro.cpfMsg').remove();
		   	  	$('.campo input[type="text"], .campo select').css('background', '#FFF').css('color','#000');
		   	}
		   	if ($('#senha').val() == "") {
		      	event.preventDefault();
		      	$('.msgErro.senhaMsg').remove();
		      	$('.campo input[type="password"], .campo select').css('background', '#C34D4D').css('color','#FFF');
		      	$('#senha').after('<div class="msgErro senhaMsg">Digite a Senha.</div>');
		   	} else {
		   		$('.msgErro.senhaMsg').remove();
		   	  	$('.campo input[type="password"], .campo select').css('background', '#FFF').css('color','#000');
		   	}
		});
	</script>
</body>
</html>