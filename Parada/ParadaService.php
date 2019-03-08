<?php

header('Content-Type: application/json');

require 'ParadaDAO.php';

class ParadaService{
    private $paradaDAO;
    
    public function __construct() {
        $this->paradaDAO = new ParadaDAO();
    }
    
    public function create($latitud, $longitud, $avenida, $calle){
        $last_insert_id = $this->paradaDAO->create($latitud, $longitud, $avenida, $calle);
        
        if($last_insert_id > 0){
            $this->read($last_insert_id);
        } else {
            echo json_encode(array());
        }
    }
    
    public function read($id_parada){
        $parada = $this->paradaDAO->read($id_parada);
        
        echo json_encode($parada, JSON_PRETTY_PRINT);
    }
    
    public function update($id_parada, $latitud, $longitud, $avenida, $calle){
        $id_parada = $this->paradaDAO->update($id_parada, $latitud, $longitud, $avenida, $calle);
        
        if($id_parada > 0){
            $this->read($id_parada);
        } else {
            echo json_encode(array());
        }
    }
    
    public function delete($id_parada){
        $id_parada = $this->paradaDAO->delete($id_parada);
        
        if($id_parada > 0){
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }
}

$service = new ParadaService();

switch ($_SERVER['REQUEST_METHOD']){
    case "GET": {
        if(empty($_GET['param'])){
            $service->read(0);
        } else {
            if(is_numeric($_GET['param'])){
                $service->read($_GET['param']);
            }
        }
        break;
    }
    case "POST": {
        $service->create($_POST['latitud'], $_POST['longitud'], $_POST['avenida'], $_POST['calle']);
        break;
    }
    
    case "PUT": {
        parse_str(file_get_contents('php://input'),$_PUT);
        $service->update($_PUT['id_parada'], $_PUT['latitud'], $_PUT['longitud'], $_PUT['avenida'], $_PUT['calle']);
        break;
    }
    
    case "DELETE": {
        parse_str(file_get_contents('php://input'),$_DELETE);
        $service->delete($_DELETE['id_parada']);
        break;
    }
}