<?php

class Pedido
{
    public $id;
    public $idMozo;
    public $idMesa;
    public $descripcionPedido;
    public $estado;
    public $tiempoEstimado;
    public $imagen;
    public $activo;


    public function CrearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMozo, idMesa, descripcionPedido, estado, tiempoEstimado,imagen ,activo) VALUES (:idMozo, :idMesa, :descripcionPedido, :estado, :tiempoEstimado,:imagen ,:activo)");

        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':descripcionPedido', $this->descripcionPedido, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->bindValue(':activo', 1, PDO::PARAM_INT);
        $consulta->execute();
    }



    public static function TraerPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE activo = 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public function MesaDisponible($idMesa)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT id FROM mesas WHERE id = ? AND estado = 'en espera'");
        $consulta->bindValue(1, $idMesa, PDO::PARAM_INT);
        $consulta->execute();

        // Verificar si se encontró alguna fila
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        // Devolver true si se encontró una mesa, false de lo contrario
        return ($resultado !== false);
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

    public static function TraerPedidoId($id)
    {
        $objAcceso = AccesoDatos::obtenerInstancia();
        $consulta = $objAcceso->prepararConsulta("SELECT * FROM pedidos WHERE id = ? AND activo = 1");

        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();

        $pedido = $consulta->fetchObject('Pedido');
        return $pedido;
    }


    public function ActualizarEstadoPedido($estado)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("UPDATE pedidos SET estado = ?, tiempoEstimado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $this->tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(3, $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }


    public static function TraerPedidoPorID($id)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from pedidos where id = ? and activo = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $pedido = $consulta->fetchObject('Pedido');
        return $pedido;
    }


    public static function EliminarPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET activo = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarPedido($id, $idMozo, $idMesa, $estado ,$tiempoEstimado, $descripcionPedido)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = ?, idMozo = ?, estado = ?, tiempoEstimado = ?, descripcionPedido = ?  WHERE id = ? AND activo = 1");

        $consulta->bindValue(1, $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(2, $idMozo, PDO::PARAM_INT);
        $consulta->bindValue(3, $descripcionPedido, PDO::PARAM_STR);
        $consulta->bindValue(4, $estado, PDO::PARAM_STR);
        $consulta->bindValue(5, $tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(6, $id, PDO::PARAM_INT);

        return $consulta->execute();
    }



    public function DefinirDestinoImagen($ruta)
    {
        $destino = str_replace('\\', '/', $ruta) . $this->idMesa .".png";
        return $destino;
    }

}


?>