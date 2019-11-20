
/* Mobile */


	// FUNCTION

	    // Mobile
		function mobile($url, $data){
			$.ajax({
				type: "POST",
				url: DIR+"/app/Ajax/Mobile/"+$url+".php",
				data: $data ? $data : '',
				dataType: "json",
				beforeSend: function(){ ajaxIni(); },
				error: function($request, $error){ ajaxErro($request, $error); },
				success: function($json){
					$(".carregando").hide();
					mobile_menu();
					if($json.alert && $json.alert!='z'){
						if($json.alert==1)		alerts(1);
						else if($json.alert)	alerts(1, $json.alert);
						else					alerts(0);
					} else if($json.erro){
						$.each($json.erro, function($key, $val) {
							alerts(0, $val, 1);
						});	
					} else {
						$('section.mobile').stop(true, true).hide();
						$.each($json['html'], function(key, val) {
							$('section.mobile#'+$url+' .'+key).html(val);
						});
						if($json.body)
							$('body').css('background', $json.body);
						else
							$('body').css('background', '#fff');

						$('section.mobile#'+$url).stop(true, true).fadeIn();
					}
					if($json.evento!=undefined)
						eval($json.evento);

					if($json.direcionar!=undefined)
						alert(direcionar);

					topoo();
				}
			});
		}


	    // Mobile Menu
		function mobile_menu(){
			$.ajax({
				type: "POST",
				url: DIR+"/app/Ajax/Mobile/menu.php",
				data: '',
				dataType: "json",
				error: function($request, $error){ ajaxErro($request, $error); },
				success: function($json){
					$('section.mobile .menu_principal').html($json.html);
					criar_css();
				}
			});
		}


	    // Mobile Abrir Menu
		function mobile_abrir_menu(){
			$('section.mobile .menu_principal .fundo_videos').slideToggle();
		}

	// FUNCTION


/* Mobile */
