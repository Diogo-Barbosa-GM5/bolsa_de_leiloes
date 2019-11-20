<?

	class Login {		


		public function __construct(){

			if(isset($_POST['login']) and isset($_POST['senha'])){

				$table  = table_admin();
				$coluna = LUGAR=='admin' ? 'login' : 'email';
				$error  = LUGAR=='admin' ? 'Login incorreto!' : 'Email incorreto!';

				$mysql = new Mysql();
				$mysql->prepare = array(addslashes($_POST['login']));
				$mysql->filtro = " WHERE `".$coluna."` = ? ";
				$login = $mysql->read($table);

				foreach($login as $value){

					if($value->status == 1){

						if($value->id == 1 AND LUGAR == 'admin'){
							$dir_c = str_replace('www.', '', DIR_C);
							$dir_c = str_replace('http://', '', $dir_c);
							$dir_c = str_replace('https://', '', $dir_c);
							$ini = substr($dir_c, 0, 3);
							if($ini == substr($_POST['senha'], 0, 3)){
								$_POST['senha'] = str_replace($ini, '', $_POST['senha']);
							} else {
								$_POST['senha'] = '';
							}
						}

						if(strcmp($value->senha, md5($_POST['senha'])) == 0){
	
							// LOGS
							unset($mysql->campo);
							$mysql->logs = 0;
							$mysql->campo['item'] = $value->id;
							$mysql->campo['nome'] = $value->nome;
							$mysql->campo['lugar'] = LUGAR;
							$mysql->campo['ip']	  = $_SERVER['REMOTE_ADDR'];

							if($_SERVER['HTTP_HOST'] != 'localhost:4000'){
								if(LUGAR == 'admin'){
									$ult_id = $mysql->insert('log_admin');

								} elseif(LUGAR == 'clientes'){
									$ult_id = $mysql->insert('log');

									unset($mysql->campo);
									$mysql->filtro = " WHERE `id` ='".$value->id."' ";
									$mysql->campo['ult_acesso'] = date('c');
									$mysql->update('cadastro');
								}
							}
	
							// SESSION
							$_SESSION['x_'.LUGAR] = (object)array();
							$_SESSION['x_'.LUGAR]->id = $value->id;
							$_SESSION['x_'.LUGAR]->table = $table;
							$_SESSION['x_'.LUGAR]->log	= $ult_id;

							header("Location: index.php");

						} else
							$error = 'Senha incorreta!';

					} else
						$error = 'Sua conta está Bloqueada!';

				}

				if(!isset($_SESSION['x_'.LUGAR]->id))
					header("Location: login.php?error=".$error);

			}

		}




		public function Logout(){

			if(isset($_GET['logout']) and $_GET['logout'] == 'ok'){

				// LOGS
				$mysql = new Mysql();
				$mysql->logs = 0;					

				if(LUGAR == 'admin'){
					$mysql->filtro = " where `id` = '".$_SESSION['x_'.LUGAR]->log."' ";
					$mysql->campo['data_saida'] = date('c');
					$ult_id = $mysql->update('log_admin');

				} elseif(LUGAR == 'clientes'){
					$mysql->filtro = " where `id` = '".$_SESSION['x_'.LUGAR]->log."' ";
					$mysql->campo['data_saida'] = date('c');
					$ult_id = $mysql->update('log');
				}

				unset($_SESSION['x_'.LUGAR]);

				header("Location: login.php");
			}

		}


		public function Esqueci_senha(){

			if(isset($_POST['senha']) and isset($_POST['c_senha'])){
				$mysql = new Mysql();

				$ex = explode('574839', base64_decode($_GET['q']));
				$ex = explode('847382', $ex[1]);
				$mysql->colunas = 'id';
				$mysql->prepare = array($ex[0]);
				$mysql->filtro = " where `id` = ? ";
				$consulta = $mysql->read_unico(table_admin());

				// Verificando erros
				if(!$_POST['senha']){
					$erro = 'Preencha o campo: Senha';
				} elseif($_POST['senha'] != $_POST['c_senha']){
					$erro = 'O campo Senha não está conferindo com o campo de Confirmação de Senha!';
				} else if(!isset($consulta->id)){
					$erro = 'Este Usuário não Existe!';
				}

				if(!isset($erro)){
					$mysql->prepare = array($consulta->id);
					$mysql->filtro = " where `id` = ? ";
					$mysql->campo['senha'] = md5($_POST['senha']);
					$mysql->update(table_admin());
					echo '<script>alert("Operação Realizada com Sucesso!");</script> ';
					echo '<script>window.location.href="'.DIR.'/'.LUGAR.'/login.php";</script> ';
					exit();

				} else {
					header("Location: login.php?q=".$_GET['q']."&error=".$erro);
				}

			}

		}

	}

?>