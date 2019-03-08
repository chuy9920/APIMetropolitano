<?php
require '../Conexion.php';

class ParadaDAO{
    private $objConexion;
    
    public function __construct() {
        $this->objConexion = new Conexion();
    }
    
    public function create($latitud, $longitud, $avenida, $calle){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $last_insert_id = 0;
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "INSERT INTO metropolitano01.parada VALUES(DEFAULT, :latitud,"
                        . ":longitud,"
                        . ":avenida,"
                        . ":calle);"
                        );
                $sqlStatement->bindParam(':latitud', $latitud);
                $sqlStatement->bindParam(':longitud', $longitud);
                $sqlStatement->bindParam(':avenida', $avenida);
                $sqlStatement->bindParam(':calle', $calle);
                $sqlStatement->execute();
                $last_insert_id = $con->lastInsertId();
               /* echo $con->lastInsertId();
                die();*/
                $con->commit();
                
            }
        } catch (PDOException $e) {
            echo $e;
            die();
            $con->rollBack();
        }
        /*echo $last_insert_id;
        die();*/
        return $last_insert_id;
    }
    
    public function read($id_parada){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $arrayParada = array();
        $sql = "SELECT * FROM metropolitano01.parada";
        
        if($id_parada > 0 ) {
            $sql =  "SELECT * FROM metropolitano01.parada WHERE id_parada = {$id_parada}";
        }
        
        if($con) {
            foreach ($con->query($sql) as $row){
                $arrayParada[] = array(
                    'id_parada' => $row['id_parada'],
                    'latitud' => $row['latitud'],
                    'longitud' => $row['longitud'],
                    'avenida' => $row['avenida'],
                    'calle' => $row['calle']
                );
                
            }
            return $arrayParada;
        }
    }
    
    public function update($id_parada, $latitud, $longitud, $avenida, $calle){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "CALL metropolitano01.actu_parada(:id_parada,"
                        . ":latitud,"
                        . ":longitud,"
                        . ":avenida,"
                        . ":calle);"
                        );
                $sqlStatement->bindParam(':id_parada', $id_parada);
                $sqlStatement->bindParam(':latitud', $latitud);
                $sqlStatement->bindParam(':longitud', $longitud);
                $sqlStatement->bindParam(':avenida', $avenida);
                $sqlStatement->bindParam(':calle', $calle);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_parada = 0;
            $con->rollBack();
            echo $e;
            die();
        }
        return $id_parada;
    }
    
    public function delete($id_parada){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "DELETE FROM metropolitano01.parada "
                        . " WHERE id_parada = :id_parada"
                        );
                
                $sqlStatement->bindParam(':id_parada', $id_parada);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_parada = 0;
            $con->rollBack();
            echo $e;
            die();
        }
        return $id_parada;
    }
}
