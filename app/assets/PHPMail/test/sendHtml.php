<?php
/**
 * información de la cuenta de correo del sistema
 * @author juanca
 *
 */
class Correo{
	/**
	 * ip del servidor smtp
	 * @var string
	 */
	private $host;
	/**
	 * puerto de conección smtp
	 * @var string
	 */
	private $port;
	/**
	 * Domain\User de la cuenta de correo
	 * @var string
	 */
	private $user;
	/**
	 * contraseña de la cuenta de correo
	 * @var string
	 */
	private $pass;
	/**
	 * Nombre de la cuenta de correo
	 * @var string
	 */
	private $nom;
	/**
	 * dirección de la cuenta de correo
	 * @var string
	 */
	private $cor;

	/**
	 * constructor
	 */
	function __construct($host="10.121.6.210", $nom="SIS ECU 911", $pass="R3p0$1+0r103cu9ii", $port='25', $user="ecu911\proyectos", $cor ="ecu911.team.proyectos@ecu911.gob.ec"){
		$this->setHost($host);
		$this->setNom($nom);
		$this->setPass($pass);
		$this->setPort($port);
		$this->setUser($user);
		$this->setCorreo($cor);
	}

	/**
	 * enviar mails
	 * @param array $Email debe contener las siguientes columnas 'Email'=>example@mail.com, 'LastName'=>Leon, 'Name'=> Juan
	 * @param string $Subject asunto del correo
	 * @param string $Body cuerpo del correo
	 * @return string|boolean cuando existe alguna excepción manda la información de la excepción del Correo
	 */
	function SendMailer($Email, $Subject, $Body, $Reply = false){
		if (!filter_var($Email['Email'], FILTER_VALIDATE_EMAIL)) {
			return false;
		}else{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();

			try{
				$mail->SMTPDebug  = 1;
				$mail->SMTPAuth   = true;
				$mail->SMTPSecure = "tls";
				$mail->Host       = $this->getHost();
				$mail->Username   = $this->getUser();
				$mail->Password   = $this->getPass();
				$mail->Port=$this->getPort();

				$mail->AddAddress($Email['Email'], trim($Email['LastName'].' '.$Email['Name']));

				if($Reply){
					$mail->AddBCC($this->getCorreo(), $this->getNom());
				}
				$mail->SetFrom($this->getCorreo(), $this->getNom());

				$mail->Subject = $Subject;

				$mail->MsgHTML($Body);
				$mail->IsHTML(true); // send as HTML

				$mail->Send();

			}catch (phpmailerException $e) {
				var_dump($e);
				WriteLog::writeLogs('SendMailer', 'phpmailerException: '.$e->getMessage().json_encode($Email));
				return false;
			} catch (Exception $e) {
				var_dump($e);
				WriteLog::writeLogs('SendMailer', 'Exception: '.$e->getMessage().json_encode($Email));
				return false;
			}
			return true;
		}
	}

	/**
	 * enviar mails
	 * @param array $Email debe contener las siguientes columnas 'Email'=>example@mail.com, 'LastName'=>Leon, 'Name'=> Juan
	 * @param string $Subject asunto del correo
	 * @param string $Body cuerpo del correo
	 * @return string|boolean cuando existe alguna excepción manda la información de la excepción del Correo
	 */
	function SendMailerGmail($Email, $Subject, $Body, $Reply = false){
		if (!filter_var($Email['Email'], FILTER_VALIDATE_EMAIL)) {
			return false;
		}else{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();

			try{
				$mail->SMTPDebug  = 1;
				$mail->SMTPAuth   = true;
				$mail->SMTPSecure = "ssl";
				$mail->Host       = $this->getHost();
				$mail->Username   = $this->getUser();
				$mail->Password   = $this->getPass();
				$mail->Port				= $this->getPort();

				$mail->AddAddress($Email['Email'], trim($Email['LastName'].' '.$Email['Name']));

				if($Reply){
					$mail->AddBCC($this->getCorreo(), $this->getNom());
				}
				$mail->SetFrom($this->getCorreo(), $this->getNom());

				$mail->Subject = $Subject;

				$mail->MsgHTML($Body);
				$mail->Send();

			}catch (phpmailerException $e) {
				var_dump($e);
				WriteLog::writeLogs('SendMailer', 'phpmailerException: '.$e->getMessage().json_encode($Email));
				return false;
			} catch (Exception $e) {
				var_dump($e);
				WriteLog::writeLogs('SendMailer', 'Exception: '.$e->getMessage().json_encode($Email));
				return false;
			}
			return true;
		}
	}

	/**
	 * retorna el host
	 * @return string
	 */
	function getHost(){
		return $this->host;
	}

	/**
	 * iniciar el host
	 * @param string $host
	 */
	function setHost($host){
		$this->host = $host;
	}
	/**
	 * retorno el puerto
	 * @return string
	 */
	function getPort(){
		return $this->port;
	}
	/**
	 * inicia el puerto
	 * @param string $port
	 */
	function setPort($port){
		$this->port = $port;
	}
	/**
	 * retorno el usuario
	 * @return string
	 */
	function getUser(){
		return $this->user;
	}
	/**
	 * retorna el usuario
	 * @param string $user
	 */
	function setUser($user){
		$this->user = $user;
	}
	/**
	 * retorna contraseña
	 * @return string
	 */
	function getPass(){
		return $this->pass;
	}
	/**
	 * inicia contraseña
	 * @param unknown $pass
	 */
	function setPass($pass){
		$this->pass=$pass;
	}
	/**
	 * retorna nombre a mostrar
	 * @return string
	 */
	function getNom(){
		return $this->nom;
	}
	/**
	 * inicia el nombre
	 * @param unknown $nom
	 */
	function setNom($nom){
		$this->nom = $nom;
	}
	/**
	 * inica el nombre del buzon
	 */
	function setCorreo($cor){
		$this->cor = $cor;
	}
	/**
	 * obtiene nombre del correo
	 * @return string
	 */
	function  getCorreo(){
		return $this->cor;
	}
}
