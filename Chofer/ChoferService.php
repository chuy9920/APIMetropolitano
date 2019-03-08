<?php
header('Content-Type: application/json');

require 'ChoferDAO.php';

class ChoferService {
    private $choferDAO;
    
    public function __construct() {
        $this->choferDAO = new ChoferDAO();
    }
    
    public function create($nombre_c, $apellido_pc, $apellido_mc, $fecha_nac, $id_autobús){
        $last_insert_id = $this->choferDAO->create($nombre_c, $apellido_pc, $apellido_mc, $fecha_nac, $id_autobús);
        
        if($last_insert_id > 0){
            $this->read($last_insert_id);
        } else {
            echo json_encode(array());
        }
    }
    
    public function read($id_chofer){
        try {
            $chofer = $this->choferDAO->read($id_chofer);
            echo json_encode($chofer, JSON_PRETTY_PRINT);
            echo 'aquí está';
        } catch (Exception $exc) {
            
        }

        
       // 
    }
    
    public function update($id_chofer, $nombre_c, $apellido_pc, $apellido_mc, $fecha_nac, $id_autobús){
        $id_chofer = $this->choferDAO->update($id_chofer, $nombre_c, $apellido_pc, $apellido_mc, $fecha_nac, $id_autobús);
        
        if($id_chofer > 0){
            $this->read($id_chofer);
        } else {
            echo json_encode(array());
        }
    }
    
    public function delete($id_chofer){
        $id_chofer = $this->choferDAO->delete($id_chofer);
        
        if($id_chofer > 0){
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }
}

$service = new ChoferService();

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET": {
        //$service->read(0);
        //$service->create("José", "Morales", "Fernández", "2006-04-18", 1);
        //$service->delete(15);
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
        $service->create($_POST['nombre_c'], $_POST['apellido_pc'], $_POST['apellido_mc'], $_POST['fecha_nac'], $_POST['id_autubus']);
        break;
    }
    case "PUT":{
            parse_str(file_get_contents('php://input'), $_PUT);
            $service->update($_PUT['id_chofer'], $_PUT['nombre_c'], $_PUT['apellido_pc'], $_PUT['apellido_mc'], $_PUT['fecha_nac'], $_PUT['id_autubus']);
            break;
    }
    
    case "DELETE": {
            parse_str(file_get_contents('php://input'), $_DELETE);
            $service->delete($_DELETE['id_chofer']);
            break;
    }
}