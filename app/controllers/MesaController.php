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




    public static function POST_cambiar_estado_de_mesa($request, $response, $args)
    {
        $param = $request->getQueryParams();

        $parametros = $request->getParsedBody();



        $mesa = new Mesa();

        $mesa->estado = $parametros['estado'];
        $mesa->id = $parametros['id'];

        if ($mesa->estado < 4) {
            $mesa->CambiarEstadoMesa($mesa->estado);
            $retorno = json_encode(array("mensaje" => "Estado cambiado con exito"));
        } else {
            if ($mesa->estado == 4) {

                $mesa->CambiarEstadoMesa($mesa->estado);
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