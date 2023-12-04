<?php

require_once './models/Producto.php';

class ProductoController
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $sector = $params['sector'];
        $nombre = $params['nombre'];
        $precio = $params['precio'];
        $codigoPedido = $params['codigoPedido'];

        if(Pedido::ObtenerIdPedidoPorCodigoDePedido($codigoPedido) != -1)
        {
            $nuevoProducto = new Producto();
            $nuevoProducto->sector = $sector;
            $nuevoProducto->nombre = $nombre;
            $nuevoProducto->precio = $precio;
            $nuevoProducto->codigoDePedido = $codigoPedido;
            $nuevoProducto->estadoDelProducto = "Pendiente";
            $nuevoProducto->tiempoEstimado = -1;
            $nuevoProducto->fechaAlta = date('Y-m-d');
            $nuevoProducto->fechaModificacion = date('Y-m-d');
            $nuevoProducto->activo = "SI";
            $nuevoProducto->CrearProducto();
    
            Pedido::SumarPrecio($codigoPedido, $precio);
    
            $payload = json_encode(array("mensaje" => "El producto se ha creado exitosamente"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "El codigo de Pedido no existe"));
        }


        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::ObtenerTodos();
        $payload = json_encode(array("listaDeProductos" => $lista));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerProductosSegunEstado($request, $response, $args)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $params = $request->getQueryParams();

        $estadoDelProducto = $params['estadoDelProducto'];

        $lista = Producto::ObtenerProductosSegunEstado($estadoDelProducto, $data->rol);     

        $payload = json_encode(array("listaDeProductos" => $lista));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function EmpleadoTomaProducto($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $idProducto = $params['idProducto'];
        $estadoDelProducto = $params['nuevoEstado'];
        $tiempoEstimado = $params['tiempoEstimado'];

        if(Producto::ModificarEstadoYTiempoProducto($idProducto, $estadoDelProducto, $tiempoEstimado) > 0)
        {
            $codigoDePedido = Pedido::ObtenerCodigoDePedidoPorIdProducto($idProducto);
            Pedido::ActualizarEstadoYTiempoDelPedido($codigoDePedido);

            $payload = json_encode(array("mensaje" => "Se han modificado el Estado y el Tiempo Estimado del Producto"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se modifico nada"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function EmpleadoAlistaProducto($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $idProducto = $params['idProducto'];
        $estadoDelProducto = $params['nuevoEstado'];

        if(Producto::ModificarEstadoYTiempoProducto($idProducto, $estadoDelProducto, 0) > 0)
        {
            $codigoDePedido = Pedido::ObtenerCodigoDePedidoPorIdProducto($idProducto);
            Pedido::ActualizarEstadoYTiempoDelPedido($codigoDePedido);

            $payload = json_encode(array("mensaje" => "Se ha modificado el Estado del Producto"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se modifico nada"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $sector = $params['sector'];
        $nombre = $params['nombre'];
        $precio = $params['precio'];
        $codigoPedido = $params['codigoPedido'];
        $fechaAlta = $params['fechaAlta'];
        $idProducto = $params['idProducto'];
        $precioAnterior = Producto::ObtenerPrecioPorId($idProducto);

        Pedido::ActualizarPrecio($codigoPedido, $precioAnterior, $precio);

        if(Producto::ModificarProducto($nombre, $precio, $sector, $codigoPedido, $fechaAlta, $idProducto) > 0)
        {
            $payload = json_encode(array("mensaje" => "El producto {$idProducto} se actualizo correctamente"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "El producto no se actualizo"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $idProducto = $args['idProducto'];

        if(Producto::BorrarProducto($idProducto) > 0)
        {
            $payload = json_encode(array("mensaje" => "El producto {$idProducto} se dio de baja"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se realizo la baja"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>