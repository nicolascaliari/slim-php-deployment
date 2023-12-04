<?php
Class Pedido
{
    public $idPedido;
    public $codigoPedido;
    public $idMesa;
    public $fecha;
    public $fotoMesa;
    public $precioTotal;
    public $tiempoEstimado;
    public $idEmpleado;
    public $nombreCliente;
    public $estadoDelPedido;
    public $fechaModificacion;
    public $puntuacion;
    public $comentario;
    public $activo;

    public function __construct()
    {

    }

    public function CrearPedido()
    {
        try{
            var_dump("hola");

            var_dump($this->codigoPedido);
            var_dump($this->idMesa);
            var_dump($this->fecha);
            var_dump($this->fotoMesa);
            var_dump($this->precioTotal);
            var_dump($this->tiempoEstimado);
            var_dump($this->idEmpleado);
            var_dump($this->nombreCliente);
            var_dump($this->estadoDelPedido);
            var_dump($this->fechaModificacion);
            var_dump($this->activo);





            $objAcessoDatos = AccesoDatos::ObtenerInstancia();
            $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO pedidos (codigoPedido, idMesa, fecha, fotoMesa, precioTotal, 
            tiempoEstimado, idEmpleado, nombreCliente, estadoDelPedido, fechaModificacion, activo) 
            VALUES (:codigoPedido, :idMesa, :fecha, :fotoMesa, :precioTotal, :tiempoEstimado, :idEmpleado, :nombreCliente, :estadoDelPedido, :fechaModificacion, :activo)");
    
            $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
            $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
            $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
            $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);
            $consulta->bindValue(':precioTotal', $this->precioTotal, PDO::PARAM_STR);
            $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_STR);
            $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
            $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
            $consulta->bindValue(':estadoDelPedido', $this->estadoDelPedido, PDO::PARAM_STR);
            $consulta->bindValue(':fechaModificacion', $this->fechaModificacion, PDO::PARAM_STR);
            $consulta->bindValue(':activo', $this->activo, PDO::PARAM_STR);
        
            $consulta->execute();
        }catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }


    }

    public static function ModificarPedido($idMesa, $fecha, $estadoDelPedido, $nombreCliente, $precioTotal, $idEmpleado, $codigoDePedido)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos set idMesa = :idMesa, fecha = :fecha, estadoDelPedido = :estadoDelPedido,
        nombreCliente = :nombreCliente, precioTotal = :precioTotal, idEmpleado = :idEmpleado, fechaModificacion = :fechaModificacion WHERE codigoPedido = :codigoDePedido");

        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->bindValue(':precioTotal', $precioTotal, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $nombreCliente, PDO::PARAM_INT);
        $consulta->bindValue(':estadoDelPedido', $estadoDelPedido, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);
        $consulta->bindValue(':codigoDePedido', $codigoDePedido, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function BorrarPedido($idPedido)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos set activo = :activo, fechaModificacion = :fechaModificacion WHERE idPedido = :idPedido");

        $consulta->bindValue(':activo', "NO", PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function generarNumeroDePedido() 
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 6;
        $codigo = '';
        
        for ($i = 0; $i < $longitud; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        return $codigo;
    }

    public static function ObtenerIdPedidoPorCodigoDePedido($codigoDePedido)
    {
        $lista = Pedido::obtenerTodos();
        $retorno = -1;

        foreach ($lista as $value) 
        {
            if($value->codigoPedido == $codigoDePedido)
            {
                $retorno = $value->idPedido;
                break;
            }

        }

        return $retorno;
    }

    public static function ActualizarPrecio($codigoPedido, $precioAnterior, $nuevoPrecio)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos SET precioTotal = precioTotal - :precioAnterior + :nuevoPrecio, fechaModificacion = :fechaModificacion
         WHERE codigoPedido = :codigoPedido");

        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':precioAnterior', $precioAnterior, PDO::PARAM_INT);
        $consulta->bindValue(':nuevoPrecio', $nuevoPrecio, PDO::PARAM_INT);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount();
    }


    public static function SumarPrecio($codigoDePedido, $precio)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos SET precioTotal = precioTotal + :precio WHERE codigoPedido = :codigoDePedido");
        
        $consulta->bindValue(':codigoDePedido', $codigoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount();

    }

    public static function CambiarEstadoDelPedido($codigoDePedido, $nuevoEstado)
    {
        $objAcessoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos SET estadoDelPedido = :estado, fechaModificacion = :fechaModificacion
        WHERE codigoPedido = :codigoPedido");

        $consulta->bindValue(':codigoPedido', $codigoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $nuevoEstado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

    }

    public static function ActualizarEstadoYTiempoDelPedido($codigoDePedido)
    {
        $objAcessoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos SET estadoDelPedido = :estado, tiempoEstimado = :tiempoEstimado, fechaModificacion = :fechaModificacion
        WHERE codigoPedido = :codigoPedido");

        $tiempoEstimado = Pedido::ObtenerTiempoEstimado($codigoDePedido);
        $estadoDelPedido = Pedido::ObtenerEstadoParaPedido($codigoDePedido);

        $consulta->bindValue(':codigoPedido', $codigoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estadoDelPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();
    }

    public static function ObtenerCodigoDePedidoPorIdProducto($idProducto)
    {
        $lista = Producto::ObtenerTodos();
        $retorno = -1;

        foreach ($lista as $value) 
        {
            if($value->idProducto == $idProducto)
            {
                $retorno = $value->codigoDePedido;
                break;
            }

        }

        return $retorno;
    }

    public static function ObtenerPedido($codigoDePedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoDePedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function ObtenerTiempoEstimado($codigoDePedido)
    {
        $lista = Producto::ObtenerProductosSegunCodigoDePedido($codigoDePedido);
        $tiemposEstimados = array();
        $tiempoMasAlto = 0;

        foreach($lista as $value)
        {
            $tiemposEstimados[] = $value->tiempoEstimado;
        }

        $tiempoMasAlto = max($tiemposEstimados);

        return $tiempoMasAlto;
    }
    public static function ObtenerEstadoParaPedido($codigoDePedido)
    {
        $lista = Producto::ObtenerProductosSegunCodigoDePedido($codigoDePedido);
        $estados = array();
        $estadoRetorno = null;

        foreach($lista as $value)
        {
            if($value->estadoDelProducto == "Pendiente")
            {
                $estadoRetorno = "Pendiente";
                break;
            }

            if($value->estadoDelProducto == "En Preparacion" || $value->estadoDelProducto == "Listo Para Servir")
            {
                $estados[] = $value->estadoDelProducto;
                
            }
            
        }

        if($estadoRetorno == null)
        {
            if(in_array("En Preparacion", $estados))
            {
                $estadoRetorno = "En Preparacion";
            }
            else if(in_array("Listo Para Servir", $estados))
            {
                $estadoRetorno = "Listo Para Servir";
            }
        }

        return $estadoRetorno;
    }

    public static function ObtenerPedidosSegunEstado($estadoDelPedido)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM pedidos WHERE estadoDelPedido = :estadoDelPedido");

        $consulta->bindValue(':estadoDelPedido', $estadoDelPedido, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function ModificarPuntuacionYComentario($codigoDePedido, $puntuacion, $comentario)
    {
        $objAcessoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos SET puntuacion = :puntuacion, comentario = :comentario, fechaModificacion = :fechaModificacion
        WHERE codigoPedido = :codigoPedido");

        $tiempoEstimado = Pedido::ObtenerTiempoEstimado($codigoDePedido);
        $estadoDelPedido = Pedido::ObtenerEstadoParaPedido($codigoDePedido);

        $consulta->bindValue(':codigoPedido', $codigoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':puntuacion', $puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $comentario, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount();
    }


    public static function DefinirDestinoImagen($ruta, $codigoDeMesa)
    {
        $destino = str_replace('\\', '/', $ruta) . $codigoDeMesa .".png";
        return $destino;
    }

    
}

?>