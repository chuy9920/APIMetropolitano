<?php
header('Content-Type: application/json');
require 'AutobusDAO.php';

class AutobusService{
    private $autobusDAO;
    
    public function __construct() {
        $this->autobusDAO  = new AutobusDAO();
    }
    public function create($id_ruta, $modelo, $anio, $marca){
        $last_insert_id = $this->autobusDAO->create($id_ruta, $modelo, $anio, $marca);
        
        if($last_insert_id > 0){
            $this->read($last_insert_id);
        } else {
            echo json_encode($array());
        }
        
    }
    
    public function read($id_autobus){
        $autobus = $this->autobusDAO->read($id_autobus);
        
        echo json_encode($autobus, JSON_PRETTY_PRINT);
    }
    
    public function update($id_autobus, $id_ruta, $modelo, $anio, $marca){
        $id_autobus = $this->autobusDAO->update($id_autobus, $id_ruta, $modelo, $anio, $marca);
        
        if($id_autobus > 0){
            $this->read($id_autobus);
        } else {
            echo json_encode($array());
        }
    }
    
    public function delete($id_autobus){
        $id_autobus = $this->autobusDAO->delete($id_autobus);
        
        if($id_autobus > 0){
           echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }
}

$service = new AutobusService();

switch ($_SERVER['REQUEST_METHOD']){
    case "GET":{
        
        
        if(empty($_GET['param'])){
            $service->read(0);
        } else {
            if(is_numeric($_GET['param'])){
                $service->read($_GET['param']);
            }
        }
    }
    
    case "POST":{
        $service->create($_POST['id_ruta'], $_POST['modelo'], $_POST['anio'], $_POST['marca']);
        break;
    }
    
    case "PUT": {
        parse_str(file_get_contents('php://input'),$_PUT);
        $service->update($_PUT['id_autobus'], $_PUT['id_ruta'], $_PUT['modelo'], $_PUT['anio'], $_PUT['marca']);
        break;
    }
    
    case "DELETE":{
        parse_str(file_get_contents('php://input'),$_DELETE);
        $service->delete($_DELETE['id_autobus']);
        break;
    }
}

