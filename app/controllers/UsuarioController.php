<?php

require_once './models/Usuario.php';

$directorio = __DIR__;

var_dump($directorio);
require_once(__DIR__ . '/../utils/autenticadorJWT.php');

class UsuarioController extends Usuario
{

    public static function InsertarUsuarioController($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $tipo = $parametros['tipo'];
        $user = $parametros['user'];
        $password = $parametros['password'];


        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->tipo = $tipo;
        $usr->password = $password;
        $usr->user = $user;
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


    public static function EliminarUsuarioController($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
    
        if (isset($parametros['id'])) {
            $id = $parametros['id'];
    
            var_dump($id);
            $usuario = Usuario::TraerUsuarioPorId($id);
    
            if ($usuario) {
                if (Usuario::EliminarUsuario($id)) {
                    $payload = json_encode(array("mensaje" => "Usuario eliminado con exito"));
                } else {
                    $payload = json_encode(array("mensaje" => "Error al eliminar usuario"));
                }
            } else {
                $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "ID de usuario no proporcionado"));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader("Content-Type", "application/json");
    }


    public static function ModificarUsuarioController($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
    
        $usuario = Usuario::TraerUsuarioPorID($id);
    
        if ($usuario !== false) {

            if (isset($parametros['nombre'])) {
                $usuario->nombre = $parametros['nombre'];
            } elseif (isset($parametros['apellido'])) {
                $usuario->apellido = $parametros['apellido'];
            } elseif (isset($parametros['tipo'])) {
                $usuario->tipo = $parametros['tipo'];
            } elseif (isset($parametros['user'])) {
                $usuario->user = $parametros['user'];
            } elseif (isset($parametros['password'])) {
                $usuario->password = $parametros['password'];
            }
    
            Usuario::ModificarUsuario($id, $usuario->nombre, $usuario->apellido, $usuario->tipo, $usuario->user, $usuario->password);
    
            $payload = json_encode(array("mensaje" => "Usuario modificado con éxito"));
        } else {
            $payload = json_encode(array("mensaje" => "Error en modificar usuario. Usuario no encontrado"));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public static function login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $username = $parametros['user'];
        $contrasenia = $parametros['password'];
        $usuario = Usuario::TraerUsuarioPorLogin($username, $contrasenia);

        if ($usuario) {
            $datos = array('id' => $usuario->id, 'tipo' => $usuario->tipo);
            $token = AutentificadorJWT::CrearToken($datos);
            $payload = json_encode(array('jwt' => $token));
        } else {
            $payload = json_encode(array('error' => 'Usuario o contraseña incorrectos'));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }



    public static function GuardarUsuarios($request, $response, $args){
        $path = "usuarios.csv";
        $param = $request->getQueryParams();
        $usuariosArray = array();
        $usuarios = Usuario::TraerUsuarios();

        foreach($usuarios as $i){
            $usuario = array($i->id, $i->nombre, $i->apellido, $i->tipo, $i->user, $i->password, $i->estado);
            $usuariosArray[] = $usuario;
        }

        $archivo = fopen($path, "w");
        $encabezado = array("id", "nombre", "apellido", "tipo", "user", "password", "estado");
        fputcsv($archivo, $encabezado);
        foreach($usuariosArray as $fila){
            fputcsv($archivo, $fila);
        }
        fclose($archivo);
        $retorno = json_encode(array("mensaje"=>"Usuarios guardados en CSV con exito"));
           
        $response->getBody()->write($retorno);
        return $response;
    }

    public static function CargarUsuarios($request, $response, $args){
        $path = "usuarios.csv";
        $archivo = fopen($path, "r");
        $encabezado = fgets($archivo);

        while(!feof($archivo)){
            $linea = fgets($archivo);
            $datos = str_getcsv($linea);
            var_dump($datos);
                $usuario = new Usuario();
                $usuario->id = $datos[0];
                $usuario->nombre = $datos[1];
                $usuario->apellido = $datos[2];
                $usuario->tipo = $datos[3];
                $usuario->user = $datos[4];
                $usuario->password = $datos[5];
                $usuario->estado = $datos[6];
                $usuario->CrearUsuario();
        }
        fclose($archivo);
                
        $retorno = json_encode(array("mensaje"=>"Usuarios guardados en base de datos con exito"));
        $response->getBody()->write($retorno);
        return $response;
    }



}


?>