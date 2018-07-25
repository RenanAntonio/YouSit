$(document).ready(function(){

//Variavel Global
window.dominio = 'http://localhost:9000';

/*	Funções de Cookies	*/
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}




/*	Menu  */
	$('.menuIcon').on('click', function(){
		if ($('.menu:visible').length > 0) {
			$('.paginas').hide();
		} else {
			setTimeout(function(){
				$('.paginas').fadeIn();
			}, 500);
		}
		$('.menu, .menuIcon span').animate({width: "toggle"}, "slow");
	});



/*	MouseOver - Linhas da reserva  */
	$('body').on('mouseover', '.lista .resUl .reservaLi', function(index){
		var posLi = $(this).index();
		$('.lista .resUl').each(function(){
			$(this).find('.reservaLi').eq(posLi).addClass('hoverBG');
		});
	});
	$('body').on('mouseout', '.lista .resUl .reservaLi', function(index){
		var posLi = $(this).index();
		$('.lista .resUl').each(function(){
			$(this).find('.reservaLi').eq(posLi).removeClass('hoverBG');
		});
	});




/*	Click - Linhas da reserva  */
	$('body').on('click', '.lista .resUl .reservaLi', function(index){
		if ($(this).hasClass('resSelecionada')) {
			$('.reservaLi').removeClass('resSelecionada');
			$('.wrapMesas').css('opacity','1').removeClass('escondido');
			$('.wrapMesas .mesa').css('opacity','1');
			$('.wrapMesas .msgOutraMesa').remove();
			window.lugaresReserv = 0;
			$('.mesa').removeClass('jaSelecionada');
		} else {
			$('.reservaLi').removeClass('resSelecionada');
			var posLi = $(this).index();
			$('.lista .resUl').each(function(){
				$(this).find('.reservaLi').eq(posLi).addClass('resSelecionada');
			});
			window.lugaresReserv = 0;
			$('.mesa').removeClass('jaSelecionada');
		}
	});




/*	Recepcionista - Ocupar mesa  */
	var mesaSelec, wrapSelec;
	var mesaSelecMulti = '';
	window.lugaresReserv = 0;
	$('body').on('click', '.mapa .wrapMesas:not(.escondido) .mesa.disponivel:not(.jaSelecionada)', function(){
		if ($('.resSelecionada').length > 0) {
			mesaSelec = $(this).index();
			mesaSelecMulti = mesaSelecMulti + $(this).index() + ';';
			wrapSelec = $(this).parent().index();
			if (parseInt($('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(mesaSelec).attr('lugares')) >= parseInt($('.resSelecionada .lugares span').text())) {
				$('.bgMensagem').fadeIn();
				$('.mensagemLightboxDisp').fadeIn();
				$('.msgMesa').text('Deseja ocupar essa mesa?');
			} else {
				$('.wrapMesas').addClass('escondido').css('opacity','0.1');
				$(this).parent().removeClass('escondido').addClass('destacada').css('opacity','1').append('<div class="msgOutraMesa">Escolha mais mesas para compor essa reserva.</div>');
				$(this).css('opacity','0.3').addClass('jaSelecionada');

				function MesasSuficientes() {
					window.lugaresReserv = 0;
					window.mesasSelecs = '';
					for (a=0; a < mesaSelecMulti.split(';').length - 1; a++) {
						$('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(parseInt(mesaSelecMulti.split(';')[a])).removeClass('disponivel');
						window.mesasSelecs = window.mesasSelecs + '&mesas=' + $('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(parseInt(mesaSelecMulti.split(';')[a])).attr('cod-mesa');
						var ultMesa = $('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(parseInt(mesaSelecMulti.split(';')[a])).attr('cod-mesa');
					}

					//Salva cliente
					var reservaID = $('.reservaLi.resSelecionada').attr('cod-reserva');
					var nomeSalvar = $('.reservaLi.resSelecionada .nome').text();
					var telSalvar = $('.reservaLi.resSelecionada .telefone').text();
					var lugarSalvar = $('.reservaLi.resSelecionada .lugares span').text();
					window.reservado.push([
						reservaID,		// Codigo
						nomeSalvar,		// Nome
						telSalvar,		// Telefone
						lugarSalvar,	// Lugares
						ultMesa 			// Numero Mesa
					]);
					createCookie('aguardandoClientes',JSON.stringify(window.reservado),0);

					//Retira da lista
					$('.reservaLi.resSelecionada').remove();

			    	$.ajax({
			        	type: "POST",
			        	dataType: 'HTML',
			            url: window.dominio+"/api/reserva/alocarMesa",
			            data: "token="+reservaID+window.mesasSelecs+"",
			            success: function(){
			            	console.log('Inserido com sucesso');
			            }
			        });

					//Limpa Mapa
				    $('.reservaLi').removeClass('resSelecionada');
				    $('.wrapMesas').css('opacity','1').removeClass('escondido');
				    $('.wrapMesas .mesa').css('opacity','1');
				    $('.wrapMesas .msgOutraMesa').remove();


				    //Sem Reserva
				    if ($('.reservaLi').length < 1) {
				    	$('.wrapLista').hide();
				    	$('.wrapLista').after('<div class="semFila">No momento estamos sem fila de espera. =)</div>');
				    }
				}

				//Função selecionar Outra Mesa
				window.lugaresReserv = window.lugaresReserv + parseInt($(this).attr('lugares'));

				//Verifica se mesas selecionadas já são suficientes
				if (window.lugaresReserv >= parseInt($('.resSelecionada .lugares span').text())) {
					MesasSuficientes();
				} else {
					$('body').on('click', '.destacada .mesa.disponivel', function(){
						if (window.lugaresReserv >= parseInt($('.resSelecionada .lugares span').text())) {
							MesasSuficientes();
						}
					});
				}
			}
		} else {
			mesaSelec = $(this).index();
			mesaSelecMulti = mesaSelecMulti + $(this).index() + ';';
			wrapSelec = $(this).parent().index();
			/*$('.bgMensagem').fadeIn();
			$('.mensagemLightboxDisp').fadeIn();
			$('.msgMesa').text('Deseja ocupar essa mesa?');*/
		}
	});

	// Recepcionista - Não
	$('body').on('click', '.mensagemLightboxDisp:not(.excluir) .btMesaAlerta.nao', function(){
		$('.bgMensagem').fadeOut();
		$('.mensagemLightboxDisp').fadeOut();
	});

	// Cliente recém-chamado
	if (JSON.parse(readCookie('aguardandoClientes')) != null) {
		window.reservado = JSON.parse(readCookie('aguardandoClientes'));
		$('.notificacao span').text(window.reservado.length);
	} else {
		window.reservado = [];
	}


	// Recepcionista - Sim
	$('body').on('click', '.mensagemLightboxDisp:not(.excluir) .btMesaAlerta.sim', function(){
		$('.bgMensagem').fadeOut();
		$('.mensagemLightboxDisp').fadeOut();
		var reservaID = $('.reservaLi.resSelecionada').attr('cod-reserva').toString();
		$('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(mesaSelec).attr('token', reservaID).removeClass('disponivel');
		var mesaID = $('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(mesaSelec).attr('cod-mesa');
		
		//Salva cliente
		var nomeSalvar = $('.reservaLi.resSelecionada .nome').text();
		var telSalvar = $('.reservaLi.resSelecionada .telefone').text();
		var lugarSalvar = $('.reservaLi.resSelecionada .lugares span').text();
		window.reservado.push([
			reservaID,		// Codigo
			nomeSalvar,		// Nome
			telSalvar,		// Telefone
			lugarSalvar,	// Lugares
			mesaID 			// Numero Mesa
		]);
		createCookie('aguardandoClientes',JSON.stringify(window.reservado),0);

		$('.notificacao span').text(window.reservado.length);
		//remove seleção de reserva
		$('.reservaLi.resSelecionada').remove();

		//Limpa Mapa
		$('.reservaLi').removeClass('resSelecionada');
		$('.wrapMesas').css('opacity','1').removeClass('escondido');
		$('.wrapMesas .mesa').css('opacity','1');
		$('.wrapMesas .msgOutraMesa').remove();

		/*$.ajax({
	    	type: "POST",
	        url: "php/updateMesa.php",
	        data: {'mesaID': mesaID, 'reservaID': reservaID}
	    });*/

	    //Alocar Mesa WS
    	$.ajax({
        	type: "POST",
        	dataType: 'HTML',
            url: window.dominio+"/api/reserva/alocarMesa",
            data: "token="+reservaID+"&mesas="+mesaID+"",
            success: function(){
            	console.log('Inserido com sucesso');
            }
        });

        //Notificar Usuario WS
    	$.ajax({
        	type: "POST",
        	dataType: 'HTML',
            url: window.dominio+"/api/firebase/notificaUsuario",
            data: "token="+reservaID,
            success: function(){
            	console.log('Usuario notificado com sucesso');
            }
        });




	    //Sem Reserva
	    if ($('.reservaLi').length < 1) {
	    	$('.wrapLista').hide();
	    	$('.wrapLista').after('<div class="semFila">No momento estamos sem fila de espera. =)</div>');
	    }


			
	});


/*	Remover Reserva  */
	$('body').on('click', '.reservaLi .remover', function(index) {
		console.log('heeey');
		var posLi = $(this).index();
		$('.bgMensagem').fadeIn();
		$('.mensagemLightboxDisp').fadeIn().addClass('excluir');
		$('.msgMesa').text('Deseja excluir essa reserva?');

		// Recepcionista - Não
		$('body').on('click', '.mensagemLightboxDisp.excluir .btMesaAlerta.nao', function(){
			$('.bgMensagem').fadeOut();
			$('.mensagemLightboxDisp').fadeOut();
		});
		
		$('body').on('click', '.mensagemLightboxDisp.excluir .btMesaAlerta.sim', function(){
			var tokenReserva = $('.reservaLi.resSelecionada').attr('cod-reserva');
			$('.reservaLi.resSelecionada').remove();
			$('.bgMensagem').fadeOut();
			$('.mensagemLightboxDisp').fadeOut();
			$.ajax({
		    	type: "POST",
		        url: "php/deleteReserva.php",
		        data: {'tokenReserva': tokenReserva},
		        complete: function() {
		        	if ($('.reservaLi').length < 1) {
		        		$('.wrapLista').hide();
		        		$('.wrapLista').after('<div class="semFila">No momento estamos sem fila de espera. =)</div>');
		        	}
		        }
		    });
		});
	});

/* Garçom - Liberar mesa  */
		var mesaSelecLib, wrapSelec;
		$('body').on('click', '.mapa .wrapMesas:not(.escondido) .mesa:not(.disponivel)', function(){
			mesaSelecLib = $(this).index();
			wrapSelec = $(this).parent().index();
			$('.bgMensagem').fadeIn();
			$('.mensagemLightbox').fadeIn();
			$('.msgMesa').text('Deseja liberar essa mesa?');
		});

	// Garçom - Não
		$('body').on('click', '.mensagemLightbox .btMesaAlerta.nao', function(){
			$('.bgMensagem').fadeOut();
			$('.mensagemLightbox').fadeOut();
		});

	// Garçom - Sim
		$('body').on('click', '.mensagemLightbox .btMesaAlerta.sim', function(){
			$('.bgMensagem').fadeOut();
			$('.mensagemLightbox').fadeOut();
			$('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(mesaSelecLib).addClass('disponivel');
			var mesaID = $('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(mesaSelecLib).attr('cod-mesa');
			var tokenMesa = $('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(mesaSelecLib).attr('token');
			$('.mapa .wrapMesas:eq('+wrapSelec+') .mesa').eq(mesaSelecLib).attr('token', '');
			/*$.ajax({
		    	type: "POST",
		        url: "php/liberarMesa.php",
		        data: {'mesaID': mesaID}
		    });*/

	    	$.ajax({
	        	type: "POST",
	        	dataType: 'HTML',
	            url: window.dominio+"/api/reserva/liberarMesa",
	            data: "token="+tokenMesa,
	            success: function(){
	            	console.log('Liberada com sucesso');
	            }
	        });
		});




//Lightbox
$('body').on('click', '.fade_aguardo', function(){
	$('.fade_aguardo, .lista_aguardo').fadeOut();
	$('.notificacao span').text(window.reservado.length);
});

$('body').on('click', '.notificacao', function(){
	//Preenche lightbox
	if ($('.notificacao span').text() != "0") {
		$('.lista_aguardo .lista li').remove();
		for (var i=0; i< window.reservado.length; i++) {
			$('.lista_aguardo .lista').append(
				'<li class="reservaLi" cod="'+i+'">'+
					'<div class="lugares">Mesa<span>'+window.reservado[i][4]+'</span></div>'+
					'<div class="wrapRound">'+
						'<div class="nome">'+window.reservado[i][1]+'</div>'+
						'<div class="telefone">'+window.reservado[i][2]+'</div>'+
					'</div>'+
					'<div class="chegou">Chegou</div>'+
				'</li>'
			);
		}
		$('.fade_aguardo, .lista_aguardo').fadeIn();
	}
});

$('body').on('click', '.lista_aguardo .chegou', function(){
	var codigo = $(this).parent().attr('cod');
	window.reservado.splice(codigo,1);
	createCookie('aguardandoClientes',JSON.stringify(window.reservado),0);
	$('.notificacao span').text(window.reservado.length);
	$('.fade_aguardo, .lista_aguardo').fadeOut();
});



//Função de Máscara de campos
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a){var b,c=navigator.userAgent,d=/iphone/i.test(c),e=/chrome/i.test(c),f=/android/i.test(c);a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},autoclear:!0,dataName:"rawMaskFn",placeholder:"_"},a.fn.extend({caret:function(a,b){var c;if(0!==this.length&&!this.is(":hidden"))return"number"==typeof a?(b="number"==typeof b?b:a,this.each(function(){this.setSelectionRange?this.setSelectionRange(a,b):this.createTextRange&&(c=this.createTextRange(),c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select())})):(this[0].setSelectionRange?(a=this[0].selectionStart,b=this[0].selectionEnd):document.selection&&document.selection.createRange&&(c=document.selection.createRange(),a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length),{begin:a,end:b})},unmask:function(){return this.trigger("unmask")},mask:function(c,g){var h,i,j,k,l,m,n,o;if(!c&&this.length>0){h=a(this[0]);var p=h.data(a.mask.dataName);return p?p():void 0}return g=a.extend({autoclear:a.mask.autoclear,placeholder:a.mask.placeholder,completed:null},g),i=a.mask.definitions,j=[],k=n=c.length,l=null,a.each(c.split(""),function(a,b){"?"==b?(n--,k=a):i[b]?(j.push(new RegExp(i[b])),null===l&&(l=j.length-1),k>a&&(m=j.length-1)):j.push(null)}),this.trigger("unmask").each(function(){function h(){if(g.completed){for(var a=l;m>=a;a++)if(j[a]&&C[a]===p(a))return;g.completed.call(B)}}function p(a){return g.placeholder.charAt(a<g.placeholder.length?a:0)}function q(a){for(;++a<n&&!j[a];);return a}function r(a){for(;--a>=0&&!j[a];);return a}function s(a,b){var c,d;if(!(0>a)){for(c=a,d=q(b);n>c;c++)if(j[c]){if(!(n>d&&j[c].test(C[d])))break;C[c]=C[d],C[d]=p(d),d=q(d)}z(),B.caret(Math.max(l,a))}}function t(a){var b,c,d,e;for(b=a,c=p(a);n>b;b++)if(j[b]){if(d=q(b),e=C[b],C[b]=c,!(n>d&&j[d].test(e)))break;c=e}}function u(){var a=B.val(),b=B.caret();if(o&&o.length&&o.length>a.length){for(A(!0);b.begin>0&&!j[b.begin-1];)b.begin--;if(0===b.begin)for(;b.begin<l&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}else{for(A(!0);b.begin<n&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}h()}function v(){A(),B.val()!=E&&B.change()}function w(a){if(!B.prop("readonly")){var b,c,e,f=a.which||a.keyCode;o=B.val(),8===f||46===f||d&&127===f?(b=B.caret(),c=b.begin,e=b.end,e-c===0&&(c=46!==f?r(c):e=q(c-1),e=46===f?q(e):e),y(c,e),s(c,e-1),a.preventDefault()):13===f?v.call(this,a):27===f&&(B.val(E),B.caret(0,A()),a.preventDefault())}}function x(b){if(!B.prop("readonly")){var c,d,e,g=b.which||b.keyCode,i=B.caret();if(!(b.ctrlKey||b.altKey||b.metaKey||32>g)&&g&&13!==g){if(i.end-i.begin!==0&&(y(i.begin,i.end),s(i.begin,i.end-1)),c=q(i.begin-1),n>c&&(d=String.fromCharCode(g),j[c].test(d))){if(t(c),C[c]=d,z(),e=q(c),f){var k=function(){a.proxy(a.fn.caret,B,e)()};setTimeout(k,0)}else B.caret(e);i.begin<=m&&h()}b.preventDefault()}}}function y(a,b){var c;for(c=a;b>c&&n>c;c++)j[c]&&(C[c]=p(c))}function z(){B.val(C.join(""))}function A(a){var b,c,d,e=B.val(),f=-1;for(b=0,d=0;n>b;b++)if(j[b]){for(C[b]=p(b);d++<e.length;)if(c=e.charAt(d-1),j[b].test(c)){C[b]=c,f=b;break}if(d>e.length){y(b+1,n);break}}else C[b]===e.charAt(d)&&d++,k>b&&(f=b);return a?z():k>f+1?g.autoclear||C.join("")===D?(B.val()&&B.val(""),y(0,n)):z():(z(),B.val(B.val().substring(0,f+1))),k?b:l}var B=a(this),C=a.map(c.split(""),function(a,b){return"?"!=a?i[a]?p(b):a:void 0}),D=C.join(""),E=B.val();B.data(a.mask.dataName,function(){return a.map(C,function(a,b){return j[b]&&a!=p(b)?a:null}).join("")}),B.one("unmask",function(){B.off(".mask").removeData(a.mask.dataName)}).on("focus.mask",function(){if(!B.prop("readonly")){clearTimeout(b);var a;E=B.val(),a=A(),b=setTimeout(function(){B.get(0)===document.activeElement&&(z(),a==c.replace("?","").length?B.caret(0,a):B.caret(a))},10)}}).on("blur.mask",v).on("keydown.mask",w).on("keypress.mask",x).on("input.mask paste.mask",function(){B.prop("readonly")||setTimeout(function(){var a=A(!0);B.caret(a),h()},0)}),e&&f&&B.off("input.mask").on("input.mask",u),A()})}})});


//Função - Valida CPF
function CPF(){"user_strict";function r(r){for(var t=null,n=0;9>n;++n)t+=r.toString().charAt(n)*(10-n);var i=t%11;return i=2>i?0:11-i}function t(r){for(var t=null,n=0;10>n;++n)t+=r.toString().charAt(n)*(11-n);var i=t%11;return i=2>i?0:11-i}var n=false,i=true;this.gera=function(){for(var n="",i=0;9>i;++i)n+=Math.floor(9*Math.random())+"";var o=r(n),a=n+"-"+o+t(n+""+o);return a},this.valida=function(o){for(var a=o.replace(/\D/g,""),u=a.substring(0,9),f=a.substring(9,11),v=0;10>v;v++)if(""+u+f==""+v+v+v+v+v+v+v+v+v+v+v)return n;var c=r(u),e=t(u+""+c);return f.toString()===c.toString()+e.toString()?i:n}}

window.CPF = new CPF();


});