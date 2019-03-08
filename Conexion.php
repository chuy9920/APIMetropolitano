<?php

class Conexion{
    private $host = "";
    private $user = "";
    private $password = "";
    private $dataBase = "";
    
    private $objConexion;
    
    public function __construct() {
        $this->host = "localhost";
        $this->user = "root";
        $this->password = "";
        $this->dataBase = "metropolitano01";
    }
    
    public function openConnection(){
        try {
            $this->objConexion = new PDO("mysql:host{$this->host};dbname={$this->dataBase}", $this->user, $this->password);
            $this->objConexion->exec("set names utf8"); 
            $this->objConexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e;
            $this->objConexion = false;
        }
    }
    
    public function closeConnection($objConnection1){
        mysqli_close($objConnection1);
    }
    
    public function getConnection(){
        return $this->objConexion;
    }
    
}
/*
 * Prueba de conexión pasada
$obj = new Conexion();
$obj->openConnection();

if($obj->getConnection()){
    echo 'Ok';
}*/

?>