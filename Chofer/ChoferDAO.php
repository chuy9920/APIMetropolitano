<?php

require '../Conexion.php';

class ChoferDAO{
    private $objConexion;
    
    public function __construct() {
        $this->objConexion = new Conexion();
    }
    //Verificar la fecha de nacimientp
    public function create($nombre_c, $apellido_pc, $apellido_mc, $fecha_nac, $id_autobús){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $last_insert_id = 0;
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                            "INSERT INTO metropolitano01.chofer VALUES(default,"
                                                    . ":nombre_c,"
                                                    . ":apellido_pc,"
                                                    . ":apellido_mc,"
                                                    . ":fecha_nac,"
                                                    . ":id_autobus)"
                        );
                $sqlStatement->bindParam(':nombre_c',$nombre_c);
                $sqlStatement->bindParam(':apellido_pc',$apellido_pc);
                $sqlStatement->bindParam(':apellido_mc',$apellido_mc);
                $sqlStatement->bindParam(':fecha_nac',$fecha_nac);
                $sqlStatement->bindParam(':id_autobus',$id_autobús);
                $sqlStatement->execute();
                
                $last_insert_id = $con->lastInsertId();
                $con->commit();
            }
        } catch (PDOException $e) {
            $con->rollback();
            echo $e;
            die();
        }
        return $last_insert_id;
    }
    
    public function read($id_chofer){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $arrayChofer = array();
        
        $sql = "SELECT * FROM metropolitano01.vista_chofer";
        if($id_chofer >0){
            $sql = "SELECT * FROM metropolitano01.vista_chofer WHERE id_chofer = $id_chofer";
        }
        
        if($con){
            foreach ($con->query($sql) as $row){
                $arrayChofer[] = array('id_chofer' => $row['id_chofer'],
                                        'nombre_c' => $row['nombre_c'],
                                        'apellido_pc' => $row['apellido_pc'],
                                        'apellido_mc' => $row['apellido_mc'],
                                        'fecha_nac' => $row['fecha_nac'],
                                        'id_autobus' => $row['id_autobus'],
                                        'modelo' => $row['modelo'],
                                        'anio' => $row['anio'],
                                        'marca' => $row['marca']);
            }
        }
        return $arrayChofer;
        
    }
    
    public function update($id_chofer, $nombre_c, $apellido_pc, $apellido_mc, $fecha_nac, $id_autobús){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "UPDATE metropolitano01.chofer SET "
                        . "nombre_c = :nombre_c,"
                        . "apellido_pc = :apellido_pc,"
                        . "apellido_mc = :apellido_mc,"
                        . "fecha_nac = :fecha_nac,"
                        . "id_autobus = :id_autobus"
                        . " WHERE id_chofer = :id_chofer"
                        );
                $sqlStatement->bindParam(':nombre_c', $nombre_c);
                $sqlStatement->bindParam(':apellido_pc', $apellido_pc);
                $sqlStatement->bindParam(':apellido_mc', $apellido_mc);
                $sqlStatement->bindParam(':fecha_nac', $fecha_nac);
                $sqlStatement->bindParam(':id_autobus', $id_autobús);
                $sqlStatement->bindParam(':id_chofer', $id_chofer);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            echo $e;
            die();
            $id_chofer = 0;
            $con->rollBack();
        }
        return $id_chofer;
    }
    
    public function delete($id_chofer){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "DELETE FROM metropolitano01.chofer"
                        . " WHERE id_chofer = :id_chofer"
                        );
                $sqlStatement->bindParam(':id_chofer', $id_chofer);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            
            echo $e;
            die();
            $id_chofer = 0;
            $con->rollBack();
        }
        return $id_chofer;
    }
}


