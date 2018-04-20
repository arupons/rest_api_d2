from datetime import time

class Templates:
    def getController(self, fecha, hora, db, table, dbm):
        name = table+"s"
        controller = """<?php
/**
 * Created by AkiraGen.
 * User: alfonso
 * Date: """+fecha+"""
 * Time: """+hora+"""
*/
require_once $_SERVER['DOCUMENT_ROOT'].'/Services/app/controllers/AppController.php';

class """+name+"""Controller extends AppController
{
    /**
     * constructor
     */
    function __construct($db = '"""+db+"""', $table = '"""+table+"""', $dbm = '"""+str(dbm)+"""'){
        parent::__construct($db, $table, $dbm);
    }
}
?>"""
        return controller

    def getService(self, fecha, hora, table, folder):
        name = table+"s"
        service = """<?php
/**
 * Created by AkiraGen.
 * User: alfonso
 * Date: """+str(fecha)+"""
 * Time: """+hora+"""
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/Services/app/Aplication.php');

class """+name+""" extends Aplication
{
    /**
     * codigo de error que bota al ejecutar un query
     * @var string
     */
    private $error;

    /**
     * mensaje de error enviado por el motor de base de datos
     * @var string
     */
    private $mensajeError;


    /**
     * constructor
     */
    function __construct($fld = '"""+folder+"""'){
        parent::__construct($fld);
        $data = $this->datosPeticion;
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if(isset($data[$this->Controller->id]))
                    $result = $this->show($data);
                else
                    $result = $this->index($data);
                break;
            case 'POST':
                $result = $this->create($data);
                break;
            case 'PUT':
                $result = $this->update($data);
                break;
        }
        $this->mostrarRespuesta(json_encode($result), $result['code']);
    }
}
new """+name+"""();
?>"""
        return service