
/* Eventos */


	// NOVO

		// cronometro
		function cronometro(){

			$.each($_LOTES, function($key, $val) {
				if($val){

					$data_fim = new Date($val.ano, $val.mes-1, $val.dia, $val.hora, $val.min, $val.seg, 0);
					$seg1 = $data_fim.getTime();

					$today = new Date();
					$today.setMilliseconds(0);
					$seg2 = $today.getTime();

					$segs = $seg1 - $seg2;
					$tempo = new Date($segs);
					$tempo.setMilliseconds(0);

					var $return = {dias: 0, hora: '00', min: '00', seg: '00', hora_total: '00', seg_total: '00'};
					if($segs > 0){
						// Segundos
						$data_s = $tempo.getSeconds();
						$return['seg'] = $data_s<10 ? '0'+$data_s : $data_s;

						// Minutos
						$data_i = $tempo.getMinutes();
						$return['min'] = $data_i<10 ? '0'+$data_i : $data_i;

						// Horas
						$data_h = $segs - (($data_s*1000)+($data_i*60*1000));
						for (var $i = $data_h; $i >= (86400*1000);) {
							$i = $i - (86400*1000);
						}
						$data_h = parseInt($i/(60*60*1000));
						$return['hora'] = $data_h<10 ? '0'+$data_h : $data_h;

						// Dias
						$seg_d = ($data_h*60*60)+($data_i*60)+$data_s;
						$data_d = ($segs-(86400*1000)) > 0 ? parseInt(($segs-$seg_d)/(86400*1000)) : 0;
						$return['dias'] = $data_d<10 ? '0'+$data_d : $data_d;

						// Horas Total
						$data_ht = (($data_d*24)+$data_h);
						$return['hora_total'] = $data_ht<10 ? '0'+$data_ht : $data_ht;

						$return['seg_total'] = $segs/1000;

					}


					if($return['dias'] > 0){
						$(".LL_box_"+$key+" .LL_cronometro .LL_dias").show().find("span").html($return['dias']);
					} else {
						$(".LL_box_"+$key+" .LL_cronometro .LL_dias").hide().find("span").html("");
					}
					$(".LL_box_"+$key+" .LL_cronometro .LL_hora span").html($return['hora']);
					$(".LL_box_"+$key+" .LL_cronometro .LL_min span").html($return['min']);
					$(".LL_box_"+$key+" .LL_cronometro .LL_seg span").html($return['seg']);

				}
			});

			setTimeout(function(){ cronometro(); }, 1000);
		}

		function cronometro_tempo(){

			$today = new Date();
			//$today.setMilliseconds(0);

			$dia = $today.getDate();
			$mes = mes($today.getMonth());
			$ano = $today.getFullYear();
			$hora = $today.getHours() < 10 ? '0'+$today.getHours() : $today.getHours();
			$min = $today.getMinutes() < 10 ? '0'+$today.getMinutes() : $today.getMinutes();
			$seg = $today.getSeconds() < 10 ? '0'+$today.getSeconds() : $today.getSeconds();

			$(".cronometro_tempo").html($dia+' de '+$mes+' de '+$ano+' - '+$hora+':'+$min+':'+$seg);

			setTimeout(function(){ cronometro_tempo(); }, 1000);

		}





		// ATUALIZAR
			// LEILOES
				function atualizar_leiloes($leiloes, $lotes, $lote, $acao_dar_lance=0){
					$.ajax({
						type: "POST",
						url: DIR+"/app/Ajax/Leiloes/atualizar_leiloes.php",
						data: { leiloes: $leiloes, lotes: $lotes, lote: $lote },
						dataType: "json",
						success: function($json){
							$(".carregando").hide();
							$(".carregando_lote").hide();

							console.log($json);

							if($json.item){
								$.each($json.item, function($key, $val) {
									LL_info($key, $val, $acao_dar_lance);
									if($lote){
										$_LOTES[$key] = $json.item[$key]['cronometro_atual']['data'];
										LL_info_lote($key, $val);
									}
								});
								if(!$acao_dar_lance){
									cronometro_leiloes($leiloes, $lotes, $lote);
								}
							}
						}
					});
				}
				function cronometro_leiloes($leiloes, $lotes, $lote){
					$today = new Date();
					$seg = $today.getSeconds();
					$pg_home_e_lotes = (!($seg%5) || $seg==59 || $seg==0 || $seg==1);
					$pg_lote = $lote ? 1 : 0;
					if( $pg_home_e_lotes || $pg_lote){
						setTimeout(function(){ atualizar_leiloes($leiloes, $lotes, $lote); }, 1000);
					} else {
						setTimeout(function(){ cronometro_leiloes($leiloes, $lotes, $lote); }, 1000);
					}
				}
			// LEILOES

			// INFORMACOES
				function LL_info($key, $val, $acao_dar_lance){
					if($acao_dar_lance){
						if($val['cronometro_atual']['dias'] > 0){
							$(".LL_box_"+$key+" .LL_cronometro .LL_dias").show().find("span").html($val['cronometro_atual']['dias']);
						} else {
							$(".LL_box_"+$key+" .LL_cronometro .LL_dias").hide().find("span").html("");
						}
						$(".LL_box_"+$key+" .LL_cronometro .LL_hora span").html($val['cronometro_atual']['hora']);
						$(".LL_box_"+$key+" .LL_cronometro .LL_min span").html($val['cronometro_atual']['min']);
						$(".LL_box_"+$key+" .LL_cronometro .LL_seg span").html($val['cronometro_atual']['seg']);
					}

					//Diogo teste de detalhes
					$(".LL_box_"+$key+" .quartos").html($val.quartos);
					$(".LL_box_"+$key+" .suites").html($val.suites);
					$(".LL_box_"+$key+" .vagas").html($val.vagas);
					$(".LL_box_"+$key+" .area_privativa").html($val.area_privativa + ' m²');
					$(".LL_box_"+$key+" .LL_fase_da_obra .fase_da_obra").html($val.fase_da_obra);

					// Infos
						$(".LL_box_"+$key+" .LL_nome").html($val.nome);
						$(".LL_box_"+$key+" .LL_codigo").html($val.codigo);
						$(".LL_box_"+$key+" .LL_local").html($val.local);

						$(".LL_box_"+$key+" .LL_natureza").html($val.natureza).css('background', $val.natureza_cor);
						$(".LL_box_"+$key+" .LL_tipos").html($val.tipos);

						$(".LL_box_"+$key+" .LL_count").html($val.count);
						$(".LL_box_"+$key+" .LL_count_lances").html($val.count_lances);

						if($val.count_lotes){
							$(".LL_box_"+$key+" .LL_count_lotes").show().find('b').html($val.count_lotes);						
						}

						if($val.lance){
							$(".LL_box_"+$key+" .LL_lance_ini").show().find('b').html($val.lance.ini);
							$(".LL_box_"+$key+" .LL_lance_min").show().find('b').html($val.lance.min);
							$(".LL_box_"+$key+" .LL_lance_atual").show().find('b').html($val.lance.atual);
						}

						if($val.lote_atual!=$key){
							$(".LL_box_"+$key+" .LL_sincronizar").show().attr('href', DIR+'/lote/-/'+$val.lote_atual+'?sincronizar=1');
						} else {
							$(".LL_box_"+$key+" .LL_sincronizar").hide().attr('href', '');
						}
					// Infos

					// Infos Arrematante
						if($val.lances_data && $val.lances_data!='30/11/1999 00:00'){
							$(".LL_box_"+$key+" .LL_lances_data").html($val.lances_data);
						}
						if($val.lances_cadastro){
							$(".LL_box_"+$key+" .LL_lances_cadastro").html(langg('Usuário')+': '+$val.lances_cadastro);
						} else if($val.lances_plaquetas!=00){
							$(".LL_box_"+$key+" .LL_lances_cadastro").html(langg('Nº Plaqueta')+': '+$val.lances_plaquetas);
						} else {
							$(".LL_box_"+$key+" .LL_lances_data").html('');
							$(".LL_box_"+$key+" .LL_lances_cadastro").html('');
						}
					// Infos Arrematante

					// Info Praca e datas
						$(".LL_box_"+$key+" .LL_data_ini").show().find('data').html($val.data.ini).parent().find('hora').html($val.data.hora_ini);
						$(".LL_box_"+$key+" .LL_data_fim").show().find('data').html($val.data.fim).parent().find('hora').html($val.data.hora_fim);

						$(".LL_box_"+$key+" .LL_praca_info").html($val.praca_info);
						if($val.praca > 0){
							$(".LL_box_"+$key+" .LL_praca1").show().find('.dataini').html($val.praca1.data.ini);
							if($val.praca2){
								$(".LL_box_"+$key+" .LL_praca2").show().find('.dataini').html($val.praca2.data.ini).parent().find('.datafim').html($val.praca2.data.fim);							
								if(!$val.praca2.data.ini){
									$(".LL_box_"+$key+" .LL_praca2").hide();
								}
							} else {
								$(".LL_box_"+$key+" .LL_praca1").find('span').html(langg('Início'));
								$(".LL_box_"+$key+" .LL_praca1 .datafim").show().find('.datafim').html($val.praca1.data.fim);
								$(".LL_box_"+$key+" .LL_praca2").hide();
							}
						} else {
							$(".LL_box_"+$key+" .LL_data_hora_ini").show().find('data').html($val.data.ini+' às '+$val.data.hora_ini);
							$(".LL_box_"+$key+" .LL_data_hora_fim").show().find('data').html($val.data.fim+' às '+$val.data.hora_fim);
						}
					// Info Praca e datas

					// Info situacao
						if($val.situacao == 0){
							$(".LL_box_"+$key+" .LL_situacao").attr('situacao', 'em_breve').find("p").html(langg('Em Breve'));
						} else if($val.situacao == 1){
							$(".LL_box_"+$key+" .LL_situacao").attr('situacao', 'aberto').find("p").html(langg('Aberto'));
						} else if($val.situacao == 2){
							$(".LL_box_"+$key+" .LL_situacao").attr('situacao', 'arrematado').find("p").html(langg('Arrematado'));
						} else if($val.situacao == 3){
							$(".LL_box_"+$key+" .LL_situacao").attr('situacao', 'nao_arrematado').find("p").html(langg('Não Arrematado'));
						} else if($val.situacao == 10){
							$(".LL_box_"+$key+" .LL_situacao").attr('situacao', 'condicional').find("p").html(langg('Leilão Condicional'));
						} else if($val.situacao == 20){
							$(".LL_box_"+$key+" .LL_situacao").attr('situacao', 'venda_direta').find("p").html(lang('Venda Direta'));
						}
					// Info situacao
				}
				function LL_info_lote($key, $val){
					$(".LL_box_"+$key+" .LL_cronometro").show();
					$(".LL_box_"+$key+" .LL_cronometro_tempo").show();
					$(".LL_box_"+$key+" .LL_lance_atual").parent().show();
					$(".LL_box_"+$key+" .LL_lance_atual span").html('Lance Atual:');

					if($val.situacao == 0){
						$(".LL_box_"+$key+" .LL_cronometro_info").html(langg('Leilão Começa em'));
					} else if($val.situacao == 1){
						$(".LL_box_"+$key+" .LL_cronometro_info").html(langg('Leilão Encerra em'));
					} else if($val.situacao == 2){
						$(".LL_box_"+$key+" .LL_cronometro_info").html('<b class="fz20 pt16 pb16">'+langg('Leilão Arrematado')+'<b>');
						$(".LL_box_"+$key+" .LL_cronometro_tempo").hide();
					} else if($val.situacao == 3){
						$(".LL_box_"+$key+" .LL_cronometro_info").html('<b class="fz20 pt16 pb16">'+langg('Leilão Não Arrematado')+'<b>');
						$(".LL_box_"+$key+" .LL_cronometro_tempo").hide();
					} else if($val.situacao == 10){
						$(".LL_box_"+$key+" .LL_cronometro_info").html('<b class="fz20 pt16 pb16">'+langg('Leilão Condicional')+'<b>');
						$(".LL_box_"+$key+" .LL_cronometro_tempo").hide();
					} else if($val.situacao == 20){
						$(".LL_box_"+$key+" .LL_cronometro").hide()
						$(".LL_box_"+$key+" .LL_cronometro_info").html('');
						$(".LL_box_"+$key+" .LL_cronometro_tempo").hide();
						$(".LL_box_"+$key+" .LL_cronometro_info").html('');
						if($val.lance.min==''){
							$(".LL_box_"+$key+" .LL_lance_atual").parent().hide();
						} else {
							$(".LL_box_"+$key+" .LL_lance_atual span").html(langg('Lance Mínimo')+':');
							$(".LL_box_"+$key+" .LL_lance_atual b").html($val.lance.min);
						}
					}
				}
			// INFORMACOES
		// ATUALIZAR


		// Habilitar Para Marticipar do Leilao
			function habilitar_leilao($id,$lote){
				$.ajax({
					type: "POST",
					url: DIR+"/app/Ajax/Leiloes/habilitar_leilao.php",
					data: { id: $id,lote:$lote },
					dataType: "json",
					success: function($json){
						$(".carregando").hide();
						if($json.evento != null){
							eval($json.evento);
						}
						if($json.erro != null){
							$.each($json.erro, function($key, $val) {
								alerts(0, $val, 1);
							});	
						} else {
							alerts(1, langg('Habilitado com Sucesso!'))
							$(".LL_habilitar_leilao").hide();
						}
					}
				});
			}
		// Habilitar Para Marticipar do Leilao

		// Habilitar Para Marticipar do Lote Sucata
			function habilitar_lote_sucata($id){
				$.ajax({
					type: "POST",
					url: DIR+"/app/Ajax/Leiloes/habilitar_leilao.php",
					data: { id: $id, sucata: 1 },
					dataType: "json",
					success: function($json){
						$(".carregando").hide();
						if($json.evento != null){
							eval($json.evento);
						}
						if($json.erro != null){
							$.each($json.erro, function($key, $val) {
								alerts(0, $val, 1);
							});	
						} else {
							alerts(1, langg('Habilitado com Sucesso!'))
							$(".LL_habilitar_lote_sucata").hide();
						}
					}
				});
			}
		// Habilitar Para Marticipar do Lote Sucata

		// Dar Lance
		function dar_lance($id, e){
			$lance = $(e).find('input[name="lance"]').val();
			$lance_mais = $(e).find('input[name="lance_mais"]').val();
			$.ajax({
				type: "POST",
				url: DIR+"/app/Ajax/Leiloes/dar_lance.php",
				data: { id: $id, lance: $lance, lance_mais: $lance_mais },
				dataType: "json",
				beforeSend: function(){ ajaxIni(0); },
				success: function($json){
					$(".carregando").hide();
					if($json.evento != null){
						eval($json.evento);
					}
					if($json.erro != null){
						$.each($json.erro, function($key, $val) {
							alerts(0, $val, 1);
						});	
					} else {
						alerts(1, langg('Lance Efetuado com Sucesso!'));
						$('input[name="lance"]').val('');
						atualizar_leiloes('', '', $id, 1);
						setTimeout(function(){ historico($id) }, 1000);
					}
				}
			});
		}


		// historico
			function historico($id, $acao_dar_lance=0){
				$.ajax({
					type: "POST",
					url: DIR+"/app/Ajax/Leiloes/historico.php",
					data: { id: $id },
					dataType: "json",
					success: function($json){
						$(".LL_historico").html($json.html);

						if(!$acao_dar_lance){
							setTimeout(function(){ historico($id) }, 10000);
						}
					}
				});
			}


		// Leilao interno box informacoes
		function box_leiloes_info($n, e){
			$('.box__').hide();
			$('.box__'+$n).show();

			$('.box_').removeClass('back_F5F5F5');
			$(e).addClass('back_F5F5F5');
		}
		function box_leiloes_info_ini($n, e){
			setTimeout(function(){
				$('.box__').hide();
				$('.box__1').show();
			}, 1000);
		}

		// FAQ
	    function faq(e){
	    	$('#faq ul li > div').removeClass('ativo');
	    	$(e).addClass('ativo');
		}

		// Lotes List, Galeria
	    function lotes_list_galeria($list_galeria, e){
	    	$('.lotes_list_galeria').hide();
	    	$('.lotes_list_galeria.'+$list_galeria).show();

	    	$(e).parent().find('li').addClass('cor_ccc').addClass('bdb_ccc');
	    	$(e).parent().find('li.'+$list_galeria).removeClass('cor_ccc').removeClass('bdb_ccc');
		}

	// NOVO

	// ------------------------------------------------------------------------

	// RESPONSIVO
		$(document).ready(function() {
			$cor = $("ul.menu_resp").attr('cor');
			$bd = $("ul.menu_resp").attr('bd');
			$back = $("ul.menu_resp").attr('back');

			$html  = '<li> ';
			$html += '	<a class="flr fz20 pt8 pb10 pl10 pr10 ml10 mr10 cor_'+$cor+' bdw3 bd_'+$bd+' back_'+$back+'"><i class="fa fa-navicon (alias)"></i></a> ';
			$html += '	<div class="h5 clear"></div> ';
			$html += '	<ul class="dn w100p posa l0 z7 p10 pb10 fz16 ts back_'+$back+'"> ';

				$x=0;
				$("ul.menu li a").each(function() { $x++;
					$nome = $(this).html();
					$href = $(this).attr('href');
					$submenu = '';
					if($(this).parent().parent().is('ul.submenu'))
						$submenu = '&nbsp;&raquo;&nbsp;';
					$bd_menu = $x!=1 ? 'bdt_'+$bd : '';
					$html += '<li><a href="'+$href+'" class="limit db p10 cor_'+$cor+' '+$bd_menu+' ">'+$submenu+$nome+'</a></li> ';
				});

			$html += '		<div class="clear"></div> ';
			$html += '	</ul> ';
			$html += '	<div class="clear"></div> ';
			$html += '</li> ';

			$("ul.menu_resp").html($html);
		});

	// RESPONSIVO



	// ------------------------------------------------------------------------



	// FOOTER
		$(window).scroll(function(){
			if($(window).scrollTop() > 200){
				$("footer .seta").fadeIn();
			} else  {
				$("footer .seta").fadeOut();
			}
		});
		$(document).ready(function() {
			$('footer .seta').on('click', function() {
				$("html,body").animate( {scrollTop: $("html").offset().top}, "slow" );
			});
		});
	// FOOTER



	// ------------------------------------------------------------------------



	// VERIFICACOES
		$(document).ready(function() {

			// Cadastro Online
			$.ajax({
				type: "POST",
				url: DIR+"/app/Ajax/Verificacoes/cadastro_online.php",
				data: '',
				dataType: "json",
				success: function($json){
				}
			});


		});
	// VERIFICACOES



	// ------------------------------------------------------------------------



	// PLUGINS SITE
		$(document).ready(function (){

			// DataTable
			$(".datatable").each(function() {
				var oTable = $(".datatable").DataTable({
					"order": [ [1, 'desc'] ],
					"iDisplayLength" : 25,
					"sPaginationType": "full_numbers",
	        		"processing": true,
				});
			});

			// Animated
			$("[animated_ini]").each(function() {
				$(this).addClass('animated_ini');
				$(this).wrap("<div></div>");
				animated_on('ini', 1);
			});

			$x=0;
			var $animated = [];
			$("[animated]").each(function() { $x++;
				$(this).addClass('animated_'+$x);
				$(this).wrap("<div></div>");
			});
			$(window).scroll(function(){
				for (var $i=1; $i<=$('[animated]').length; $i++) {
					animated_scroll($i)
				};
			});
			function animated_scroll($n){
				$altura_tela = $(window).scrollTop() + $(window).height();
				// ON
				if($('.animated_'+$n).attr('animated_status')==null || $('.animated_'+$n).attr('animated_status')==0){
					if($altura_tela > ($('.animated_'+$n).parent().offset().top + 240) ){
						animated_on($n);
					}

				// OFF
				} else if($('.animated_'+$n).attr('animated_status')==2){
					if($altura_tela < ($('.animated_'+$n).parent().offset().top + 230) ){
						animated_off($n);
					}
				}
			}
			function animated_on($n, $tipo){
				tirar_efeitos_atuais($('.animated_'+$n));
				$efeito = efeitosIn($('.animated_'+$n), $tipo);
				shuffle($efeito);
				$('.animated_'+$n).removeClass('animated').parent().css('overflow', 'hidden');
				setTimeout(function(){
					$('.animated_'+$n).addClass('animated '+$efeito[0]).css('opacity', 1);
					setTimeout(function(){ $('.animated_'+$n).parent().css('overflow', ''); }, 1500);
				}, 0.5);
				if($('.animated_'+$n).attr('animated_loop'))
					$('.animated_'+$n).attr('animated_status', 2)
				else
					$('.animated_'+$n).attr('animated_status', 1);
			}
			function animated_off($n){
				tirar_efeitos_atuais($('.animated_'+$n));
				$efeito = efeitosOut($('.animated_'+$n), $tipo);
				shuffle($efeito);
				$.each($efeito, function($key, $val) {
					$('.animated_'+$n).removeClass($val);
				});	
				$('.animated_'+$n).removeClass('animated').parent().css('overflow', 'hidden');
				setTimeout(function(){
					$('.animated_'+$n).addClass('animated '+$efeito[0]).css('opacity', '');
					setTimeout(function(){ $('.animated_'+$n).parent(); }, 1500);
				}, 0.5);
				$('.animated_'+$n).attr('animated_status', 0)
			}

			function efeitosIn($e, $tipo){
				if($tipo==1)
					return $e.attr('efeito') ? [$e.attr('efeito')] : ['fadeIn'];
				else
					return $e.attr('efeito') ? [$e.attr('efeito')] : ['fadeIn'];

				//return $e.attr('efeito') ? [$e.attr('efeito')] : ['fadeIn', 'lightSpeedIn', 'zoomIn', 'zoomInUp']; // flipInX
			}
			function efeitosOut($e, $tipo){
				return $e.attr('efeito') ? [$e.attr('efeito')] : ['fadeOut', 'fadeOutUpBig', 'lightSpeedOut', 'zoomOut', 'zoomOutUp']; // flipOutX
			}
			function tirar_efeitos_atuais($e){
				$.each(efeitosIn($e), function($key, $val) {
					$e.removeClass($val);
				});	
				$.each(efeitosOut($e), function($key, $val) {
					$e.removeClass($val);
				});	
			}

		});
	// PLUGINS SITE



/* Eventos */
