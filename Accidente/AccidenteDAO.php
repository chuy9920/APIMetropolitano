<?php
require '../Conexion.php';

class AccidenteDAO{
    private $objConexion;
    
    public function __construct() {
        $this->objConexion = new Conexion();
    }
    
    public function create($id_usuario, $descripcion){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $last_insert_id = 0;
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStetement = $con->prepare(
                        "INSERT INTO metropolitano01.accidente VALUES(DEFAULT,"
                                                    . ":id_usuario,"
                                                    . ":descripcion)"
                        );
                $sqlStetement->bindParam(':id_usuario', $id_usuario);
                $sqlStetement->bindParam(':descripcion', $descripcion);
                $sqlStetement->execute();
                
                $last_insert_id = $con->lastInsertId();
                $con->commit();
            }
        } catch (PDOException $e) {
            $con->rollBack();
            //Lineas para debuguear la app
            /*
            echo $e;
            die();*/
        }
        return $last_insert_id;
    }
    
    public function read($id_accidente){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $arrayAccidente = array();
        $sql = 'SELECT * FROM metropolitano01.accidente';
        
        if($id_accidente > 0){
            $sql = "SELECT * FROM metropolitano01.accidente WHERE id_accidente = {$id_accidente}";
        }
        
        if($con){
            foreach ($con->query($sql) as $row){
                $arrayAccidente[] = array('id_accidente' => $row['id_accidente'],
                                            'id_usuario' => $row['id_usuario'],
                                            'descripcion' => $row['descripcion']);
            }
        }
        return $arrayAccidente;
    }
    
    public function update($id_accidente, $id_usuario, $descripcion){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "UPDATE metropolitano01.accidente SET "
                        . "id_usuario = :id_usuario,"
                        . " descripcion = :descripcion"
                        . " WHERE id_accidente = :id_accidente"
                        );
                $sqlStatement->bindParam(':id_usuario', $id_usuario);
                $sqlStatement->bindParam(':descripcion', $descripcion);
                $sqlStatement->bindParam(':id_accidente', $id_accidente);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_accidente = 0;
            $con->rollBack();
            //Lineas para debug, eliminar después de terminar pruebas
            echo $e;
            die();
        }
        return $id_accidente;
    }
    
    public function delete($id_accidente){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try{
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "DELETE FROM metropolitano01.accidente"
                        . " WHERE id_accidente = :id_accidente"
                        );
                $sqlStatement->bindParam(':id_accidente', $id_accidente);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_accidente = 0;
            $con->rollBack();
            //DEBUG
            echo $e;
            die();
        }
        return $id_accidente;
    }
}

