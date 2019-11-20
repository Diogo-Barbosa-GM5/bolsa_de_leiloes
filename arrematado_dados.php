<?	ob_start();

	require_once "system/conecta.php";
	require_once "system/mysql.php";
	include_once 'app/Funcoes/funcoes.php';
	include_once 'app/Classes/Email.php';
	include_once 'app/Classes/Upload.php';

	$mysql = new Mysql();

											$mysql->prepare = array($_GET['leilao']);
											$mysql->filtro = " WHERE `id` = ? ";
											$leilao = $mysql->read_unico('leiloes');




											$mysql->prepare = array($_GET['leilao']);
											$mysql->filtro = " WHERE `leiloes` = ? ";
											$lotes = $mysql->read('lotes');

if(!isset($_SESSION['x_admin']->id)):

header("Location:/admin");

endif;	

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <title>EXPORTAR RELATORIOS</title>

        <!-- DataTables -->
        <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>


    </head>


    <body>

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container">

                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="/admin" class="logo">
                            <span>ADIMIS<span>TRAÇAO</span></span>
                            <!--<span><img src="assets/images/logo.png" alt="logo" style="height: 20px;"></span>-->
                        </a>
                    </div>
                    <!-- End Logo container-->

                    <div class="navbar-custom navbar-left">
                        <div id="navigation">
                            <!-- Navigation Menu-->
                            <ul class="navigation-menu">
                                <li>
                                    <a href="/admin">
                                        <span><i class="zmdi zmdi-view-dashboard"></i></span>
                                        <span> INICIO </span> </a>
                                </li>
                               


                            </ul>
                            <!-- End navigation menu  -->
                        </div>
                    </div>


                    <div class="menu-extras">

                     
                        <div class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </div>
                    </div>

                </div>
            </div>

        </header>
        <!-- End Navigation Bar-->


        <div class="wrapper">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                     
                        <h4 class="page-title"><? echo $leilao->nome;?></h4>
                    </div>
                </div>


             

                         

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                       

                            <h4 class="header-title m-t-0 m-b-30">Relatorio Geral de Arremates</h4>

                            <table id="datatable-buttons" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>COD</th>
                                        <th>ID</th>
                                        <th>SITUAÇÃO</th>
                                        <th>DESCRIÇÃO</th>
                                        <th>QT</th>
                                        <th>LOTE</th>
                                        <th>PREÇO</th>
                                        <th>VALOR</th>
                                        <th>CPF/CNPJ</th>
                                        <th>CLIENTE</th>
                                        <th>ENDEREÇO</th>
                                        <th>NÚMERO</th>
                                        <th>COMPL</th>
                                        <th>CEP</th>
                                        <th>CIDADE</th>
                                        <th>ESTADO</th>
                                        <th>CPF/CNPJ</th>
                                        <th>RG/IE</th>
                                        <th>DATA NASC</th>
                                        <th>EMAIL</th>
                                    </tr>
                                </thead>

                                <tbody>
                                	                                    	<? foreach($lotes as $key=>$value){



									if(!empty($value->lances_cadastro)):
											$mysql->prepare = array($value->lances_cadastro);
											$mysql->filtro = " WHERE `id` = ? ";
											$arrematante = $mysql->read_unico('cadastro');

											$cpf = empty($arrematante->cpf) ? $arrematante->cnpj : $arrematante->cpf;
 											$ciente = $arrematante->email;
 											$endereco = $arrematante->rua;
 											$complemento = $arrematante->complemento;
 											$cep = $arrematante->cep;
 											$cidade = $arrematante->cidades;
 											$estado = $arrematante->estados;
 											$email = $arrematante->email;
 											$nascimento = $arrematante->nascimento;
 											$numero = $arrematante->numero;
 											$cpf2 = empty($arrematante->cpf) ? $arrematante->cnpj : $arrematante->cpf;
 											$rg = empty($arrematante->rg) ? '' : $arrematante->rg;
									else:
											$cpf = '';
 											$ciente = '';
 											$endereco = '';
 											$complemento = '';
 											$cep = '';
 											$cidade = '';
 											$estado = '';
 											$email = '';
 											$nascimento = '';
 											$numero = '';
 											$cpf2 = '';
 											$rg = '';


									endif;			
                                	                            
                                	?>

                                    <tr>
                                    	
                                        <td>#<? echo $value->codigo; ?></td>
                                        <td><? echo $value->id; ?></td>
                                        <td><? echo !empty($value->lances_cadastro) ? 'ARREMATADO' : 'CONDICIONAL'; ?></td>
                                        <td><? echo $value->nome; ?></td>
                                        <td>01</td>
                                        <td><? echo $value->id; ?></td>
                                        <td>R$ <? echo number_format($value->lance_ini,2,',','.'); ?></td>
                                        <td>R$ <? echo number_format($value->lances,2,',','.'); ?></td>
										<td><? echo $cpf; ?></td>
										<td><? echo $email; ?></td>
										<td><? echo $endereco; ?></td>
										<td><? echo $numero; ?></td>
										<td><? echo $complemento; ?></td>
										<td><? echo $cep; ?></td>
										<td><? echo $cidade; ?></td>
										<td><? echo $estado; ?></td>
										<td><? echo $cpf2; ?></td>
										<td><? echo $rg; ?></td>
										<td><? echo $nascimento; ?></td>
										<td><? echo $email; ?></td>

                                   
                                      
                                    </tr>
                                     <? } ?>

                                </tbody>
                            </table>
                        </div>
                    </div><!-- end col -->
                </div>
              

          
            </div>
            <!-- end container -->



            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="zmdi zmdi-close-circle-o"></i>
                </a>
                <h4 class="">Notifications</h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list">
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">Michael Zenaty</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon">
                                    <i class="zmdi zmdi-account"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Signup</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">5 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon">
                                    <i class="zmdi zmdi-comment"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Message received</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-3.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">James Anderson</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 days ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="icon">
                                    <i class="zmdi zmdi-settings"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">Settings</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->

        </div>



        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <!-- Datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/jszip.min.js"></script>
        <script src="assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/pages/jquery.datatables.init.js"></script>

        <!-- App js -->
        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#datatable').dataTable();
                $('#datatable-keytable').DataTable( { keys: true } );
                $('#datatable-responsive').DataTable();
                $('#datatable-scroller').DataTable( { ajax: "assets/plugins/datatables/json/scroller-demo.json", deferRender: true, scrollY: 380, scrollCollapse: true, scroller: true } );
                var table = $('#datatable-fixed-header').DataTable( { fixedHeader: true } );
            } );
            TableManageButtons.init();

        </script>

    </body>
</html>