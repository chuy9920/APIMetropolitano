<?php
header('Content-Type: application/json');

require 'RutaDAO.php';

class RutaService {
    private $rutaDAO;
    
    public function __construct() {
        $this->rutaDAO = new RutaDAO();
    }
    
    public function create($nombre){
        $last_insert_id = $this->rutaDAO->create($nombre);
        
        if($last_insert_id > 0){
            $this->read($last_insert_id);
        } else {
            echo json_encode($array());
        }
    }
    
    public function read($id_ruta){
        $ruta = $this->rutaDAO->read($id_ruta);
        
        echo json_encode($ruta, JSON_PRETTY_PRINT);
    }
    
    public function update($id_ruta, $nombre){
        $id_ruta = $this->rutaDAO->update($id_ruta, $nombre);
        
        if($id_ruta > 0){
            $this->read($id_ruta);
        } else {
            echo json_encode($array());
        }
    }
    
    public function delete($id_ruta){
        $id_ruta = $this->rutaDAO->delete($id_ruta);
        
        if($id_ruta > 0){
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }
}

$service = new RutaService();

switch($_SERVER['REQUEST_METHOD']){
    case "GET":{
        if(empty($_GET['param'])){
            $service->read(0);
        } else{
            if(is_numeric($_GET['param'])){
                $service->read($_GET['param']);
            }
        }
        break;
    }
    
    case "POST":{
        $service->create($_POST['nombre']);
        break;
    }
    
    case "PUT": {
        parse_str(file_get_contents('php://input'),$_PUT);
        $service->update($_PUT['id_ruta'], $_PUT['nombre']);
        break;
    }
    
    case "DELETE":{
        parse_str(file_get_contents('php://input'),$_DELETE);
        $service->delete($_DELETE['id_ruta']);
        break;
    }
}