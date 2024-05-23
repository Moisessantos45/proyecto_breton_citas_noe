<?php

require_once "conexion.php";

class CAD{
    public $con;

    static public function agregaUsuario($nombre, $correo, $rol, $username, $passw)
    {
        $con = new Conexion(); //establece la conexión a la bd
        $query = $con->conectar()->prepare("INSERT INTO usuario (nombre,correo,rol,username,passw) VALUES ('$nombre','$correo', '$rol','$username','$passw')");

        if ($query->execute()) {
            echo "el usuario $nombre se ha agregado correctamente";
            return 1;
        } else {
            echo "Hubo un error";
            print_r($con->conectar()->errorInfo());
            return 0;
        }
    }

    static public function verificaUsuario($username, $passw){
        $con = new Conexion();
        $query = $con->conectar()->prepare("SELECT * FROM usuario WHERE username = '$username' and passw = '$passw' ");
        if($query->execute())
        {
            //un solo registro 
            $row = $query->fetch(PDO::FETCH_NUM);
            if($row)
            {
                //echo $row[0]."-".$row[1]."-".$row[2]."-".$row[3];
                $_SESSION['idUsuario'] = $row[0];
                return true;
            }
            else
            {
                echo "El usuario no existe";
            }

            //mas de un registro
           /* while ($row = $query->fetch(PDO::FETCH_ASSOC)){
                $datos[]=$row;
            }
            print_r($datos);*/

        }
        else
        {
            return false;
        }
    }

    static public function modificaUsuario($datosModificar,$idUsuario)
    {
        $con = new Conexion(); //establece la conexión a la bd
        $query = $con->conectar()->prepare("UPDATE usuario SET $datosModificar WHERE idUsuario = $idUsuario");

        if ($query->execute()) {
            return 1;
        } else {
            echo "Hubo un error";
            print_r($con->conectar()->errorInfo());
            return 0;
        }
    }

    static public function traeUsuarios()
    {
        $con = new Conexion();
        $query = $con->conectar()->prepare("SELECT * FROM usuario ORDER BY idUsuario DESC");
        if ($query->execute()) {
            $datos = [];
            while ($row = $query->fetch(PDO::FETCH_ASSOC)){
                $datos[]=$row;
            }
            return $datos;
        } else {
            return false;
        }
    }

    static public function eliminaUsuario($idUsuario)
    {
        $con = new Conexion(); //establece la conexión a la bd
        $query = $con->conectar()->prepare("DELETE FROM usuario WHERE idUsuario = $idUsuario");

        if ($query->execute()) {
            return 1;
        } else {
            echo "Hubo un error";
            print_r($con->conectar()->errorInfo());
            return 0;
        }
    }

    static public function eliminaCita($idCita)
    {
        $con = new Conexion(); //establece la conexión a la bd
        $query = $con->conectar()->prepare("DELETE FROM citas WHERE idCita = $idCita");

        if ($query->execute()) {
            return 1;
        } else {
            echo "Hubo un error";
            print_r($con->conectar()->errorInfo());
            return 0;
        }
    }

    static public function traeCitas()
    {
        $con = new Conexion();
        $query = $con->conectar()->prepare("SELECT * FROM citas ORDER BY idCita");
        if ($query->execute()) {
            $datos = [];
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $datos[] = $row;
            }
            return $datos;
        } else {
            return false;
        }
    }

    static public function modificaCita($datosModificar, $idCita)
    {
        $con = new Conexion(); //establece la conexión a la bd
        $query = $con->conectar()->prepare("UPDATE citas SET $datosModificar WHERE idCita = $idCita");

        if ($query->execute()) {
            return 1;
        } else {
            echo "Hubo un error";
            print_r($con->conectar()->errorInfo());
            return 0;
        }
    }
}

?>