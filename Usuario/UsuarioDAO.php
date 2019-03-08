<?php

require '../Conexion.php';

class UsuarioDAO{
    private $objConexion;
    
    public function __construct() {
        $this->objConexion = new Conexion();
    }
    
    public function create($nombre, $apellido_p, $apellido_m, $correo, $fecha_na, $contrasena){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        $last_insert_id = 0;
        
        try {
            if($con){
                $con->beginTransaction();
                //INSERT INTO usuario VALUES(DEFAULT, 'Jesus', 'Morales', 'Fernpandez', 'chuy99_mf@hotmail.com', '20-07-1999', 'jesusMF');
                $sqlStatement = $con->prepare(
                        "INSERT INTO metropolitano01.usuario VALUES(default,"
                                                  . ":nombre,"
                                                  . ":apellido_p,"
                                                  . ":apellido_m,"
                                                  . ":correo,"
                                                  . ":fecha_na,"
                                                  . ":contrasena)");
                $sqlStatement->bindParam(':nombre', $nombre);
                $sqlStatement->bindParam(':apellido_p', $apellido_p);
                $sqlStatement->bindParam(':apellido_m', $apellido_m);
                $sqlStatement->bindParam(':correo', $correo);
                $sqlStatement->bindParam(':fecha_na', $fecha_na);
                $sqlStatement->bindParam(':contrasena', $contrasena);
                $sqlStatement->execute();
                
                $last_insert_id = $con->lastInsertId();
                $con->commit();
            }
        } catch (PDOException $e) {
           // echo $e;
            $con->rollBack();
           // die();
        }
        return $last_insert_id;
    }
    
    public function read($id_usuario){
        $this->objConexion->openConnection();
        $con  = $this->objConexion->getConnection();
        
        $arrayUsuario = array();
        
        try {
            $sql = "SELECT * FROM metropolitano01.usuario";
            if($id_usuario > 0){
                $sql = "SELECT * FROM metropolitano01.usuario WHERE id_usuario={$id_usuario}";
            }

            if($con){
                foreach ($con->query($sql) as $row){
                    $arrayUsuario[] = array(
                                            'id_usuario' => $row['id_usuario'],
                                            'nombre' => $row['nombre'],
                                            'apellido_p' => $row['apellido_p'],
                                            'apellido_m' => $row['apellido_m'],
                                            'correo' => $row['correo'],
                                            'fecha_na' => $row['fecha_na'],
                                            'contrasena' => $row['contrasena']
                    );
                }
            }
        } catch (PDOException $e) {
            
        }
        
        return $arrayUsuario;
    }
    
    public function update($id_usuario, $nombre, $apellido_p, $apellido_m, $correo, $fecha_na, $contrasena){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                            'UPDATE metropolitano01.usuario SET nombre = :nombre,'
                                              . 'apellido_p = :apellido_p,'
                                              . 'apellido_m = :apellido_m,'
                                              . 'correo = :correo,'
                                              . 'fecha_na = :fecha_na,'
                                              . 'contrasena = :contrasena'
                                              . ' WHERE id_usuario = :id_usuario'
                        );
                $sqlStatement->bindParam(':nombre', $nombre);
                $sqlStatement->bindParam(':apellido_p', $apellido_p);
                $sqlStatement->bindParam(':apellido_m', $apellido_m);
                $sqlStatement->bindParam(':correo', $correo);
                $sqlStatement->bindParam(':fecha_na', $fecha_na);
                $sqlStatement->bindParam(':contrasena', $contrasena);
                $sqlStatement->bindParam(':id_usuario', $id_usuario);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_usuario = 0;
            $con->rollBack();
            echo $e;
            die();
            
        }
        return $id_usuario;
    }
    
    public function delete($id_usuario){
        $this->objConexion->openConnection();
        $con = $this->objConexion->getConnection();
        
        try {
            if($con){
                $con->beginTransaction();
                $sqlStatement = $con->prepare(
                        "DELETE FROM metropolitano01.usuario "
                        . "WHERE id_usuario = :id_usuario"
                        );
                $sqlStatement->bindParam(':id_usuario', $id_usuario);
                $sqlStatement->execute();
                $con->commit();
            }
        } catch (PDOException $e) {
            $id_usuario = 0;
            $con->rollBack();
        }
        return $id_usuario;
    }
}