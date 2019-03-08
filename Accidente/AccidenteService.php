<?php
header('Content-Type: application/json');
require 'AccidenteDAO.php';

class AccidenteService{
    private $accidenteDAO;
    
    public function __construct() {
        $this->accidenteDAO = new AccidenteDAO();
    }
    
    public function create($id_usuario, $descripcion){
        $last_insert_id = $this->accidenteDAO->create($id_usuario, $descripcion);
        
        if($last_insert_id > 0){
            $this->read($last_insert_id);
        } else {
            echo json_encode(array());
        }
    }
    
    public function read($id_accidente){
        $accidente = $this->accidenteDAO->read($id_accidente);
       // print_r($accidente);
        echo json_encode($accidente, JSON_PRETTY_PRINT);
    }
    
    public function update($id_accidente, $id_usuario, $descripcion){
        $id_accidente = $this->accidenteDAO->update($id_accidente, $id_usuario, $descripcion);
        
        if($id_accidente > 0){
            $this->read($id_accidente);
        } else {
            echo json_encode(array());
        }
    }
    
    public function delete($id_accidente){
        $id_accidente = $this->accidenteDAO->delete($id_accidente);
        
        if($id_accidente > 0) {
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }
}

$service = new AccidenteService();

switch ($_SERVER['REQUEST_METHOD']) {
    
    //Faltan los demás métodos
    case "GET":{
        if(empty($_GET['param'])){
            $service->read(0);
        } else {
            if(is_numeric($_GET['param'])){
                $service->read($_GET['param']);
            }
        }
        break;
    }
    
    case "POST":{
        $service->create($_POST['id_usuario'], $_POST['descripcion']);
        break;
    }
    
    case "PUT":{
        parse_str(file_get_contents('php://input'), $_PUT);
        $service->update($_PUT['id_accidente'], $_PUT['id_usuario'], $_PUT['descripcion']);
    }

    case "DELETE": {
            parse_str(file_get_contents('php://input'), $_DELETE);
            $service->delete($_DELETE['id_accidente']);
    }
    default:
        break;
}