<?php
class ECU911Generator{
	/**
	 * constructor
	 */
	function __construct(){
		$this->loadModels();
	}
	/**
	 *
	 */
	public function loadModels($drv=null,$usr=null,$pass=null,$db=null,$host=null){
		require_once('Doctrine.php');
		spl_autoload_register(array('Doctrine', 'autoload'));

        $conn = Doctrine_Manager::connection($drv.'://'.$usr.':'.$pass.'@'.$host.'/'.$db, 'doctrine');
		Doctrine_Core::generateModelsFromDb('db/'.$db, array('doctrine'), array('generateTableClasses' => true));
		echo "Generado Exitosamente!!";
	}
}
new ECU911Generator();
?>
