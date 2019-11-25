<?

	class Controllers extends Mysql {



		// INCLUDES

			public function includes(){

				// CORES CSS
					define('LARANJA', '153C89');
				// CORES CSS

				// Topo
					$this->colunas = 'id';
					$this->filtro = " WHERE ".STATUS." AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS." ".SITUACAO.") ORDER BY data_ini asc, ".ORDER." ";
					$dados['total_leiloes'] = $this->read('leiloes');

					$this->colunas = 'id';
					$this->filtro = "  WHERE ".STATUS." ".SITUACAO." ORDER BY data_ini asc, ".ORDER." ";
					$dados['total_lotes'] = $this->read('lotes');

					$this->filtro = " WHERE ".STATUS." ORDER BY ".ORDER." ";
					$dados['topo_frases'] = $this->read('frases');


					// Busca Refinada
					$dados['topo_status'][] = (object)array('id'=>0, 'nome'=>lang('Em Loteamento'));
					$dados['topo_status'][] = (object)array('id'=>1, 'nome'=>lang('Aberto'));
					$dados['topo_status'][] = (object)array('id'=>2, 'nome'=>lang('Arrematado'));
					$dados['topo_status'][] = (object)array('id'=>3, 'nome'=>lang('Não Arrematado'));
					$dados['topo_status'][] = (object)array('id'=>10, 'nome'=>lang('Em Condicional'));

					$this->colunas = 'id, nome';
					$this->filtro = " WHERE ".STATUS." ORDER BY ".ORDER." ";
					$dados['topo_tipos'] = $this->read('tipos');

					$this->colunas = 'id, nome';
					$this->filtro = " WHERE ".STATUS." ORDER BY ".ORDER." ";
					$dados['topo_natureza'] = $this->read('natureza');

					$this->colunas = 'id, nome, cor';
					$this->filtro = " WHERE ".STATUS." AND star = 1 ORDER BY ".ORDER." ";
					$dados['topo_lotes1_cate_star'] = $this->read('lotes1_cate');

					$this->colunas = 'id, nome';
					$this->filtro = " WHERE ".STATUS." AND `tipo` = 0 ORDER BY nome asc, ".ORDER." ";
					$dados['topo_lotes1_cate'] = $this->read('lotes1_cate');
					foreach($dados['topo_lotes1_cate'] as $value){
						$this->colunas = 'id, nome';
						$this->prepare = array($value->id);
						$this->filtro = " WHERE ".STATUS." AND `subcategorias` = ? ORDER BY nome asc, ".ORDER." ";
						$dados['topo_lotes1_cate1'][$value->id] = $this->read('lotes1_cate');
						foreach($dados['topo_lotes1_cate1'][$value->id] as $value1){
							$this->colunas = 'id, nome';
							$this->prepare = array($value1->id);
							$this->filtro = " WHERE ".STATUS." AND `subcategorias` = ? ORDER BY ".ORDER." ";
							$dados['topo_lotes1_cate2'][$value1->id] = $this->read('lotes1_cate');
						}
					}

					$this->colunas = 'cidades, estados';
					$this->filtro = " WHERE ".STATUS." AND cidades != '' GROUP BY cidades ORDER BY estados asc, ".ORDER." ";
					$dados['topo_cidades'] = $this->read('lotes');
					foreach ($dados['topo_cidades'] as $key => $value) {
						$dados['topo_estados'][$value->estados][$key] = $value->cidades;
					}
					// Busca Refinada
				// Topo


				// Footer
					$this->filtro = " WHERE ".STATUS." ORDER BY ".ORDER." ";
					$dados['footer_servicos'] = $this->read('servicos');
				// Footer


				// Padrao
					$this->filtro = " WHERE `tipo` = 'emails' ";
					$dados['emails'] = $this->read_unico('configs');

					$this->filtro = " WHERE  `tipo` = 'informacoes' AND lang = '".LANG."' ";
					$dados['info'] = $dados['informacoes'] = $this->read_unico('configs');

					if(isset($_SESSION['x_site']->id)){
						$this->prepare = array($_SESSION['x_site']->id);
						$this->filtro = " WHERE `id` = ? ";
						$dados['cadastro_pd'] = $this->read_unico('cadastro');
					}

					$textos = $this->read('textos');
					foreach ($textos as $key => $value){
						$dados['textos'][$value->id] = $value;
					}
					$this->filtro = " WHERE ".STATUS." ORDER BY ".ORDER." ";
					$dados['paginas'] = $this->read('paginas');

					$this->filtro = " ORDER BY `lugar` DESC";
					$banner = $this->read_unico('banner');
					if(isset($banner->lugar)){
						for ($i=1; $i <= $banner->lugar; $i++) { 
							$this->prepare = array($i);
							$this->filtro = " WHERE ".STATUS." AND `lugar` = ? ORDER BY `ordem` ASC, `id` DESC ";
							$dados['banner'][$i] = $this->read('banner');
						}
					}

					// Todas da informacoes do carrinho
					//$dados = carrinho_dados($dados);
					//$dados = cotacao_dados($dados);

				// Padrao

				return($dados);
			}

		// INCLUDES







		// <------------------------></------------------------>----------------------------------------------------------------------------------------------------------------------------------







		// VIEWS


			// Home
			public function home(){
                if(isset($_GET['morePage'])):

                    $limit = $_GET['morePage'];

                else:

                    $limit = 3;
                endif;
                // Leiloes e Lotes Star
					$this->colunas = 'id, nome, foto, comitentes, data_ini';
					$this->filtro = " WHERE ".STATUS." AND star = 1 AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS." ".SITUACAO.") ORDER BY data_ini asc, ".ORDER." LIMIT 0,".$limit."";
					$dados['leiloes_star'] = $this->read('leiloes');

					$this->colunas = 'id, nome, foto, leiloes, data_ini';
					$this->filtro = " WHERE ".STATUS." AND situacao = 0 AND star = 1 ORDER BY data_ini asc, ".ORDER." ";
					$dados['lotes_star'] = $this->read('lotes');

					// Juntar Leiloes e lotes Star
					$dados['leiloes_e_lotes_star'] = leiloes_e_lotes_star($dados['leiloes_star'], $dados['lotes_star']);
				// Leiloes e Lotes Star




				$this->filtro = " WHERE ".STATUS." AND `data_fim` BETWEEN ('".date('c')."') AND ('4000-12-31 00:00') AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS." AND situacao = 0) ORDER BY data_ini asc, ".ORDER." LIMIT 0,".$limit."";
				//$this->filtro = " WHERE ".STATUS." AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS."  ".SITUACAO.") ORDER BY data_ini asc, ".ORDER." ";
				$dados['leiloes'] = $this->read('leiloes');
	
	
			

				$this->filtro = " WHERE ".STATUS." AND foto != '' ORDER BY ".ORDER." ";
				$dados['comitentes'] = $this->read('comitentes');

                if(isset($_GET['morePageEnd'])):

                    $limit = $_GET['morePageEnd'];

                else:

                    $limit = 3;
                endif;
				$this->filtro = " WHERE lote_atual = '0' LIMIT 0,".$limit."";
				$dados['leiloessf'] = $this->read('leiloes');

				$this->view($_GET['pg'], $dados);
			}


			// lotes
			public function lotes(){

				$this->colunas = 'id, nome, foto, foto1, codigo, comitentes, tipos, natureza, data_ini, data_fim';
				$this->prepare = array($_GET['id']);
				$this->filtro = " WHERE ".STATUS." AND `id` = ? ORDER BY ".ORDER." ";
				$dados['leiloes'] = $this->read_unico('leiloes');

				$this->colunas = 'id, nome, foto, foto10, leiloes, lances, lance_ini, data_ini, data_fim, cidades, estados, ordem, count';
				if(isset($dados['leiloes']->id)){
					$this->prepare = array($dados['leiloes']->id);
					$this->filtro = " WHERE ".STATUS." AND situacao = 0 AND `leiloes` = ? ORDER BY ordem ASC ";
					$dados['lotes'] = $this->read('lotes');

					if(count($dados['lotes']) == 1){
						location(url('lote', $dados['lotes'][0]));
					}

				} else {
					// FILTRO
						$filtro = " ";
						if(isset($_GET['status'])){
							$filtro .= status_leiloes($_GET['status']);
						} else {

						    if(isset($_GET['FIM_ls'])):
                                $filtro .= " AND situacao != 1";

                            else:

                                    $filtro .= " AND situacao = 0";

                            endif;
						}

						/*
						if(isset($_GET['status_2']) AND is_array($_GET['status_2'])){
							$array = array();
							foreach ($_GET['status_2'] as $key => $value) {
								$array[] = "(1=1 ".status_leiloes($value).") ";
							}
							$filtro .= $array ? " AND (".implode(' OR ', $array).") " : "";
						} else {
							$filtro .= " AND ".STATUS." AND situacao = 0";
						}
						*/

						if(isset($_GET['tipos']) AND is_array($_GET['tipos'])){
							$array = array();
							foreach ($_GET['tipos'] as $key => $value) {
								$array[] = " tipos = '".$value."' ";
							}
							$filtro .= $array ? " AND `leiloes` IN ( SELECT `id` FROM `leiloes` WHERE (".implode(' OR ', $array).") ) " : "";
						}
						
							if(isset($_GET['leiloes'])){
							
							$filtro .=  " AND `leiloes` = ".$_GET['leiloes']."";
						}
						if(isset($_GET['natureza']) AND is_array($_GET['natureza'])){
							$array = array();
							foreach ($_GET['natureza'] as $key => $value) {
								$array[] = " natureza = '".$value."' ";
							}
							$filtro .= $array ? " AND `leiloes` IN ( SELECT `id` FROM `leiloes` WHERE (".implode(' OR ', $array).") ) " : "";
						}
						if(isset($_GET['cate']) AND is_array($_GET['cate'])){
							$array = array();
							foreach ($_GET['cate'] as $key => $value) {
								$array[] = " categorias = '".$value."' ";
							}
							$filtro .= $array ? " AND (".implode(' OR ', $array).") " : "";
						}
						if(isset($_GET['estados']) AND is_array($_GET['estados'])){
							$array = array();
							foreach ($_GET['estados'] as $key => $value) {
								$array[] = " estados = '".$value."' ";
							}
							$filtro .= $array ? " AND (".implode(' OR ', $array).") " : "";
						}
						if(isset($_GET['cidades']) AND is_array($_GET['cidades'])){
							$array = array();
							foreach ($_GET['cidades'] as $key => $value) {
								$array[] = " cidades = '".$value."' ";
							}
							$filtro .= $array ? " AND (".implode(' OR ', $array).") " : "";
						}
					// FILTRO

					$this->filtro = " WHERE ".STATUS." ".$filtro." ".filtro_fixo('categorias')." ".filtro_busca()." ORDER BY ".ORDER." ";
				 	$dados['lotes'] = $this->read('lotes', 12);
				}

				$this->view($_GET['pg'], $dados);
			}


			// lote
			public function lote(){

			    $campos = 'id, foto, foto100, situacao, leiloes, incremento, sucata, ordem, count, anexo1, foto1,';
			    $campos .= 'area_privativa, quartos, suites, vagas, banheiros, desocupado, fase_da_obra,';
                $campos .= ' anexo2, foto2, anexo3, foto3, anexo4, foto4, anexo5, foto5, foto10, txt_extra_01, txt_extra_02, txt_extra_03, google_maps,';
                $campos .= ' academia, bicicletario, brinquedoteca, campo_de_futebol, churrasqueira, cinema, pet_care, piscina, piscina_infantil, pista_de_skate, playground,';
                $campos .= ' quadra_de_futsal, quadra_de_squash, quadra_de_tenis, restaurante, sala_de_massagem, salao_de_beleza, salao_de_festas, salao_de_festas_infantil,';
                $campos .= ' salao_de_jogos, sauna, spa, vagas_de_visitantes';

                $this->colunas = $campos;
				$this->prepare = array($_GET['id']);
				$this->filtro = " WHERE ".STATUS." AND `id` = ? ORDER BY ".ORDER." ";
				$dados['item'] = $this->read_unico('lotes');

				$this->prepare = array($dados['item']->leiloes);
				$this->filtro = " WHERE ".STATUS." AND `id` = ? ORDER BY ".ORDER." ";
				$dados['leiloes'] = $this->read_unico('leiloes');

				$cadastro = isset($_SESSION['x_site']->id) ? $_SESSION['x_site']->id : 0;
				$this->prepare = array($dados['item']->id);
			//	$this->filtro = " WHERE cadastro = '".$cadastro."' AND `leiloes` IN ( SELECT `leiloes` FROM `lotes` WHERE id = ? ) ";
				$this->filtro = " WHERE cadastro = '".$cadastro."' AND lotes=?";
				$dados['leiloes_habilitacoes'] = $this->read('leiloes_habilitacoes');

				$this->prepare = array($dados['item']->id);
				$this->filtro = " WHERE cadastro = '".$cadastro."' AND `lotes` = ? ";
				$dados['lotes_habilitacoes_sucata'] = $this->read('lotes_habilitacoes_sucata');

				countt($dados['item'], 'lotes');

				$dados['mais_fotos'] = mais_fotos($dados['item']);


				$this->colunas = 'id, nome, ordem';
				$this->prepare = array($dados['item']->leiloes);
				$this->filtro = " WHERE ".STATUS." AND `leiloes` = ? ORDER BY ".ORDER." ";
				$dados['lotes'] = $this->read('lotes');

				$this->view($_GET['pg'], $dados);
			}


			// faq
			public function faq(){

				$this->filtro = " WHERE ".STATUS." ORDER BY ".ORDER." ";
				$dados['faq'] = $this->read('faq');

				$this->view($_GET['pg'], $dados);
			}


			// servico
			public function servico(){

				$this->prepare = array($_GET['id']);
				$this->filtro = " WHERE ".STATUS." AND `id` = ? ";
				$dados['item'] = $this->read_unico('servicos');

				$dados['mais_fotos'] = mais_fotos($dados['item']);

				$this->view($_GET['pg'], $dados);
			}


			// imprimir
			public function imprimir(){
				$this->view($_GET['pg'], '');
			}
			// excel
			public function excel(){
				if(!isset($_SESSION['x_admin']->id)){
					echo '<script> window.parent.location="'.DIR.'/'.ADMIN.'/login.php"; </script>';
					exit();
				} else {
					require_once('../views/excel.phtml');					
				}
			}

		//busca_leiloes
        public function busca_leiloes()
        {

              $query = 'SELECT id,
                               nome                                  
                          FROM lotes1_cate                         
                      ORDER BY nome';

              $resultado = $this->db->query($query);
              $lista = $resultado->fetchAll(PDO::FETCH_ASSOC);

              $dados['tipologia'] = $lista;

              $query = 'SELECT id,
                               nome                                  
                          FROM comitentes                         
                      ORDER BY nome';

              $resultado = $this->db->query($query);
              $lista = $resultado->fetchAll(PDO::FETCH_ASSOC);

              $dados['comitentes'] = $lista;

              $this->view($_GET['pg'], $dados);
        }


        public function busca_autocomplete()
        {
            $term = $_GET['term'];

            $query = "SELECT bairro,
                                 cidades,
                                 estados 
                            FROM lotes 
                           WHERE (bairro LIKE '%$term%' OR cidades LIKE '%$term%' OR estados LIKE '%$term%') 
                        GROUP BY bairro 
                        ORDER BY bairro";

            $resultado = $this->db->query($query);
            $lista = $resultado->fetchAll(PDO::FETCH_ASSOC);

            $elementos = [];
            foreach ($lista as $local) {
              $elementos[] = $local['bairro'] . ' , ' . $local['cidades'] . ' - ' . $local['estados'];
            }

            return print_r(json_encode($elementos, JSON_UNESCAPED_UNICODE));

        }


        //busca
        public function busca()
        {

            //Ordenar

                $ordenacao = $_GET['ordenacao'];
                $ordenacao_order = '';

                //maior lance
                if(isset($ordenacao) && $ordenacao == 'maior_lance_inicial') {
                    $ordenacao_order = ', a.lance_ini DESC';
                }

                //menor lance
                if(isset($ordenacao) && $ordenacao == 'menor_lance_inicial') {
                    $ordenacao_order = ', a.lance_ini ASC';
                }

                //maior lance atual
                if(isset($ordenacao) && $ordenacao == 'maior_lance_atual') {
                    $ordenacao_order = ', a.lances DESC';
                }

                //menor lance atual
                if(isset($ordenacao) && $ordenacao == 'menor_lance_atual') {
                    $ordenacao_order =  ', a.lances ASC';
                }

            //Fim Ordenar


            //Localidade
            $localidade = $_GET['localidade'];

            $parte = explode(',',$localidade);

            $bairro = trim($parte[0]);

            $cidade_estado = explode('-' , $parte[1]);

            $cidade = trim($cidade_estado[0]);

            $bairro_cidade_sql = '';

            if(!empty($bairro) && !empty($cidade)){
                $bairro_cidade_sql = 'AND (a.bairro like "%'.$bairro.'%" OR a.cidades like "%'.$cidade.'%") ';
            }else{
               $bairro_cidade_sql = 'AND (a.bairro like "%' . $localidade . '%" OR a.cidades like "%' . $localidade . '%") ';
            }


            //Fim Localidade

            //Área privativa
            $area = $_GET['area'];

            $area_sql = '';

            empty($area['min'])?'':($area_sql .="AND a.area_privativa >= ".$area['min']." ");
            empty($area['max'])?'':($area_sql .="AND a.area_privativa <= ".$area['max']." ");

            //Fim Área privativa

            // Comitente
            $comitente = $_GET['comitente'];

            $comitente_sql = '';

            empty($comitente)?'':($comitente_sql ="AND b.comitentes like '%".$comitente."%' ");

            //Fim Comitente

            //Preço
             $preco = $_GET['valor'];

             $preco_sql = '';

             empty($preco['min'])?'':($preco_sql .="AND a.lance_ini >= ". str_replace('.','', $preco['min'])." ");
             empty($preco['max'])?'':($preco_sql .="AND a.lance_ini <= ".str_replace('.','', $preco['max'])." ");

            //Fim Preço

            //Tipo do imóvel
            $tipologia = $_GET['tipologia'];

            $tipologia_sql = '';
            $a = 0;
            foreach ($tipologia as $valor){
                ++$a;
                if($a <= 1) {
                    $tipologia_sql = "AND a.categorias in (".$valor['value'];
                }else{
                    $tipologia_sql .= ",".$valor['value'];
                }
            }

            empty($tipologia_sql)?'':($tipologia_sql.=') ');

            //Fim Tipo do imóvel


            //Situação do leilão
            $situacao = $_GET['situacao'];


            $situacao_sql = '';

            foreach($situacao as $value) {

                if(empty($situacao_sql) && $value != 1) {
                    $situacao_sql = "AND a.situacao in ('".$value."'";
                }

                if(!empty($situacao_sql) && $value != 1) {
                     $situacao_sql .= ",'" . $value . "'";
                }


            }

            empty($situacao_sql)?'':($situacao_sql.=') ');


            // Neste foreach faço a pesquisa por leiloes em loteamento
            foreach($situacao as $value) {

                //em loteamento -  1 (um) seria em loteamento mas não há um código para isso
                //logo é preciso fazer uma verificação nas datas
                if($value == 1 ) {

                    // removo o AND e adiciono ao $situacao_em_loteamento
                    if(!empty($situacao_sql)) {
                        $situacao_sql = str_replace('AND', 'or', $situacao_sql);
                    }

                    $situacao_em_loteamento = " AND ( (a.data_ini > NOW()) or ( a.data_ini < NOW() and a.data_ini1 > NOW()) ".$situacao_sql." ) ";

                    $situacao_sql = $situacao_em_loteamento;
                }

            }

            //Fim Situação do leilão


            //natureza do leilão
            $natureza = $_GET['natureza'];

            $natureza_sql = '';
            $a = 0;
            foreach ($natureza as $valor) {
                ++$a;
                if($a <= 1){
                    $natureza_sql = "AND b.natureza in (".$valor['value'];
                }else{
                    $natureza_sql .= ",".$valor['value'];
                }
            }

            empty($natureza_sql)?'':($natureza_sql.=') ');
            //Fim natureza do leilão

            //dormitorios
            $dormitorio = $_GET['dormitorio'];

            $dormitorio_sql = '';
            $cinco_ou_mais_dormitorios = '';
            $a = 0;
            foreach ($dormitorio as $valor) {
                ++$a;
                if($a <= 1){
                    $dormitorio_sql = "AND ( a.quartos in (".$valor['value'];
                }else{
                    $dormitorio_sql .= ",".$valor['value'];
                }

                //se for selecionado 5 pesquisar valores maiores também
                if($valor['value'] == 5) {
                    $cinco_ou_mais_dormitorios = 'OR a.quartos >= 5 ';
                }
            }

            empty($dormitorio_sql)?'':($dormitorio_sql.=') '.$cinco_ou_mais_dormitorios.') ');
            //Fim dormitorios

            //suítes
            $suite = $_GET['suite'];

            $suite_sql = '';
            $cinco_ou_mais_suites = '';
            $a = 0;
            foreach ($suite as $valor) {
                ++$a;
                if($a <= 1){
                    $suite_sql = "AND ( a.suites in (".$valor['value'];
                }else{
                    $suite_sql .= ",".$valor['value'];
                }

                //se for selecionado 5 pesquisar valores maiores também
                if($valor['value'] == 5) {
                    $cinco_ou_mais_suites = 'OR a.suites >= 5 ';
                }
            }

            empty($suite_sql)?'':($suite_sql.=') '.$cinco_ou_mais_suites.') ');
            //Fim suítes

            //vagas
            $vagas = $_GET['vagas'];

            $vagas_sql = '';
            $cinco_ou_mais_vagas = '';
            $a = 0;
            foreach ($vagas as $valor) {
                ++$a;
                if($a <= 1){
                    $vagas_sql = "AND ( a.vagas in (".$valor['value'];
                }else{
                    $vagas_sql .= ",".$valor['value'];
                }

                //se for selecionado 5 pesquisar valores maiores também
                if($valor['value'] == 5) {
                    $cinco_ou_mais_vagas = 'OR a.vagas >= 5 ';
                }
            }

            empty($vagas_sql)?'':($vagas_sql.=') '.$cinco_ou_mais_vagas.') ');
            //Fim vagas

            $query = 'SELECT a.id as id_lote,
                             a.nome as nome_lote,
                             a.nome_meta,
                             (SELECT nome FROM lotes1_cate WHERE id = a.categorias) as tipo_imovel,
                             a.bairro,
                             a.cidades as cidade_lote,
                             a.estados as estado_lote,
                             a.quartos,
                             a.suites,
                             a.vagas,
                             a.banheiros,
                             a.area_privativa,
                             a.desocupado as desocupado,
                             FORMAT(a.lance_ini,2,"de_DE") as lance_ini,
                             FORMAT(a.lances,2,"de_DE") as lance_recente,
                             a.foto,
                             if((SELECT situacao FROM lotes WHERE id = a.id AND (data_ini > NOW() AND NOW() <= data_fim ) OR (data_ini1 > NOW() AND NOW() <= data_fim1)) = 0,1,a.situacao) as situacao,
                             if((SELECT situacao FROM lotes WHERE id = a.id AND (data_ini > NOW() AND NOW() <= data_fim ) OR (data_ini1 > NOW() AND NOW() <= data_fim1)) = 0,1,a.situacao) as situacao_ordem,
                             DATE_FORMAT(a.data_ini, "%d-%m-%Y %H:%i:%s") as data_ini,
                             DATE_FORMAT(a.data_fim, "%d-%m-%Y %H:%i:%s") as data_fim,
                             (SELECT fase_da_obra FROM fase_da_obra WHERE id = a.fase_da_obra) as obra,                             
                             b.natureza,
                             b.codigo as codigo_leiloes,
                             (SELECT nome FROM tipos WHERE id = b.tipos) as tipo_leiloes,
                             (REPLACE(b.comitentes,"-","")) as id_comitente,  
                             (SELECT foto FROM comitentes WHERE id = id_comitente) as foto_comitente                      
                        FROM lotes as a  
                   LEFT JOIN leiloes as b 
                          ON a.leiloes = b.id 
                       WHERE 1 = 1 
                             '.$natureza_sql.'
                             '.$situacao_sql.'
                             '.$tipologia_sql.' 
                             '.$preco_sql.'
                             '.$area_sql.'
                             '.$bairro_cidade_sql.'
                             '.$dormitorio_sql.'
                             '.$suite_sql.'
                             '.$vagas_sql.'
                             '.$comitente_sql.'                      
                    ORDER BY FIELD(situacao_ordem,0,1,3,10,2) '.$ordenacao_order;


            $resultado = $this->db->query($query);
            $lista = $resultado->fetchAll(PDO::FETCH_ASSOC);

           // print_r($query);

           return print_r(json_encode($lista, JSON_UNESCAPED_UNICODE));
           //return print_r(json_encode(array('sql'=>$situacao_sql), JSON_UNESCAPED_UNICODE));

        }



		// VIEWS







		// ------------------------------------------------------------------------------------------------------------------------------------------







		// VIEWS PADROES


			// Textos
			public function textos(){
				$banco = 'textos';
				if($_GET['pg_real']=='textosp'){
					$banco = 'paginas';
				} elseif($_GET['pg_real']=='textosp1'){
					$banco = 'paginas1_cate';
				}

				$this->prepare = array($_GET['id']);
				$this->filtro = " WHERE ".STATUS." AND `id` = ? ";
				$dados['item'] = $this->read_unico($banco);
				$dados['titulo'] = $dados['item']->nome;

				$dados['mais_fotos'] = $banco == 'textos' ? multifotos($dados['item']) : mais_fotos($dados['item']);

				$this->view($_GET['pg'], $dados);
			}


			// Paginas Padroes ou com Ajax
			public function fale(){ $this->view($_GET['pg'], ''); }
			public function login(){ $this->view($_GET['pg'], ''); }
			public function cadastro(){ $this->view($_GET['pg'], ''); }

			public function carrinho(){
				//verificar_sessao('carrinho');
				unset($_SESSION['desconto']);
				$this->view($_GET['pg'], '');
			}

			public function minha_conta(){
				verificar_sessao('minha_conta');

                // Leiloes e Lotes Star
                $this->colunas = 'id, nome, foto, comitentes, data_ini';
                //$this->filtro = " WHERE ".STATUS." AND star = 1 AND `data_fim` BETWEEN ('".date('c')."') AND ('4000-12-31 00:00') AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS." AND situacao = 0) ORDER BY data_ini asc, ".ORDER." ";
                $this->filtro = " WHERE ".STATUS." AND star = 1 AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS." ".SITUACAO.") ORDER BY data_ini asc, ".ORDER." ";
                $dados['leiloes_star'] = $this->read('leiloes');

                $this->colunas = 'id, nome, foto, leiloes, data_ini';
                //$this->filtro = " WHERE ".STATUS." AND situacao = 0 AND star = 1 ORDER BY data_ini asc, ".ORDER." ";
                $this->filtro = " WHERE ".STATUS."  ".SITUACAO." AND star = 1 ORDER BY data_ini asc, ".ORDER." ";
                $dados['lotes_star'] = $this->read('lotes');

                // Juntar Leiloes e lotes Star
                $dados['leiloes_e_lotes_star'] = leiloes_e_lotes_star($dados['leiloes_star'], $dados['lotes_star']);
                // Leiloes e Lotes Star

                //$this->filtro = " WHERE ".STATUS." AND `data_fim` BETWEEN ('".date('c')."') AND ('4000-12-31 00:00') AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS." AND situacao = 0) ORDER BY data_ini asc, ".ORDER." ";
                $this->filtro = " WHERE ".STATUS." AND `id` IN ( SELECT `leiloes` FROM `lotes` WHERE ".STATUS."  ".SITUACAO.") ORDER BY data_ini asc, ".ORDER." ";
                $dados['leiloes'] = $this->read('leiloes', 32);

                $this->filtro = " WHERE ".STATUS." AND foto != '' ORDER BY ".ORDER." ";
                $dados['comitentes'] = $this->read('comitentes');
				$this->view($_GET['pg'], $dados);
			}



			// Cotacao
			public function cotacao(){
				$this->filtro = " WHERE `tipo` = 'emails' ";
				$dados['configs_contato'] = $this->read('configs');

				// Excluir Cotacao
				if(isset($_GET['cotacao_excluir']) and $_GET['cotacao_excluir']){
					unset($_SESSION['cotacao']['id'][$_GET['banco']][$_GET['cotacao_excluir']]);
					unset($_SESSION['cotacao']['qtd'][$_GET['banco']][$_GET['cotacao_excluir']]);
					location(DIR.'/cotacao/');
				}

				$dados = cotacao_dados($dados);

				if(isset($_POST['enviar_email'])) unset($_SESSION['cotacao']);

				$this->view($_GET['pg'], $dados);
			}


		// VIEWS PADROES






		// ------------------------------------------------------------------------------------------------------------------------------------------




		// Apps
	
			private function view($pg, $vars = null){
				global $dados;

				$globais = array('pagg');
				foreach($globais as $value){
					$vars[$value] = $dados[$value];
				}

				global $config_zz;
				foreach(@$config_zz as $key => $value){
					$vars[$key] = $value;
				}

				// Padroes
				$head = new Head();
				define('META', $head->meta());

				if($_GET['pg'] != 'imprimir'){
					define('CSS', $head->css());
					define('JAVASCRIPT', $head->js());
				} else {
					define('CSS', '');
					define('JAVASCRIPT', '');
				}

				// Includes
				extract($this->includes(), EXTR_OVERWRITE);

				// Variaveis
				if(is_array($vars) and count($vars) ){
					extract($vars, EXTR_OVERWRITE);
				}

				// Pagina Real Existe?
				if( file_exists('../views/'.$_GET['pg_real'].'.phtml') )
					$pg = $_GET['pg_real'];

				return require_once('../views/index.phtml');

			}

		// Apps




	}

?>