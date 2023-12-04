<?php
require_once './models/ProductoPedido.php';
class EncuestaController{
    public function AltaEncuesta($request, $response, $args){
        $parametros = $request->getParsedBody();

        if(isset($parametros['idMesa']) && isset($parametros['nombreCliente']) && isset($parametros['descripcion']) && isset($parametros['puntuacionMesa'])
         && isset($parametros['puntuacionMozo']) && isset($parametros['puntuacionCocinero']) && isset($parametros['puntuacionRestaurant']))
        {
            $encuesta = new Encuesta();
            $encuesta->idMesa = $parametros['idMesa'];
            $encuesta->nombreCliente = $parametros['nombreCliente'];
            $encuesta->descripcion = $parametros['descripcion'];
            $encuesta->puntuacionMesa = $parametros['puntuacionMesa'];
            $encuesta->puntuacionMozo = $parametros['puntuacionMozo'];
            $encuesta->puntuacionCocinero = $parametros['puntuacionCocinero'];
            $encuesta->puntuacionRestaurant = $parametros['puntuacionRestaurant'];
            $encuesta->estado = "Activo";
            Encuesta::CrearEncuesta($encuesta);
            $payload = json_encode(array("mensaje" => "Encuesta creado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo crear la encuesta."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerEncuestas($request, $response, $args)
    {
        $lista = Encuesta::TraerEncuestas();

        $payload = json_encode($lista);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function ObtenerUnaEncuesta($request, $response, $args)
    {
        $id = $args['id'];
        $encuesta = Encuesta::TraerPorId($id);
        $payload = json_encode($encuesta);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}
?>