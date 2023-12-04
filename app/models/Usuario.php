<?php

require_once("./db/AccesoDatos.php");

class Usuario
{
    public $idUsuario;
    public $nombre;
    public $clave;
    public $mail;
    public $rol;
    public $fechaAlta;
    public $fechaModificacion;
    public $estadoDeCuenta;

    public function __construct()
    {
        
    }

    public function CrearUsuario()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO usuario (nombre, clave, mail, rol, fechaAlta, fechaModificacion, estadoDeCuenta) 
        VALUES (:nombre, :clave, :mail, :rol, :fechaAlta, :fechaModificacion, :estadoDeCuenta)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', $this->fechaModificacion, PDO::PARAM_STR);
        $consulta->bindValue(':estadoDeCuenta', $this->estadoDeCuenta, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM usuario");
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function BorrarUsuariosBD()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("DELETE FROM usuario");
        $consulta->execute();
    }

    public static function ObtenerIdPorMail($mail)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT id FROM usuario where mail = :mail");

        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);

        $consulta->execute();

        $usuario = $consulta->fetchObject();
        return $usuario;
    }

    public static function ModificarUsuario($nombre, $clave, $mail, $rol, $fechaAlta, $idUsuario)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE usuario set nombre = :nombre, clave = :clave, mail = :mail, rol = :rol, fechaAlta = :fechaAlta,
        fechaModificacion = :fechaModificacion WHERE idUsuario = :idUsuario");


        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $rol, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function BorrarUsuario($idUsuario)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE usuario set estadoDeCuenta = :estadoDeCuenta, fechaModificacion = :fechaModificacion WHERE idUsuario = :idUsuario");

        $consulta->bindValue(':estadoDeCuenta', 'Inactivo', PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function VerificarSiExisteUsuario($mail, $clave)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM usuario where mail = :mail AND clave = :clave AND estadoDeCuenta = :estadoDeCuenta");

        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->bindValue(':estadoDeCuenta', 'Activo', PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchObject();
    }


}

?>