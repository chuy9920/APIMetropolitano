<?php
require '../Conexion.php';

class AutobusDAO{
    private $objConexion;
    
    public function __construct() {
        $this->objConexion = new Conexion();
    }
    
    public function create($id_ruta, $modelo, $anio, $marca){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $last_insert_id = 0;
        
        try{
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "INSERT INTO metropolitano01.autobus VALUES(default,"
                        . ":id_ruta,"
                        . ":modelo,"
                        . ":anio,"
                        . ":marca)"
                        );
                
                $sqlStatement->bindParam(':id_ruta', $id_ruta);
                $sqlStatement->bindParam(':modelo', $modelo);
                $sqlStatement->bindParam(':anio', $anio);
                $sqlStatement->bindParam(':marca', $marca);
                $sqlStatement->execute();
                
                $last_insert_id = $con->lastInsertId();
                $con->commit();
            }
        } catch (PDOException $e) {
            $con->rollBack();
            echo $e;
            die();
        }
        return$last_insert_id;
    }
    
    public function read($id_autobus){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $arrayAutobus = array();
        
        $sql = "SELECT * FROM metropolitano01.vista_autobus";
        
        if($id_autobus > 0){
            $sql = "SELECT * FROM metropolitano01.vista_autobus WHERE id_autobus = {$id_autobus}";
        }
        if($con){
            foreach ($con->query($sql) as $row){
                $arrayAutobus[] = array('id_autobus' => $row['id_autobus'],
                                        'id_ruta' => $row['id_ruta'],
                                        'modelo' => $row['modelo'],
                                        'anio' => $row['anio'],
                                        'marca' => $row['marca']);
            }
        }
        return $arrayAutobus;
    }
    
    public function update($id_autobus, $id_ruta, $modelo, $anio, $marca){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "CALL metropolitano01.edit_autobus(:id_autobus,"
                        . ":id_ruta,"
                        . ":modelo,"
                        . ":anio,"
                        . ":marca)"
                        );
                $sqlStatement->bindParam(':id_autobus', $id_autobus);
                $sqlStatement->bindParam(':id_ruta', $id_ruta);
                $sqlStatement->bindParam(':modelo', $modelo);
                $sqlStatement->bindParam(':anio', $anio);
                $sqlStatement->bindParam(':marca', $marca);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_autobus = 0;
            $con->rollBack();
            //DEBUG
            echo $e;
            die();
        }
        return $id_autobus;
    }
    
    public function delete($id_autobus){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "DELETE FROM metropolitano01.autobus "
                        . " WHERE id_autobus = :id_autobus"
                        );
                
                $sqlStatement->bindParam(':id_autobus', $id_autobus);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_autobus = 0;
            $con->rollBack();
            //DEBUG
            echo $e;
            die();
        }
        return $id_autobus;
    }
}
