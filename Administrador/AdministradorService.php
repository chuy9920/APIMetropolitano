<?php
header('Content-Type: application/json');
require 'AdministradorDAO.php';

class AdministradorService {
    private  $administradorDAO;
    
    public function __construct() {
        $this->administradorDAO = new AdministradorDAO();
    }
    
    public function create($nombre_a, $apellido_pa, $apellido_ma, $correo, $contrasena){
        $last_insert_id = $this->administradorDAO->create($nombre_a, $apellido_pa, $apellido_ma, $correo, $contrasena);
        
        if($last_insert_id > 0){
            $this->read($last_insert_id);
        } else {
            echo json_encode(array());
        }
    }
    public function read($id_administrador){
        $administrador = $this->administradorDAO->read($id_administrador);
        echo json_encode($administrador, JSON_PRETTY_PRINT);
    }
    
    public function update($id_administrador, $nombre_a, $apellido_pa, $apellido_ma, $correo, $contrasena){
        $id_administrador = $this->administradorDAO->update($id_administrador, $nombre_a, $apellido_pa, $apellido_ma, $correo, $contrasena);
        
        if($id_administrador > 0) {
            $this->read($id_administrador);
        } else {
            echo json_encode(array());
        }
    }
    
    public function delete($id_administrador){
        $id_administrador = $this->administradorDAO->delete($id_administrador);
        
        if($id_administrador > 0){
            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array('status' => false));
        }
    }
}

$service = new AdministradorService();

switch ($_SERVER['REQUEST_METHOD']) {
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

    case "POST": {
        $service->create($_POST['nombre_a'], $_POST['apellido_pa'], $_POST['apellido_ma'], $_POST['correo'], $_POST['contrasena']);
        break;
    }
    
    case "PUT":{
            parse_str(file_get_contents('php://input'), $_PUT);
            $service->update($_PUT['id_administrador'], $_PUT['nombre_a'], $_PUT['apellido_pa'], $_PUT['apellido_ma'], $_PUT['correo'], $_PUT['contrasena']);
            break;
    }
    
    case "DELETE":{
        parse_str(file_get_contents('php://input'), $_DELETE);
        $service->delete($_DELETE['id_administrador']);
        break;
    }
        

    default:
        break;
}