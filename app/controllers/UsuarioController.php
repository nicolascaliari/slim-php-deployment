<?php

require_once './models/Usuario.php';
class UsuarioController extends Usuario
{

    public static function InsertarUsuarioController($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $tipo = $parametros['tipo'];


        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->tipo = $tipo;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }



    public function TraerUsuariosController($request, $response, $args)
    {
        $usuarios = Usuario::TraerUsuarios();

        $payload = json_encode(array("listaUsuarios" => $usuarios));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}


?>