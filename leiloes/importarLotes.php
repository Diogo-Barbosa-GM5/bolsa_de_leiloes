<?

$uploaddir = 'web/lotes/';
@rmdir($uploaddir);

@mkdir("web/lotes", 0777);
@mkdir("web/fotos/leilao" . $_POST['leilao'] . "", 0777);

$uploadfile = $uploaddir . basename($_FILES['EnviarArquivo']['name']);

$extensao = explode('.', $_FILES['EnviarArquivo']['name']);
if ($extensao[1] == 'xls'):

    if (move_uploaded_file($_FILES['EnviarArquivo']['tmp_name'], $uploadfile)) {

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        date_default_timezone_set('Europe/London');

        /** Include PHPExcel_IOFactory */
        require_once dirname(__FILE__) . '/plugins/PHPExcel/Classes/PHPExcel.php';

        $objReader = new PHPExcel_Reader_Excel5();
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($uploadfile);
        $objPHPExcel->setActiveSheetIndex(0);


        $numerosdeGrupos = 0;
        $linhasGrupos = array();
        $dados = array();
// navegar na linha
        for ($linha = 1; $linha <= $_POST['registro']; $linha++) {
            // navegar nas colunas da respectiva linha
            for ($coluna = 0; $coluna <= $_POST['colunas']; $coluna++) {
                if ($linha == 1) {
                    // escreve o cabeçalho da tabela a bold
                } else {


                    if ($coluna == 0):
                        if (!empty(trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($coluna, $linha)->getValue()))):

                            $numerosdeGrupos = $numerosdeGrupos + 1;

                            $linhasGrupos[$numerosdeGrupos] = 1;
                        else:

                            $linhasGrupos[$numerosdeGrupos] = $linhasGrupos[$numerosdeGrupos] + 1;

                        endif;


                    endif;
                }
            }
        }

        for ($i = 0; $i <= $_POST['colunas']; $i++):

            $campos[$i] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, 1)->getValue();

        endfor;


        for ($i = 0; $i <= $_POST['colunas']; $i++):
            $campos[setCampos(trim($campos[0]))] = setCampos(trim($campos[0]));
        endfor;


        ob_start();

        require_once dirname(__FILE__) . "/system/conecta.php";
        require_once dirname(__FILE__) . "/system/mysql.php";
        include_once dirname(__FILE__) . '/app/Funcoes/funcoes.php';
        include_once dirname(__FILE__) . '/app/Classes/Email.php';

        $mysql = new Mysql();

        $mysql->filtro = "WHERE id = '" . $_POST['leilao'] . "'";
        $leilao = $mysql->read_unico('leiloes');

        $identificars = 1;

        $n = 1;

        for ($linha = 2; $linha < $_POST['registro'] + 2; $linha++) {

            if (!empty($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Identificador', $campos), $linha)->getValue())):

                $identificars = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Identificador', $campos), $linha)->getValue();

            endif;

            $imagemLotes = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Imagens', $campos), $linha)->getValue();


            $nomeclaturalotes[1] = 'a';
            $nomeclaturalotes[2] = 'b';
            $nomeclaturalotes[3] = 'c';
            $nomeclaturalotes[4] = 'd';
            $nomeclaturalotes[5] = 'e';


            $mysql->campo['foto'] = 'leilao' . $_POST['leilao'] . '/'.$n .'a'.'.JPG';
            $mysql->campo['foto1'] = 'leilao' . $_POST['leilao'] . '/'.$n .'b'.'.JPG';
            $mysql->campo['foto2'] = 'leilao' . $_POST['leilao'] . '/'.$n .'c'.'.JPG';
            $mysql->campo['foto3'] = 'leilao' . $_POST['leilao'] . '/'.$n .'d'.'.JPG';
            $mysql->campo['foto4'] = 'leilao' . $_POST['leilao'] . '/'.$n .'e'.'.JPG';


            $mysql->campo['nome'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Descrição', $campos), $linha)->getValue();
            $mysql->campo['outras_despesas'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Despesas', $campos), $linha)->getValue();
            $mysql->campo['identificador_xls'] = $identificars;
            $mysql->campo['lance_ini'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Valor Inicial', $campos), $linha)->getValue();
            $mysql->campo['lance_min'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Valor Mínimo', $campos), $linha)->getValue();
            $mysql->campo['leiloes'] = $_POST['leilao'];
            $mysql->campo['data_ini'] = $_POST['data_ini'];
            $mysql->campo['data_fim'] = $_POST['data_fim'];
            $mysql->campo['incremento'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $linha)->getValue();
            $mysql->campo['cidades'] = $_POST['localidade'];
            $mysql->campo['estados'] = $_POST['estado'];
            $mysql->campo['data_ini1'] = $_POST['data_ini'];
            $mysql->campo['data_fim1'] = $_POST['data_fim'];
            $mysql->campo['ordem'] = $identificars;
            $mysql->campo['nome_meta'] = $leilao->nome_meta;
            $mysql->campo['data'] = date('c');
            $mysql->campo['status'] = 1;
            $mysql->campo['situacao'] = 0;
            $mysql->campo['lang'] = 1;

            if (!empty($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Descrição', $campos), $linha)->getValue())):
                $id = $mysql->insert('lotes');




            endif;
            echo $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Identificador', $campos), $linha)->getValue() . '<br>';

            $n++;
        }

        unset($mysql->campo);

        $mysql->campo['data_ini'] = $_POST['data_ini'];
        $mysql->campo['data_fim'] = $_POST['data_fim'];
        $mysql->filtro = " WHERE id = '".$_POST['leilao']."' ";
        $mysql->update('leiloes');

        header('Location:/admin/?pg=60&mod=lotes');


        echo '<br><br>';
        //var_dump($leilao);
    } else {
        echo "Possível ataque de upload de arquivo!\n";
    }


endif;


function identificar($val, $arr)
{

    return array_search($val, $arr);

}


//Função de Filtragem de Campos
function setCampos($campo)
{

    if ($campo == 'Identificador'):
        $campo = 'id';

    elseif ($campo == 'Valor Mínimo'):
        $campo = 'lance_min';

    elseif ($campo == 'Despesas'):
        $campo = 'outras_despesas';

    elseif ($campo == 'Valor Inicial'):
        $campo = 'lance_ini';

    elseif ($campo == 'ID da Sub-categoria'):
        $campo = 'subcategoria_id';
    elseif ($campo == 'Detalhes'):
        $campo = 'nome';

    else:

        $campo = '';
    endif;

    return $campo;
}