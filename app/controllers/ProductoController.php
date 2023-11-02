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


        // Creamos el usuario
        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->sector = $sector;

        $producto->CraerProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public static function TraerProductosController($request, $response, $args) {
        $productos  = Producto::TraerProductos();
    
        $payload = json_encode(array("listaProductos" => $productos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}

?>