<?php

require_once './models/Producto.php';
class ProductoController extends Producto
{
    public static function InsertarProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $sector = $parametros['sector'];
        $tiempoDePreparacion = $parametros['tiempoDePreparacion'];


        // Creamos el usuario
        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->sector = $sector;
        $producto->tiempoDePreparacion = $tiempoDePreparacion;

        $producto->CraerProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public static function TraerProductosController($request, $response, $args)
    {
        $productos = Producto::TraerProductos();

        $payload = json_encode(array("listaProductos" => $productos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function BajaProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $producto = Producto::TraerProducto_Id($id);
        if ($producto) {
            if (Producto::EliminarProducto($id)) {
                $payload = json_encode(array("mensaje" => "Producto eliminado con exito"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Error en eliminar Producto"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarProductoController($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $producto = Producto::TraerProducto_Id($id);
        if ($producto) {
            $producto->nombre = $parametros['nombre']; 
            $producto->sector = $parametros['sector'];
            $producto->precio = $parametros['precio'];
            $producto->tiempoDePreparacion = $parametros['tiempoDePreparacion'];
            //var_dump($producto->tiempoDePreparacion);

            Producto::ModificarProducto($id, $producto->nombre, $producto->sector, $producto->precio, $producto->tiempoDePreparacion);
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error en modificar Producto"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>