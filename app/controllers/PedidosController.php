<?php
require_once './models/Pedido.php';
class PedidosController
{
    public static function InsertarPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idMesa = $parametros['idMesa'];
        $idMozo = $parametros['idMozo'];
        $descripcionPedido = $parametros['descripcionPedido'];
        $arrayProductos = explode(",", $descripcionPedido);
        $productos = array();
        $tiempoMaximo = 0;

        foreach ($arrayProductos as $id) {
            $producto = Producto::TraerProducto_Id($id);
            array_push($productos, $producto);
            $tiempoDePreparacion = $producto->tiempoDePreparacion;

            $tiempoMaximo = max($tiempoMaximo, $tiempoDePreparacion);
        }
        $tiempoEstimado = $tiempoMaximo;

        $pedido = new Pedido();

        var_dump($idMesa);
        var_dump($idMozo);

        $validacionDeMesa = $pedido->MesaDisponible($idMesa);
        $validacionDeMozo = $pedido->MozoExistente($idMozo);

        if ($validacionDeMesa == false || $validacionDeMozo == false) {
            $payload = json_encode(array("mensaje" => "La mesa no esta disponible o el mozo no esta disponible"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($validacionDeMesa == true && $validacionDeMozo == true) {
            $pedido->idMesa = $idMesa;
            $pedido->idMozo = $idMozo;
            $pedido->descripcionPedido = json_encode($productos);
            $pedido->tiempoEstimado = $tiempoEstimado;
            $pedido->estado = "en preparacion";

            if (isset($_FILES['imagen'])) {

                $rutaImagen = __DIR__ . '/../images/';


                var_dump($rutaImagen);

                $imagen = $_FILES['imagen'];
                $destino = $pedido->DefinirDestinoImagen($rutaImagen);
                move_uploaded_file($imagen['tmp_name'], $destino);

                $pedido->imagen = $destino;
            }
            $pedido->CrearPedido();
            $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }




    public function TraerPedidos($request, $response, $args)
    {
        $pedidos = Pedido::TraerPedidos();

        $payload = json_encode(array("listaPedidos" => $pedidos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }



    public static function ModificarEstado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        // me traigo el pedido con el id ingresado por body
        $pedido = new Pedido();
        $id = $parametros['id'];
        $estado = $parametros['estado'];

        $pedidoSolicitado = Pedido::TraerPedidoPorID($id);
        $pedido = new Pedido();
        $pedido->id = $pedidoSolicitado->id;
        $pedido->estado = $pedidoSolicitado->tiempoEstimado;
        $pedido->estado = $pedidoSolicitado->estado;

        if ($pedido != null) {
            switch ($estado) {
                case 'En preparacion':
                    $pedido->ActualizarEstadoPedido($estado);
                    $payload = json_encode(array("mensaje" => "El estado del pedido era " . $pedido->estado . " y se ha actualizado a " . $estado . " exitosamente"));
                    break;
                case 'Finalizado':
                    var_dump($pedido);
                    $pedido->tiempoEstimado = "5";
                    $pedido->ActualizarEstadoPedido($estado);
                    $payload = json_encode(array("mensaje" => "El estado del pedido era " . $pedido->estado . " y se ha actualizado a " . $estado . " exitosamente"));
                    break;
                case 'Entregado':
                    $pedido->tiempoEstimado = "0";
                    $pedido->ActualizarEstadoPedido($estado);
                    $payload = json_encode(array("mensaje" => "El estado del pedido era " . $pedido->estado . " y se ha actualizado a " . $estado . " exitosamente"));
                    break;
                default:
                    $payload = json_encode(array("mensaje" => "Valor de estado no valido"));
            }
            MesaController::CambiarEstadoMesaPorPedido($id);
        } else {
            $payload = json_encode(array("mensaje" => "Numero de pedido no encontrado."));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function BajaPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $pedido = Pedido::TraerPedidoPorID($id);

        if ($pedido) {
            if (Pedido::EliminarPedido($id)) {
                $payload = json_encode(array("mensaje" => "Pedido eliminado con exito"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Error en eliminar Pedido"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $pedido = Pedido::TraerPedidoPorID($id);
        if ($pedido) {
            $pedido->idMozo = $parametros['idMozo'];
            $pedido->idMesa = $parametros['idMesa'];
            $pedido->estado = $parametros['estado'];
            $pedido->tiempoEstimado = $parametros['tiempoEstimado'];
            $descripcionPedido = $parametros['descripcionPedido'];

            $arrayProductos = explode(",", $descripcionPedido);
            $productos = array();


            foreach ($arrayProductos as $id) {
                $producto = Producto::TraerProducto_Id($id);
                array_push($productos, $producto);
            }
            $pedido->descripcionPedido = json_encode($productos);

            Pedido::ModificarPedido($id, $pedido->idMozo, $pedido->idMesa, $pedido->estado, $pedido->tiempoEstimado, $pedido->descripcionPedido);
            $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error en modificar Pedido"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>