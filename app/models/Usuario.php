<?php
require_once(__DIR__ . '/../db/AccesoDatos.php');


class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $tipo;


    // public function __construct($nombre, $apellido, $tipo)
    // {
    //     $this->nombre = $nombre;
    //     $this->apellido = $apellido;
    //     $this->tipo = $tipo;
    // }


    public function CrearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (nombre, apellido, tipo) VALUES (:nombre, :apellido, :tipo)");
        //  $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function TraerUsuarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
}

?>