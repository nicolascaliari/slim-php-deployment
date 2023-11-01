<?php
require_once(__DIR__ . '/../db/AccesoDatos.php');


class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $tipo;


    public function __construct($nombre, $apellido, $tipo)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->tipo = $tipo;
    }


    public function CrearUsuario()
    {
        $accesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $accesoDato->prepararConsulta("INSERT INTO usuario (nombre, apellido, tipo) VALUES (:nombre, :apellido, :tipo)");
    
        // Asigna los valores a los marcadores de posición en la consulta
        $consulta->bindParam(':nombre', $this->nombre);
        $consulta->bindParam(':apellido', $this->apellido);
        $consulta->bindParam(':tipo', $this->tipo);
    
        // Ejecuta la consulta
        $consulta->execute();
    
        return $accesoDato->obtenerUltimoId();
    }


    // public function TraerUsuarios()
    // {
    //     $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
    //     $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM usuario");
    //     $consulta->execute();
    //     $usuarios = array();
    //     $arrayObtenido = $consulta->fetchAll(PDO::FETCH_OBJ);
    //     foreach ($arrayObtenido as $i) {
    //         $usuario = array($i->id, $i->nombre, $i->apellido, $i->tipo);
    //         $usuarios[] = $usuario;
    //     }
    //     return $usuarios;
    // }
}

?>