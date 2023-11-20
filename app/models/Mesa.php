<?php

class Mesa
{
    public $id;
    public $estado;
    public $activo;


    public function CrearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado,activo) VALUES (:estado, :activo)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':activo', 1, PDO::PARAM_INT);
        $consulta->execute();
    }


    public static function TraerMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE activo = 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }


    public static function TraerMesaPorID($id)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from mesas where id = ? AND activo = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $mesa = $consulta->fetchObject();
        return $mesa;
    }


    public static function CambiarEstadoMesa($id, $estado)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("UPDATE mesas SET estado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }


    public function CambiarEstadoMesaPorPedido($id_pedido){
        $pedido = Pedido::TraerPedidoPorID($id_pedido);
        $mesa = Mesa::TraerMesaPorID($pedido->idMesa);
        switch ($pedido->estado) {
            case "En espera":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "En preparacion":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Finalizado":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Entregado":
                $estadoMesa = "con cliente comiendo";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
        }
    }


    public static function ActualizarEstadoMesa($id, $estado){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE mesas SET estado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function MapearParaMostrar($array)
    {
        if (count($array) > 0) {
            foreach ($array as $i) {
                switch ($i->estado) {
                    case 1:
                        $i->estado = "Con cliente esperando pedido";
                        break;
                    case 2:
                        $i->estado = "Con cliente comiendo";
                        break;
                    case 3:
                        $i->estado = "Con cliente pagando";
                        break;
                    case 4:
                        $i->estado = "Cerrada";
                        break;
                }
            }
        }
        return $array;
    }



    public static function EliminarMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas SET activo = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarMesa($id, $estado)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("UPDATE mesas SET estado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $id, PDO::PARAM_INT);

        return $consulta->execute();
    }
}
?>