<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/dirs.php');
    include_once(ROOT_PATH.'app/assets/Doctrine.php');
    include_once(ROOT_PATH.'app/assets/UUID.php');
    include_once(ROOT_PATH.'app/config/Conection.php');
    include_once(ROOT_PATH.'app/assets/jwt/JWT.php');
    require_once(ROOT_PATH.'app/assets/RestService/Api.php');

	/**
	 * Application controllers
	 *
	 * Add your application-wide methods in the class below, your controllers
	 * will inherit them.
	 *
	 * @package		app.controllers
	 * 
	 */
	class Aplication extends API{

        public function __construct($fld){
            parent::__construct();

            $this->key = "SecretKey";
            $controller = get_class($this) . 'Controller';
            require_once(CONTROLLER_PATH  . $fld . $controller . '.php');
            $this->Controller = new $controller;
        }

        public function index($arr = array()){
            return $this->Controller->index($arr);
        }
        public function show($arr = array()){
            return $this->Controller->show($arr);
        }
        public function create($arr = array()){
            return $this->Controller->create($arr);
        }
        public function update($arr = array()){
            return $this->Controller->update($arr);
        }
        public function delete($arr = array()){
            return $this->Controller->delete($arr);
        }
	}
?>