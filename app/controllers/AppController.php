<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/Services/app/assets/Doctrine.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Services/app/assets/UUID.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Services/app/config/Conection.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/Services/app/assets/jwt/JWT.php');
//  	require_once("./Aplication.php");

	/**
	 * Application controllers
	 *
	 * Add your application-wide methods in the class below, your controllers
	 * will inherit them.
	 *
	 * @package		app.controllers
	 * 
	 */
	class AppController
	{
        public function __construct($db = null, $table = null, $dbm = 0){
//            parent::__construct();
//            echo $db . ' ' . $table;
            $this->key = "secure key";
            if(!is_null($db) && !is_null($table)) {
                try {
                    switch ($dbm) {
                        case 0:
                            $conexion = new Conection($db, 'mysql', 'host','user', 'password');
                            break;
                        case 1:
                            $conexion = new Conection($db, 'pgsql', 'host','user', 'password');
                            break;
                    }
//                    $conexion->SetBaseDatos($db);

                    spl_autoload_register(array('Doctrine', 'autoload'));
                    $this->conn = Doctrine_Manager::connection($conexion->getStringConnDoctrine(), 'doctrine');
                    $this->conn->connect();

                    $this->conn->setCharset('utf8');
                    Doctrine_Core::loadModels(array($_SERVER['DOCUMENT_ROOT'] . '/Services/app/db/' . $db . '/generated', $_SERVER['DOCUMENT_ROOT'] . '/Services/app/db/' . $db . '/models'));
                    $this->table=$table;
                    $this->Table = Doctrine_Core::getTable($table);
                    $this->sql = $this->getSqlData($table);
                    $this->id = $this->Table->getIdentifier();
//                    echo $this->Table->getTypeOf($this->id);
                } catch (Exception $e) {
                    exit($e->getMessage());
                }
            }
        }


        public function index($arr=array())
        {
            try
            {
                $first = true;
                $Table = $this->Table;
                $Entity = $Table;
                $columns = $Table->getFieldNames();
                $Table = $Table->createQuery($this->table)
                    ->select($this->sql['query']);

                foreach ($this->sql['relations'] as $relation){
                    $Table = $Table->leftJoin($this->table.'.'.$relation.' as '.$relation);
                }
                foreach($arr as $key => $value)
                {
                    $key = str_replace('-','.',$key);
                    if(!(array_search($key, $columns) === false) || !(array_search($key, $this->sql['filters']) === false))
                    {
                        if($value != null)
                        {
                            if($first) {
                                if ($this->Table->getTypeOf($key) != 'string')
                                    $Table = $Table->where($key . ' = ?', array($value));
                                else
                                    $Table = $Table->where($key . ' like ?', array('%' . $value . '%'));
                                $first=false;
                            }else{
                                if ($this->Table->getTypeOf($key) != 'string')
                                    $Table = $Table->andWhere($key . ' = ?', array($value));
                                else
                                    $Table = $Table->andWhere($key . ' like ?', array('%' . $value . '%'));
                            }
                        }
                    }
                }
                if(!isset($arr['start']))
                    $arr['start'] = 0;
                if(!isset($arr['limit']))
                    $arr['limit'] = 25;
                if(!isset($arr['order']))
                    $arr['order']=$this->table.'.'.$this->id . ' DESC';
                else
                    $arr['order'] = str_replace('-','.',$arr['order']);

                $TableCount = $Table;
                $Table = $Table->orderBy($arr['order'])->offset($arr['start'])->limit($arr['limit'])->execute();
                $result = array("data"=>$Table->toArray(), "total"=>$TableCount->Count(), "code"=>200);
            }catch(Exception $ex){
                $result = array('msg'=> $ex->getMessage(), 'code'=>500);
            }
            return $result;
        }

        public function show($arr=array()){
            try{
                $Table = $this->Table->createQuery($this->table)->select($this->sql['query']);
                foreach ($this->sql['relations'] as $relation){
                    $Table = $Table->leftJoin($this->table.'.'.$relation.' as '.$relation);
                }
                $Table = $Table->where($this->table.'.'.$this->id.'=?',array($arr[$this->id]))
                    ->execute();
                $result = array("data"=>$Table[0]->toArray(), "code"=>200, "success"=>true);
            }catch(Exception $e){$result = array('msg'=> $e->getMessage(), 'code'=>500);}
            return $result;
        }

        public function create($arr=array())
        {
            try
            {
//                var_dump($arr);
                $Entity = new $this->table;
                $columnsEntity = $this->Table->getFieldNames();

                if($this->Table->getTypeOf($this->id)=='uuid' || $this->Table->getTypeOf($this->id)=='string')
                    $Entity[$this->id] = UUID::v4();

                foreach ($arr as $key => $value)
                {
                    if(!(array_search($key, $columnsEntity) === false)  ) {
                        if ($value != null) {
                            if (isset($Entity[$key]) && $key != $this->id)
                                $Entity[$key] = $value;
                        }
                    }
                }

                $Entity->save();
                $result = array("data"=>$Entity->toArray(), "code"=>200, "msg"=>"Creado exitosamente","success"=>true);
            }
            catch(Exception $e)
            {
                $result = array('msg'=> $e->getMessage(), 'code'=>500);
            }
            return $result;
        }

        public function update($arr=array())
        {
            if (isset($arr[$this->id])) {
                try {
                    $Entity = $this->Table->findOneBy($this->id,$arr[$this->id]);
                    foreach ($arr as $key => $value) {
                        if($Entity != null)
                        {
                            if (isset($Entity[$key]))
                            {
                                $Entity[$key] = $value;
                            }
                        }

                    }
                    $result= $Entity->save();

                    return array("data" => $result, "code" => 200, "msg" => "Actualizado exitosamente", "success" => true);
                } catch (Exception $e) {
                    $result = array('msg' => $e->getMessage(), "success"=>false, 'code' => 500);
                }
            } else {
                $result = array('msg' => $this->id.' es un campo obligatorio', "success"=>false, 'code' => 422);
            }
            return $result;
        }

        public function delete($arr = array())
        {
            if (isset($arr[$this->id])) {
                try {
                    $Table = Doctrine_Core::getTable($this->table)->findBy($this->id, $arr[$this->id]);
                    $result = $Table->delete();
                    return array("data" => $result, "code" => 200, "msg" => "Eliminado exitosamente", "success" => true);
                } catch (Exception $e) {
                    $result = array('msg' => $e->getMessage(), "success" => false, 'code' => 500);
                }
            } else {
                $result = array('msg' => $this->id.' es un campo obligatorio', "success" => false, 'code' => 422);
            }
            return $result;
        }

        public function getSqlData($table = null){
            $query = $table.'.*';
            if(!is_null($table)) {
                try {
                    $Table = $this->Table;
                    $relations = $Table->getRelations($Table);
                    $relationsAlias = array_keys($relations);
                    $filters = array();
                    foreach ($relationsAlias as $relation) {//var_dump($relations[$relation]['class']);
                        $query .= ', '.$relation.'.*';
                        foreach (Doctrine_Core::getTable($relations[$relation]['class'])->getFieldNames() as $field) {
                            array_push($filters,$relation . "." . $field);
                        }
                    }
                    $result = array('filters'=>$filters, 'relations'=>$relationsAlias, 'query'=>$query);
                }catch(Exception $e)
                {
                    $result = array('filters'=>array(), 'relations'=>array(), 'query'=>$query, 'msg'=> $e->getMessage());
                }
            }else{
                $result = array('filters'=>array(), 'relations'=>array(), 'query'=>$query);
            }
            return $result;
        }

        /**
         * @return Doctrine_Connection
         */
        public function isAuthorized($token)
        {
            if(empty($token))
            {
                //throw new Exception("Invalid token supplied.");
                $result = false;
            }else {
                try {
                    JWT::verify($token);
                    $token = JWT::decode($token, $this->key, array('HS256'));
                    $result = true;
                } catch (Exception $e) {
                    $result = false;
                }
            }
            return $result;
        }
    }
?>
