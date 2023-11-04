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
}

?>