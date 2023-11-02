<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
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
    $group->get('[/]', \UsuarioController::class . ':TraerUsuariosController');
    // $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':InsertarUsuarioController');
});




$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerProductosController');
    // $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':InsertarProducto');
});



$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerMesas');
    // $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \MesaController::class . ':InsertMesa');
});

// Run app
$app->run();
