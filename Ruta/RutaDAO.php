<?php
require '../Conexion.php';

class RutaDAO{
    private $objConexion;
    
    public function __construct() {
        $this->objConexion = new Conexion;
    }
    
    public function create($nombre){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $last_insert_id = 0;
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "INSERT INTO metropolitano01.ruta VALUES(default,"
                        . ":nombre)"
                        );
                $sqlStatement->bindParam(':nombre', $nombre);
                $sqlStatement->execute();
                $last_insert_id = $con->lastInsertId();
                $con->commit();
            }
        } catch (PDOException $e) {
            $con->rollBack();
            //DEBUG
            echo $e;
            die();
        }
        return $last_insert_id;
    }
    
    public function read($id_ruta){
        $this->objConexion->openConnection();
        $cor = $this->objConexion->getConnection();
        
        $arrayRuta = array();
        
        $sql = "SELECT * FROM metropolitano01.ruta";
        if($id_ruta > 0){
            $sql = "SELECT * FROM metropolitano01.ruta WHERE id_ruta = {$id_ruta}";
        }
        
        if($cor){
            foreach ($cor->query($sql) as $row){
                $arrayRuta[] = array(
                    'id_ruta' => $row['id_ruta'],
                    'nombre' => $row['nombre']
                );
            }
        }
        return $arrayRuta;
    }
    
    public function update($id_ruta, $nombre){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "UPDATE metropolitano01.ruta SET nombre = :nombre"
                        . " WHERE id_ruta = :id_ruta"
                        );
                $sqlStatement->bindParam(':id_ruta', $id_ruta);
                $sqlStatement->bindParam(':nombre', $nombre);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_ruta = 0;
            $con->rollBack();
            //DEBUG
            echo $e;
            die();
        }
        return $id_ruta;
    }
    
    public function delete($id_ruta){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "DELETE FROM metropolitano01.ruta "
                        . " WHERE id_ruta = :id_ruta"
                        );
                $sqlStatement->bindParam(':id_ruta', $id_ruta);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_ruta = 0;
            $con->rollback();
            //DEBUG
            echo $e;
            die();
        }
        return $id_ruta;
    }
}
