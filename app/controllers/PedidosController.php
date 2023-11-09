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

        $pedido = new Pedido();


        $validacionDeMesa = $pedido->MesaDisponible($idMesa);
        $validacionDeMozo = $pedido->MozoExistente($idMozo);

        if ($validacionDeMesa == false || $validacionDeMozo == false) {
            $payload = json_encode(array("mensaje" => "La mesa no esta disponible o el mozo no esta disponible"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else if ($validacionDeMesa == true && $validacionDeMozo == true) {
            $pedido->idMesa = $idMesa;
            $pedido->idMozo = $idMozo;
            $pedido->descripcionPedido = $descripcionPedido;
            $pedido->estado = "en preparacion";


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



    public static function CambiarEstadoPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $pedido = new Pedido();

        $pedido->estado = $parametros['estado'];
        $pedido->id = $parametros['id'];

        if ($pedido->estado === "en preparacion") {
            $pedido->CambiarEstadoPedido("finalizado");
            $retorno = json_encode(array("mensaje" => "Estado cambiado con exito"));
        } else {
            if ($pedido->estado === "finalizado") {

                $pedido->CambiarEstadoPedido("entregado");
                $retorno = json_encode(array("mensaje" => "Estado cambiado con exito"));

            } else {
                $retorno = json_encode(array("mensaje" => "Valor de esatdo no valido"));
            }
        }

        $response->getBody()->write($retorno);
        return $response;
    }
}

?>