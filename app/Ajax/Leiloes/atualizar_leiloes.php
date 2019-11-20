<?	ob_start();

	require_once "../../../system/conecta.php";
	require_once "../../../system/mysql.php";
	include_once '../../../app/Funcoes/funcoes.php';
	include_once '../../../app/Classes/Email.php';
	include_once 'atualizar_leiloes_func.php';
	date_default_timezone_set('America/Sao_Paulo');


	$mysql = new Mysql();
	$mysql->nao_existe_all = 1;
	$arr = array();

	// IDS
	$ids_leiloes = ids($_POST['leiloes']);
	$ids_lotes = ids($_POST['lotes']);



	// LEILOES E LOTES
	$colunas_lotes = 'id, nome, foto, leiloes, lances_cadastro, lances_plaquetas, lances_data, categorias, situacao,';
    $colunas_lotes .= ' praca2, lances, acrescimo, cidades, estados, lance_ini, lance_min, lance_min1, data_ini, data_fim,';
	$colunas_lotes .= ' data_acrescimo, data_ini1, data_fim1, count, area_privativa, quartos, suites, vagas, banheiros, desocupado, fase_da_obra';
	if($ids_leiloes OR $ids_lotes){

		// LEILOES
		if($ids_leiloes){
			$mysql->colunas = 'id, nome, codigo, natureza, tipos, acrescimo, data_ini, data_fim';
			$mysql->filtro = "  WHERE ".STATUS." AND (".implode(' OR ', $ids_leiloes).") ORDER BY ".ORDER."  ";
			$leiloes = $mysql->read('leiloes');
			foreach ($leiloes as $key => $value){

				$mysql->colunas = $colunas_lotes;
				$mysql->filtro = " WHERE ".STATUS." AND situacao = 0 AND leiloes = '".$value->id."' ORDER BY estados asc, ".ORDER." ";
				$lotes = $mysql->read('lotes');

				$dados = array("count"=>0, "count_lances"=>0, "praca"=>0, "local"=>array());
				foreach ($lotes as $key1 => $value1) {
					$dados = tipo_de_leilao($value, $lotes, $key1, $value1, $dados);
					$arr['item'][$dados['box_id']] = informacoes($value, $lotes, $value1, $dados);
					$arr['item'][$dados['box_id']] = cronometro($value1, $arr['item'][$dados['box_id']]);
				}

			}
		}

		// LOTES
		if($ids_lotes){
			$mysql->colunas = $colunas_lotes;
			$mysql->filtro = " WHERE ".STATUS." AND (".implode(' OR ', $ids_lotes).") ORDER BY estados asc, ".ORDER." ";
			$lotes = $mysql->read('lotes');
			foreach ($lotes as $key1 => $value1) {
				$dados = array("count"=>0, "count_lances"=>0, "praca"=>0, "local"=>array());
				$dados = tipo_de_leilao('', $lotes, $key1, $value1, $dados);
				$arr['item'][$dados['box_id']] = informacoes('', $lotes, $value1, $dados);
				$arr['item'][$dados['box_id']] = cronometro($value1, $arr['item'][$dados['box_id']]);
			}
		}




	// LOTE
	} else {
		$mysql->colunas = $colunas_lotes;
		$mysql->filtro = " WHERE ".STATUS." AND id = '".$_POST['lote']."' ORDER BY estados asc, ".ORDER." ";
		$lotes = $mysql->read('lotes');
		foreach ($lotes as $key1 => $value1) {
			$dados = array("count"=>0, "count_lances"=>0, "praca"=>0, "local"=>array());
			$dados = tipo_de_leilao('', $lotes, $key1, $value1, $dados);
			$arr['item'][$dados['box_id']] = informacoes('', $lotes, $value1, $dados, 1);
			$arr['item'][$dados['box_id']] = cronometro($value1, $arr['item'][$dados['box_id']]);
		}

	}
	// FORACH LEILOES E LOTES


	echo json_encode($arr);

?>