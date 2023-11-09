<?php

class Pedido
{
    public $id;
    public $idMozo;
    public $idMesa;
    public $descripcionPedido;
    public $estado;


    public function CrearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMozo, idMesa, descripcionPedido, estado) VALUES (:idMozo, :idMesa, :descripcionPedido, :estado)");

        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':descripcionPedido', $this->descripcionPedido, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
    }



    public static function TraerPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }


    public function MesaDisponible($idMesa)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT id from mesas WHERE id = ? AND estado = 1");
        $consulta->bindValue(1, $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        $estado = $consulta->fetchColumn();

        if ($estado == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function MozoExistente($idMozo)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT id FROM usuario WHERE id = ? AND tipo = 'mecero'");
        $consulta->bindValue(1, $idMozo, PDO::PARAM_INT);
        $consulta->execute();
        $id = $consulta->fetchColumn();

        if ($id == $idMozo) {
            return true;
        } else {
            return false;
        }
    }


    public function CambiarEstadoPedido($estado)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("update pedidos set estado = ? where id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_INT);
        $consulta->bindValue(2, $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }
}


?>