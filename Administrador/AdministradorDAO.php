<?php
require '../Conexion.php';

class AdministradorDAO{
    private $objConexion;
    
    public function __construct() {
        $this->objConexion = new Conexion();
    }
    
    public function create($nombre_a, $apellido_pa, $apellido_ma, $correo, $contrasena){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $last_insert_id = 0;
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "INSERT INTO metropolitano01.administrador VALUES(default,"
                                                                        . ":nombre_a,"
                                                                        . ":apellido_pa,"
                                                                        . ":apellido_ma,"
                                                                        . ":correo,"
                                                                        . ":contrasena)"
                        );
                $sqlStatement->bindParam(':nombre_a', $nombre_a);
                $sqlStatement->bindParam(':apellido_pa', $apellido_pa);
                $sqlStatement->bindParam(':apellido_ma', $apellido_ma);
                $sqlStatement->bindParam(':correo', $correo);
                $sqlStatement->bindParam(':contrasena', $contrasena);
                
                $sqlStatement->execute();
                
                $last_insert_id = $con->lastInsertId();
                $con->commit();

                }
        } catch (PDOException $e) {
            $con->rollBack();
            //Debug
            echo $e;
            die();
        }
        return $last_insert_id;
    }
    
    public function read($id_administrador){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $arrayAdministrador = array();
        $sql = 'SELECT * FROM metropolitano01.administrador';
        
        if($id_administrador > 0){
            $sql = "SELECT * FROM metropolitano01.administrador WHERE id_administrador = {$id_administrador}";
        }
        
        if($con){
            foreach ($con->query($sql) as $row){
                $arrayAdministrador[] = array('id_administrador' => $row['id_administrador'],
                                                'nombre_a' => $row['nombre_a'],
                                                'apellido_pa' => $row['apellido_pa'],
                                                'apellido_ma' => $row['apellido_ma'],
                                                'correo' => $row['correo'],
                                                'contrasena' => $row['contrasena']);
            }
        }
        return $arrayAdministrador;
    }
    
    public function update($id_administrador, $nombre_a, $apellido_pa, $apellido_ma, $correo, $contrasena){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                
                $sqlStatement = $con->prepare(
                        "UPDATE metropolitano01.administrador SET "
                        . "nombre_a = :nombre_a,"
                        . "apellido_pa = :apellido_pa,"
                        . "apellido_ma = :apellido_ma,"
                        . "correo = :correo,"
                        . "contrasena = :contrasena"
                        . " WHERE id_administrador = :id_administrador"
                        );
                
                $sqlStatement->bindParam(':nombre_a', $nombre_a);
                $sqlStatement->bindParam(':apellido_pa', $apellido_pa);
                $sqlStatement->bindParam(':apellido_ma', $apellido_ma);
                $sqlStatement->bindParam(':correo', $correo);
                $sqlStatement->bindParam(':contrasena', $contrasena);
                $sqlStatement->bindParam(':id_administrador', $id_administrador);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_administrador = 0;
            $con->rollBack();
            //Lineas debug
            echo $e;
            die();
        }
        return $id_administrador;
    }
    
    public function delete($id_administrador){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "DELETE FROM metropolitano01.administrador"
                        . " WHERE id_administrador = :id_administrador"
                        );
                $sqlStatement->bindParam(':id_administrador', $id_administrador);
                $sqlStatement->execute();
                
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_administrador = 0;
            $con->rollBack();
            //DEBUG
            echo $e;
            die();
        }
        return $id_administrador;
    }
}

