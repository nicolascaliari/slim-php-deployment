<?php

Class Producto
{
    public $idProducto;
    public $nombre;
    public $precio;
    public $sector;
    public $codigoDePedido;
    public $estadoDelProducto;
    public $tiempoEstimado;
    public $fechaAlta;
    public $fechaModificacion;
    public $activo;
    

    public function __construct()
    {
        
    }

    public function CrearProducto()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("INSERT INTO productos (nombre, precio, sector, codigoDePedido,
         estadoDelProducto, tiempoEstimado, fechaAlta, fechaModificacion, activo) 
        VALUES (:nombre, :precio, :sector, :codigoDePedido, :estadoDelProducto, :tiempoEstimado, :fechaAlta, :fechaModificacion, :activo)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);  //derecha le guarda a izquierda
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':codigoDePedido', $this->codigoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':estadoDelProducto', $this->estadoDelProducto, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', $this->fechaModificacion, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $this->activo, PDO::PARAM_STR);

        $consulta->execute();
    }

    public static function ModificarProducto($nombre, $precio, $sector, $codigoDePedido, $fechaAlta, $idProducto)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE productos set nombre = :nombre, precio = :precio, sector = :sector,
        codigoDePedido = :codigoDePedido, fechaAlta = :fechaAlta, fechaModificacion = :fechaModificacion WHERE idProducto = :idProducto");

        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);  //derecha le guarda a izquierda
        $consulta->bindValue(':precio', $precio, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':codigoDePedido', $codigoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function BorrarProducto($idProducto)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedidos set activo = :activo, fechaModificacion = :fechaModificacion WHERE idProducto = :idProducto");

        $consulta->bindValue(':activo', "NO", PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas
    }

    public static function ObtenerTodos()
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function ObtenerPrecioPorId($id)
    {
        $lista = Producto::ObtenerTodos();
        $precio = -1;


        foreach ($lista as $producto) 
        {
            if($producto->idProducto == $id)
            {
                $precio = $producto->precio;
                break;
            }

        }

        return $precio;
    }

    public static function ModificarEstadoYTiempoProducto($idProducto, $nuevoEstado, $tiempoEstimado)
    {
        $objAcessoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE productos SET estadoDelProducto = :estadoDelProducto, tiempoEstimado = :tiempoEstimado
        WHERE idProducto = :idProducto");

        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':estadoDelProducto', $nuevoEstado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount(); //retorna la cantidad de filas afectadas

    }

    public static function ObtenerProductosSegunEstado($estadoDelProducto, $sector)
    {

        var_dump($estadoDelProducto);
        var_dump($sector);

        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM productos WHERE estadoDelProducto = :estadoDelProducto");

        $consulta->bindValue(':estadoDelProducto', $estadoDelProducto, PDO::PARAM_STR);
        //$consulta->bindValue(':sector', $sector, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function ObtenerProductosSegunCodigoDePedido($codigoDePedido)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM productos WHERE codigoDePedido = :codigoDePedido");

        $consulta->bindValue(':codigoDePedido', $codigoDePedido, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function ObtenerProductoSegunId($idProducto)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();
        $consulta = $objAcessoDatos->PrepararConsulta("SELECT * FROM productos WHERE idProducto = :idProducto");

        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);

        $consulta->execute();

        $producto = $consulta->fetchObject('Producto');
        
        return $producto;
    }
}

?>