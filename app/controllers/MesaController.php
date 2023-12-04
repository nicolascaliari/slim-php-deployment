<?php

require_once './models/Mesa.php';

class MesaController
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $codigoMesa = $params['codigoMesa'];

        $newTable = new Mesa();
        $newTable->codigoMesa = $codigoMesa;
        $newTable->estadoMesa = "Cerrada";
        $newTable->fechaAlta = date('Y-m-d');
        $newTable->fechaModificacion = date('Y-m-d');
        $newTable->activo = "SI";
        $newTable->CrearMesa();

        $payload = json_encode(array("mensaje" => "La mesa se ha creado exitosamente"));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::ObtenerTodos();
        $payload = json_encode(array("listaDeMesas" => $lista));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $codigoMesa = $params['codigoMesa'];
        $estadoMesa = $params['estadoMesa'];

        $idMesa = Mesa::ObtenerIdMesaPorCodigo($codigoMesa);

        if(Mesa::ModificarMesa($idMesa, $codigoMesa, $estadoMesa) > 0)
        {
            $payload = json_encode(array("mensaje" => "La mesa {$idMesa} se actualizo correctamente"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "La mesa no se actualizo"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function SocioCierraMesa($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $codigoMesa = $params['codigoMesa'];


        if(Mesa::CerrarMesa($codigoMesa) > 0)
        {
            $payload = json_encode(array("mensaje" => "La mesa {$codigoMesa} se cerro correctamente"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "La mesa no se actualizo"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $idMesa = $args['idMesa'];

        if(Mesa::BorrarMesa($idMesa) > 0)
        {
            $payload = json_encode(array("mensaje" => "La mesa {$idMesa} se dio de baja"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se realizo la baja"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerMesaMasUsada($request, $response, $args)
    {
        $pedidos = Pedido::ObtenerTodos();

        $mesaMasUsada = Mesa::ObtenerMesaMasUsada($pedidos);

        if($mesaMasUsada)
        {
            $payload = json_encode(array("Mesa Mas Usada" => $mesaMasUsada));
        }
        else
        {
            $payload = json_encode(array("No se encontro una mesa mas utilizada"));
        }
        

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerMejoresComentarios($request, $response, $args)
    {
        $pedidos = Pedido::ObtenerTodos();

        $mejoresComentarios = Mesa::ObtenerMejoresComentarios($pedidos);

        $payload = json_encode(array("Los mejores comentarios son" => $mejoresComentarios));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>