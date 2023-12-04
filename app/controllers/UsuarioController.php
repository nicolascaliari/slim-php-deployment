<?php

require_once './models/Usuario.php';

class UsuarioController
{
    public function CargarUno($request, $response, $args)
    {

        $params = $request->getParsedBody();

        $nombre = $params['nombre'];
        $clave = $params['clave'];
        $mail = $params['mail'];
        $rol = $params['rol'];

        $newUser = new Usuario();
        $newUser->nombre = $nombre;
        $newUser->clave = $clave;
        $newUser->mail = $mail;
        $newUser->rol = $rol;
        $newUser->fechaAlta = date('Y-m-d');
        $newUser->fechaModificacion = date('Y-m-d');
        $newUser->estadoDeCuenta = "Activo";
        $newUser->CrearUsuario();

        $payload = json_encode(array("mensaje" => "El usuario se ha creado exitosamente"));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::ObtenerTodos();
        $payload = json_encode(array("listaDeUsuarios" => $lista));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $idUsuario = $params['idUsuario'];
        $nombre = $params['nombre'];
        $clave = $params['clave'];
        $mail = $params['mail'];
        $rol = $params['rol'];
        $fechaAlta = $params['fechaAlta'];
        

        if(Usuario::ModificarUsuario($nombre, $clave, $mail, $rol, $fechaAlta, $idUsuario) > 0)
        {
            $payload = json_encode(array("mensaje" => "El usuario {$idUsuario} se actualizo correctamente"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se realizaron modificaciones"));
        }


        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
        
    }

    public function BorrarUno($request, $response, $args)
    {
        $idUsuario = $args['idUsuario'];

        if(Usuario::BorrarUsuario($idUsuario) > 0)
        {
            $payload = json_encode(array("mensaje" => "El usuario {$idUsuario} se dio de baja"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se realizo la baja"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function LoginUsuario($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $mail = $params['mail'];
        $clave = $params['clave'];

        $usuario = Usuario::VerificarSiExisteUsuario($mail, $clave);

        if($usuario)
        {
            $datos = array('idUsuario' => $usuario->idUsuario, 'rol' => $usuario->rol);
            $token = AutentificadorJWT::CrearToken($datos);
            $payload = json_encode(array('jwt' => $token));
        }
        else
        {
            $payload = json_encode(array("Error" => "El usuario o la clave es incorrecta"));
        }
        
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
        
    }

    public function CargarUsuariosEnCSV($request, $response, $args)
    {
        $listaDeUsuario = Usuario::ObtenerTodos();

        $archivo = fopen("Usuarios.csv", "w");

        if($archivo != false)
        {
            $encabezado = array("IdUsuario", "Nombre", "Clave", "Mail", "Rol", "FechaAlta", "FechaModificacion", "EstadoDeCuenta");
            fputcsv($archivo, $encabezado);
            foreach($listaDeUsuario as $usuario)
            {
                fputcsv($archivo, 
                [$usuario->idUsuario, $usuario->nombre, $usuario->clave, $usuario->mail, $usuario->rol, $usuario->fechaAlta, $usuario->fechaModificacion, $usuario->estadoDeCuenta]);
            }

            $payload = json_encode(array("mensaje" => "Los usuarios se cargaron correctamente en el archivo"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error al abrir el archivo"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
        
    }

    public function DescargarUsuariosDesdeCSV($request, $response, $args)
    {
        $archivo = fopen("Usuarios.csv", "r");

        if($archivo != false)
        {
            Usuario::BorrarUsuariosBD();

            fgets($archivo);

            while(($datos = fgetcsv($archivo)) !== false)
            {
                $usuario = new Usuario();
                $usuario->idUsuario = $datos[0];
                $usuario->nombre = $datos[1];
                $usuario->clave = $datos[2];
                $usuario->mail = $datos[3];
                $usuario->rol = $datos[4];
                $usuario->fechaAlta = $datos[5];
                $usuario->fechaModificacion = $datos[6];
                $usuario->estadoDeCuenta = $datos[7];

                $usuario->CrearUsuario();
            }

            $payload = json_encode(array("mensaje" => "Los usuarios se descargaron correctamente"));

            fclose($archivo);
            
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error al abrir el archivo"));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

}
?>