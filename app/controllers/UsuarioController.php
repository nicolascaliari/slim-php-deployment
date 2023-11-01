<?php

require_once './models/Usuario.php';
class UsuarioController
{

    public static function POST_InsertarUsuario($request, $response, $args)
    {
        $param = $request->getQueryParams();
        // if (!isset($param['token'])) {
        $retorno = json_encode(array("mensaje" => "Token necesario"));
        //    } else {
        //  $token = $param['token'];
        //  $respuesta = Autenticador::ValidarToken($token, "Admin");
        //  if ($respuesta == "Validado") {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $tipo = $parametros['tipo'];

        $user = new Usuario($nombre, $apellido, $tipo);
        $ok = $user->CrearUsuario();
        if ($ok != null) {
            $retorno = json_encode(array("mensaje" => "Usuario creado con exito"));
        } else {
            $retorno = json_encode(array("mensaje" => "No se pudo crear"));
        }
        //   } else {
        //      $retorno = json_encode(array("mensaje" => $respuesta));
        //     }
        //   }
        $response->getBody()->write($retorno);
        return $response;
    }


    // public function altaUsuario($request, $response, $args) {
    //     $data = $request->getParsedBody();
    //     $usuario = new Usuario($data['id'], $data['nombre'], $data['apellido'], $data['tipo']);
    //     $response->getBody()->write(json_encode($usuario->CrearUsuario()));
    //     return $response;
    // }

    // public function traerUsuarios($request, $response, $args) {
    //     $response->getBody()->write(json_encode(Usuario::TraerUsuarios()));
    //     return $response;
    // }
}


?>