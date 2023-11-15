<?php

class Producto
{

    public $id;
    public $nombre;
    public $precio;
    public $sector;
    public $tiempoDePreparacion;


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
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio, sector, tiempoDePreparacion) VALUES (:nombre, :precio, :sector, :tiempoDePreparacion)");
        //  $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoDePreparacion', $this->tiempoDePreparacion, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function TraerProductos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }



    public static function TraerProducto_Id($id)
    {
        $producto = null;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("select * from productos where id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $productoBuscado = $consulta->fetchObject();
        if ($productoBuscado != null) {
            $producto = new Producto($productoBuscado->nombre, $productoBuscado->sector, $productoBuscado->precio,$productoBuscado->tiempoDePreparacion ,  $productoBuscado->id);
        }

        return $producto;
    }
}

?>