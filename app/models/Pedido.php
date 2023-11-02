<?php

class Pedido
{
    public $idPedido;
    public $idMozo;
    public $idMesa;
    public $descripcionPedido;


    public function CrearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMozo, idMesa, descripcionPedido) VALUES (:idMozo, :idMesa, :descripcionPedido)");

        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':descripcionPedido', $this->descripcionPedido, PDO::PARAM_STR);

        $consulta->execute();
    }



}


?>