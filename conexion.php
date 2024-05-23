<?php

class Conexion
{
    private $host; //localhost o IP
    private $db; //nombre de la db
    private $usuario; // usuarios de la BD
    private $pass; //contraseña del usuario
    private $charset; //utf8

    public function __construct()
    {
        $this->host = '127.0.0.1';
        $this->db = 'proyecto_citas_noe';
        $this->usuario = 'root';
        $this->pass = 'root';
        $this->charset = 'utf8';
    }

    #Método conectar
    public function conectar()
    {
        try {
            #conectar a la bd -> pdo
            $com = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $enlace = new PDO($com, $this->usuario, $this->pass);
            #print_r($enlace);
            echo "Conexión exitosa";
            return $enlace;
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
        }
    }
}

$conexion = new Conexion();
$conexion->conectar();
