<?php
class Encuesta{
    public $id;
    public $idMesa;
    public $idPedido;
    public $nombreCliente;
    public $descripcion;
    public $puntuacionMesa;
    public $puntuacionMozo;
    public $puntuacionCocinero;
    public $puntuacionRestaurant;
    public $estado;

    public static function CrearEncuesta($encuesta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("INSERT into encuestas (idMesa,nombreCliente,descripcion,puntuacionMesa,puntuacionMozo,puntuacionCocinero,puntuacionRestaurant,estado)values(:idMesa,:nombreCliente,:descripcion,:puntuacionMesa,:puntuacionMozo,:puntuacionCocinero,:puntuacionRestaurant,:estado)");
        $consulta->bindValue(':idMesa', $encuesta->idMesa);
        $consulta->bindValue(':nombreCliente', $encuesta->nombreCliente);
        $consulta->bindValue(':descripcion', $encuesta->descripcion);
        $consulta->bindValue(':puntuacionMesa', $encuesta->puntuacionMesa);
        $consulta->bindValue(':puntuacionMozo', $encuesta->puntuacionMozo);
        $consulta->bindValue(':puntuacionCocinero', $encuesta->puntuacionCocinero);
        $consulta->bindValue(':puntuacionRestaurant', $encuesta->puntuacionRestaurant);
        $consulta->bindValue(':estado', $encuesta->estado);
        $consulta->execute();
    }

    public static function TraerEncuestas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function TraerPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function TraerMejoresComentarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas ORDER BY puntuacionRestaurant DESC");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
}
?>