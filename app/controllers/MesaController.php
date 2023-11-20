<?php

require_once './models/Mesa.php';
class MesaController
{
    public static function InsertMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];

        $mesa = new Mesa();
        $mesa->estado = $estado;

        $mesa->CrearMesa();


        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }



    public static function TraerMesas($request, $response, $args)
    {
        $mesas  = Mesa::TraerMesas();
        $mesasMap = Mesa::MapearParaMostrar($mesas);
    
        $payload = json_encode(array("listaMesas" => $mesasMap));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }




    public static function CambiarEstadoMesaPorPedido($id_pedido){
        $pedido = Pedido::TraerPedidoPorID($id_pedido);
        $mesa = Mesa::TraerMesaPorID($pedido->idMesa);
        switch ($pedido->estado) {
            case "En espera":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::CambiarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "En preparacion":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::CambiarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Finalizado":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::CambiarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Entregado":
                $estadoMesa = "con cliente comiendo";
                Mesa::CambiarEstadoMesa($mesa->id, $estadoMesa);
                break;
        }
    }



    public function BajaMesa($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $mesa = Mesa::TraerMesaPorID($id);
        if($mesa){
            if(Mesa::EliminarMesa($id)){
                $payload = json_encode(array("mensaje" => "Mesa eliminado con exito"));
            }
        }else{
            $payload = json_encode(array("mensaje" => "Error en eliminar Mesa"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarMesa($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $mesa = Mesa::TraerMesaPorID($id);
        if($mesa){
            if (isset($parametros['estado'])){
                $mesa->estado = $parametros['estado'];
            }else{
                $payload = json_encode(array("mensaje" => "Parametros insuficientes"));
            }

            Mesa::ModificarMesa($id, $mesa->estado);
            $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Error en modificar Mesa"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>