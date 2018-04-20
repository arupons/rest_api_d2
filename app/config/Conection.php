<?php
/**
 * Esta clase permite obtener la cadena de conección para diferentes accesos a basesde datos Mysql
 *
 * @author Juan Carlos León Ruiz
 * @author juan.leon@ecu911.gob.ec
 *
 * @package ECU911.logica
 */
class Conection{
	/**
	 * nombre de base de datos
	 * @var string
	 */
	public $BaseDatos;
	/**
	 * nombre del servidor
	 * @var string
	 */
	public $Servidor;
	/**
	 * usuario de base de datos
	 * @var string
	 */
	public  $Usuario;
	/**
	 * clave de base de datos
	 * @var string
	 */
	public $Clave;
	
	/**
	 * constructor, iniciando variables
	 * @param string $bd
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 */
	function __construct($bd = null, $dbm = null, $host = null, $user = null, $pass = null){
		$this->BaseDatos = $bd;
        $this->dbm = $dbm;
		$this->Servidor = $host;
		$this->Usuario = $user;
		$this->Clave = $pass;
	}
	
	public function getStringConnDoctrine(){
		return $this->dbm.'://'.$this->GetUsuario().':'.$this->GetClave().'@'.$this->GetServidor().'/'.$this->BaseDatos;
	}

	/**
	 * obtener nombre de la base de datos
	 */
	private function GetBaseDatos(){
		return $this->BaseDatos;
	}
	
	/**
	 * apuntar a una base de datos diferente de la por defecto
	 * @param string $bd
	 */
	public function SetBaseDatos($bd){
		$this->BaseDatos = $bd;
	}
	
	/**
	 * obtener el nombre del servidor
	 */
	private function GetServidor(){
		return $this->Servidor;
	}
	
	/**
	 * obtener nombre de usuario
	 */
	private function GetUsuario(){
		return $this->Usuario;
	}
	
	/**
	 * Obtener clave de usuario
	 */
	private function GetClave(){
		return $this->Clave;
	}
}
?>
