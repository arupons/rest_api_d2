<?php
/**
 * Servicio rest que se integrar� con el sistema del SIS ECU 911
 *
 * @author Juan Leon
 * @version 1.0
 * @package BDC-ECU.php.RestService
 */
require_once("Rest.php");
class Api extends Rest{
	private $_metodo;
	private $_argumentos;

	public function __construct(){
		parent::__construct();
	}

	private function devolverError($id){
		$errores = array(
				array('estado' => "error", "msg" => utf8_encode("petici�n no encontrada")),
				array('estado' => "error", "msg" => utf8_encode("petici�n no aceptada")),
				array('estado' => "error", "msg" => utf8_encode("petici�n sin contenido")),
				array('estado' => "error", "msg" => utf8_encode("par�metro incorrecto")),
				array('estado' => "error", "msg" => "error borrando usuario"),
				array('estado' => "error", "msg" => "error actualizando nombre de usuario"),
				array('estado' => "error", "msg" => "error buscando usuario por email"),
				array('estado' => "error", "msg" => "error creando usuario"),
				array('estado' => "error", "msg" => "usuario ya existe")
		);
		return $errores[$id];
	}

	public function procesarLLamada() {
		if (isset($_REQUEST['url'])) {
			//si por ejemplo pasamos explode('/','////controller///method////args///') el resultado es un array con elem vacios;
			//Array ( [0] => [1] => [2] => [3] => [4] => controller [5] => [6] => [7] => method [8] => [9] => [10] => [11] => args [12] => [13] => [14] => )
			$url = explode('/', trim($_REQUEST['url']));
			//con array_filter() filtramos elementos de un array pasando funci�n callback, que es opcional.
			//si no le pasamos funci�n callback, los elementos false o vacios del array ser�n borrados
			//por lo tanto la entre la anterior funci�n (explode) y esta eliminamos los '/' sobrantes de la URL
			$url = array_filter($url);
			$this->_metodo = strtolower(array_shift($url));
			$this->_argumentos = $url;
			$func = $this->_metodo;
			if ((int) method_exists($this, $func) > 0) {
				if (count($this->_argumentos) > 0) {
					call_user_func_array(array($this, $this->_metodo), $this->_argumentos);
				} else {//si no lo llamamos sin argumentos, al metodo del controlador
					call_user_func(array($this, $this->_metodo));
				}
			}
			else
				$this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
		}
		$this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
	}

	public function objectToArray($d)
    {
      if (is_object($d)) {
        $d = get_object_vars($d);
      }
      if (is_array($d)) {
        return array_map(array($this, 'objectToArray'), $d);
        // return array_map(__FUNCTION__, $d);
      }
      else {
        return $d;
      }
    }
    
	public function convertirJson($data){
		return json_encode($data);
	}
}
//$api = new Api();
//$api->procesarLLamada();
?>
