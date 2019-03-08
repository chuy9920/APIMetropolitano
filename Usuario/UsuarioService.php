<?php

header('Content-Type: application/json');

require 'UsuarioDAO.php';

class UsuarioService{
    private $usuario;
    private $usuarioDAO;
    
    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }
   
    public function create($nombre, $apellido_p, $apellido_m, $correo, $fecha_na, $contrasena){
        $last_inset_id = $this->usuarioDAO->create($nombre, $apellido_p, $apellido_m, $correo, $fecha_na, $contrasena);
        
        if($last_inset_id > 0){
            $this->read($last_inset_id);
        } else {
            echo json_encode(array());
        }
    }
    
    public function read($id_usuario){
        
        $usuario = $this->usuarioDAO->read($id_usuario);
        //print_r($usuario);
        try{
            echo json_encode($usuario, JSON_PRETTY_PRINT);
        } catch (Exception $ex) {
            echo $ex;
            die();
        }
    }
    
    public function update($id_usuario, $nombre, $apellido_p, $apellido_m, $correo, $fecha_na, $contrasena){
        $id_usuario = $this->usuarioDAO->update($id_usuario, $nombre, $apellido_p, $apellido_m, $correo, $fecha_na, $contrasena);
        
        if($id_usuario > 0){
            $this->read($id_usuario);
        } else {
            json_encode(array());
        }
    }
    
    public function delete($id_usuario){
        $id_usuario = $this->usuarioDAO->delete($id_usuario);
        if($id_usuario > 0){
            echo json_encode(array('status' => true), JSON_PRETTY_PRINT);
        } else {
            echo json_encode(array('status' => false));
        }
    }
}


$service = new UsuarioService();

switch ($_SERVER['REQUEST_METHOD']){
    case "GET": {
        //$service->delete(1);
        //$service->update(2, 'Jesus', 'Morales', 'Fernandez', 'chuy99_mf@hotmail.com', '1999-07-20', 'JesusMF');
       // $service->create('Jose', 'Morales', 'Fernandez', 'jose_mf@gotmail.com', '2006-04-18', 'JoseMF');
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
        $service->create($_POST['nombre'], $_POST['apellido_p'], $_POST['apellido_m'], $_POST['correo'], $_POST['fecha_na'], $_POST['contrasena']);
        break;;
    }
    
    case "PUT":{
        parse_str(file_get_contents('php://input'), $_PUT);
        $service->update($_PUT['id_usuario'], $_PUT['nombre'], $_PUT['apellido_p'], $_PUT['apellido_m'], $_PUT['correo'], $_PUT['fecha_na'], $_PUT['contrasena']);
        break;
    }
    
    case "DELETE":{
            parse_str(file_get_contents('php://input'), $_DELETE);
            $service->delete($_DELETE['id_usuario']);
            break;
    }
}

        