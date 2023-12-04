<?php

Class Mesa
{
    public $id;
    public $codigoMesa;
    public $estadoMesa;
    public $fechaAlta;
    public $fechaModificacion;
    public $activo;

    public function __construct(){}

    public function CrearMesa()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO mesas (codigoMesa, estadoMesa, fechaAlta, fechaModificacion, activo) 
        VALUES (:codigoMesa, :estadoMesa, :fechaAlta, :fechaModificacion, :activo)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', $this->estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', $this->fechaModificacion, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $this->activo, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function ObtenerIdMesaPorCodigo($codigoDeMesa)
    {
        // $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        // $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from pedidos where id = ? and activo = 1");
        // $consulta->bindValue(1, $id, PDO::PARAM_INT);
        // $consulta->execute();
        // $pedido = $consulta->fetchObject('Pedido');
        // return $pedido;

        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT id FROM mesas WHERE codigoMesa = :codigoMesa");
        $consulta->bindValue(':codigoMesa', $codigoDeMesa, PDO::PARAM_INT);
        $consulta->execute();

        // Usar fetchObject para obtener el resultado como un objeto Mesa
        $mesa = $consulta->fetchObject('Mesa');

        return $mesa;
    }
    

    public static function CambiarEstadoDeMesa($id, $estadoMesa)
    {
        //var_dump($id);
        var_dump("estoy en cambiar estado el id es : " . $id);
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa  WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estadoMesa', $estadoMesa, PDO::PARAM_STR);

        $consulta->execute();

    }

    public static function ModificarMesa($idMesa, $codigoDeMesa, $estadoMesa)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE mesas SET codigoMesa = :codigoMesa, estadoMesa = :estadoMesa, fechaModificacion = :fechaModificacion WHERE idMesa = :idMesa");

        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $codigoDeMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', $estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function BorrarMesa($idMesa)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE usuarios SET activo = :activo, fechaModificacion = :fechaModificacion WHERE idMesa = :idMesa");

        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':activo', "NO", PDO::PARAM_INT);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function CerrarMesa($codigoDeMesa)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa, fechaModificacion = :fechaModificacion WHERE codigoMesa = :codigoDeMesa");

        $consulta->bindValue(':codigoDeMesa', $codigoDeMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', "Cerrado", PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function EstaLibre($codigoMesa)
    {
        var_dump("esta libre codigo de mesa: " .$codigoMesa);

        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * from mesas WHERE codigoMesa = :codigoMesa");

        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_INT);

        $consulta->execute();

        $mesa = $consulta->fetchObject();

        var_dump("esta libre estado mesa : " . $mesa->estadoMesa);
        
        if($mesa->estadoMesa == "Cerrada")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function ObtenerMesaMasUsada($pedidos)
    {
        $contadorIdMesa = array();

        foreach($pedidos as $pedido)
        {
            $idMesa = $pedido->idMesa;

            if(isset($contadorIdMesa[$idMesa]))
            {
                $contadorIdMesa[$idMesa]++;
            }
            else
            {
                $contadorIdMesa[$idMesa] = 1;
            }
        }

        $mesaMasUsada = array_search(max($contadorIdMesa), $contadorIdMesa);

        return $mesaMasUsada;
    }

    public static function ObtenerMejoresComentarios($pedidos)
    {
        $mejoresComentarios = array();

        usort($pedidos, function($a, $b)
        {
            $comparacion = $b->puntuacion - $a->puntuacion;
            return $comparacion;
        });

        $mejoresPedidos = array_slice($pedidos, 0, 3);

        foreach($mejoresPedidos as $pedido)
        {
            $mejoresComentarios[] = $pedido->comentario;
        }

        return $mejoresComentarios;
    }
    
}

?>