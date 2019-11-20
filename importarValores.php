<?

$uploaddir = 'web/lotes/';
@rmdir($uploaddir);

@mkdir("web/lotes", 0777);

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

        for($i=0;$i<=$_POST['colunas'];$i++):

        $campos[$i] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, 1)->getValue();

        endfor;



        for($i=0;$i<=$_POST['colunas'];$i++):
            $campos[setCampos(trim($campos[0]))] = setCampos(trim($campos[0]));
        endfor;


        ob_start();

        require_once dirname(__FILE__) ."/system/conecta.php";
        require_once dirname(__FILE__) ."/system/mysql.php";
        include_once dirname(__FILE__) .'/app/Funcoes/funcoes.php';
        include_once dirname(__FILE__) .'/app/Classes/Email.php';

        $mysql = new Mysql();

        $mysql->filtro = "WHERE id = '".$_POST['leilao']."'";
        $leilao = $mysql->read_unico('leiloes');


            for ($linha = 2; $linha <= $_POST['registro'] + 2; $linha++) {
                 
                 

                    $mysql->campo['identificador_xls'] = '0';
                    $mysql->campo['lance_ini'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Valor Mínimo',$campos), $linha)->getValue();
                    $mysql->campo['outras_despesas'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Despesas',$campos), $linha)->getValue();
                    $mysql->campo['lance_min'] = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Valor Mínimo',$campos), $linha)->getValue();
                    $mysql->filtro = "WHERE identificador_xls='".$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(identificar('Identificador',$campos), $linha)->getValue()."' AND leiloes = '".$_POST['leilao']."' ";
                    $mysql->update('lotes');



        }
        header('Location:/admin/?pg=60&mod=lotes');


            echo '<br><br>';
        //var_dump($leilao);
    } else {
        echo "Possível ataque de upload de arquivo!\n";
    }


endif;


function identificar($val,$arr){

   return array_search($val, $arr);

}


//Função de Filtragem de Campos
function setCampos($campo){

    if($campo == 'Identificador'):
        $campo = 'id';

    elseif($campo == 'Valor Mínimo'):
        $campo = 'lance_min';

    elseif($campo == 'Despesas'):
        $campo = 'outras_despesas';

    elseif($campo == 'Valor Inicial'):
        $campo = 'lance_ini';

    elseif($campo == 'ID da Sub-categoria'):
        $campo = 'subcategoria_id';
    elseif($campo == 'Detalhes'):
        $campo = 'nome';

    else:

        $campo = '';
    endif;

    return $campo;
}


        header('Location:/admin/?pg=60&mod=lotes');
