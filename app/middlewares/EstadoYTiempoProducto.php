<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class EstadoYTiempoProductoMW
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $data = AutentificadorJWT::ObtenerData($token);

        $params = $request->getParsedBody();

        $idProducto = $params['idProducto'];

        $producto = Producto::ObtenerProductoSegunId($idProducto);

        //echo $data->rol;

        if($producto->sector == $data->rol || $data->rol == "Socio")
        {
            $response = $handler->handle($request);
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array("Error" => "No sos un usuario habilitado para realizar esta accion"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');

    }
}

?>