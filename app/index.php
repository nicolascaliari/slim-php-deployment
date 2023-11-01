<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
require_once './controllers/UsuarioController.php';
require_once './db/AccesoDatos.php';



require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$app->group('/', function (RouteCollectorProxy $group) {

    $group->get('[/]', function (Request $request, Response $response) {
        $response->getBody()->write("Bienvenido a la api de la comanda");
        return $response;
    });
});



$app->group('/usuarios', function (RouteCollectorProxy $group) {
    // $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    // $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':POST_InsertarUsuario');
  });

// Run app
$app->run();
