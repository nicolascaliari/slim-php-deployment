<?php

class Producto
{

    public $id;
    public $nombre;
    public $precio;
    public $sector;
    public $tiempoDePreparacion;
    public $estado;


    public function __construct()
    {
        $params = func_get_args();
        $num_params = func_num_args();
        $funcion_constructor = '__construct' . $num_params;
        if (method_exists($this, $funcion_constructor)) {
            call_user_func_array(array($this, $funcion_constructor), $params);
        }
    }

    public function __construct5($nombre, $sector, $precio, $tiempoDePreparacion, $id = null)
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->sector = $sector;
        $this->tiempoDePreparacion = $tiempoDePreparacion;
        if ($id != null) {
            $this->id = $id;
        }
    }


    public function __construct0()
    {
    }


    public function CraerProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio, sector, tiempoDePreparacion,estado) VALUES (:nombre, :precio, :sector, :tiempoDePreparacion,:estado)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoDePreparacion', $this->tiempoDePreparacion, PDO::PARAM_STR);
        $consulta->bindValue(':estado', 1, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function TraerProductos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE estado = 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }



    public static function TraerProducto_Id($id)
    {
        $producto = null;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = ? AND estado = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $productoBuscado = $consulta->fetchObject();
        if ($productoBuscado != null) {
            $producto = new Producto($productoBuscado->nombre, $productoBuscado->sector, $productoBuscado->precio, $productoBuscado->tiempoDePreparacion, $productoBuscado->id);
        }

        return $producto;
    }

    public static function EliminarProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos SET estado = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarProducto($id, $nombre, $sector, $precio, $tiempoDePreparacion)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("UPDATE productos SET nombre = ?, sector = ?, precio = ?, tiempoDePreparacion = ? WHERE id = ?");
        $consulta->bindValue(1, $nombre, PDO::PARAM_STR);
        $consulta->bindValue(2, $sector, PDO::PARAM_STR);
        $consulta->bindValue(3, $precio, PDO::PARAM_INT);
        $consulta->bindValue(4, $tiempoDePreparacion, PDO::PARAM_INT);
        $consulta->bindValue(5, $id, PDO::PARAM_INT);

        return $consulta->execute();
    }
}

?>