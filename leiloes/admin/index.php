<?

	require_once "../system/conecta.php";
	require_once "../".ADMIN."/home.php";

    // Eliminar Bug de LUGAR == site
    if(LUGAR == 'site'){
    	echo '<script>window.parent.location="'.DIR.'/admin/";</script>';
    	exit();
    }

?>