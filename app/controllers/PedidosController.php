<?php

class PedidosController
{
    
        public static function InsertarPedido($request, $response, $args)
        {
            $parametros = $request->getParsedBody();
    
            $idMesa = $parametros['idMesa'];
            $idMozo = $parametros['idMozo'];
            $descripcionPedido = $parametros['descripcionPedido'];

    
            $pedido = new Pedido();
            $pedido->idMesa = $idMesa;
            $pedido->idMozo = $idMozo;
            $pedido->descripcionPedido = $descripcionPedido;

    
            $pedido->CrearPedido();
    
            $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
    
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
}

?>