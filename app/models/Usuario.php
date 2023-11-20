<?php
require_once(__DIR__ . '/../db/AccesoDatos.php');


class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $tipo;
    public $user;
    public $password;
    public $estado;

    public function CrearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (nombre, apellido, tipo,user, password ,estado) VALUES (:nombre, :apellido, :tipo, :user, :password, :estado)");
        //  $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':user', $this->user, PDO::PARAM_STR);
        $consulta->bindValue('password', $this->password, PDO::PARAM_STR);
        $consulta->bindValue('estado', 1, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function TraerUsuarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE estado = 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }


    public static function TraerUsuarioPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE id = ? AND estado = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();

        $usuario = $consulta->fetchObject();
        return $usuario;
    }


    public static function EliminarUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE usuario SET estado = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }



    public static function ModificarUsuario($id, $nombre, $apellido, $tipo, $user, $password){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE usuario SET nombre = ?, apellido = ?, tipo = ?, user = ?, password = ?  WHERE id = ?");
        $consulta->bindValue(1, $nombre, PDO::PARAM_STR);
        $consulta->bindValue(2, $apellido, PDO::PARAM_STR);
        $consulta->bindValue(3, $tipo, PDO::PARAM_STR);
        $consulta->bindValue(4, $user, PDO::PARAM_STR);
        $consulta->bindValue(5, $password, PDO::PARAM_STR);
        $consulta->bindValue(6, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }




    public static function TraerUsuarioPorLogin($user, $password)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from usuario where user = ? AND password = ? AND estado = 1");
        $consulta->bindValue(1, $user, PDO::PARAM_STR);
        $consulta->bindValue(2, $password, PDO::PARAM_STR);
        $consulta->execute();
        $usuario = $consulta->fetchObject();
        return $usuario;
    }
}

?>