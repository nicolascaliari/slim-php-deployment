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
require_once './controllers/PedidosController.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/LoggerMiddleware.php';

require_once(__DIR__ . '/./middlewares/AuthMiddleware.php');
require_once(__DIR__ . '/./middlewares/LoggerMiddleware.php');


require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();


$app->group('/auth', function (RouteCollectorProxy $group) {
    $group->post('/login', \UsuarioController::class . ':login');
});


$app->group('/', function (RouteCollectorProxy $group) {

    $group->get('[/]', function (Request $request, Response $response) {
        $response->getBody()->write("Bienvenido a la api de la comanda");
        return $response;
    });
});



$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerUsuariosController')
        ->add(new LoggerMidleware())
        ->add(new AuthMiddleware('admin'));

    $group->post('/insertar', \UsuarioController::class . ':InsertarUsuarioController')
        ->add(new LoggerMidleware())
        ->add(new AuthMiddleware('admin'));

    $group->delete('/eliminar', \UsuarioController::class . ':EliminarUsuarioController')
        ->add(new LoggerMidleware())
        ->add(new AuthMiddleware('admin'));

    $group->post('/cambiar', \UsuarioController::class . ':ModificarUsuarioController')
        ->add(new LoggerMidleware())
        ->add(new AuthMiddleware('admin'));

    $group->get('/guardar', \UsuarioController::class . ':GuardarUsuarios')
        ->add(new LoggerMidleware())
        ->add(new AuthMiddleware('admin'));
        
    $group->get('/cargar', \UsuarioController::class . ':CargarUsuarios')
        ->add(new LoggerMidleware())
        ->add(new AuthMiddleware('admin'));
});




$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerProductosController');
    $group->post('/insertar', \ProductoController::class . ':InsertarProducto');
    $group->delete('/eliminar', \ProductoController::class . ':BajaProducto');
    $group->post('/modificar', \ProductoController::class . ':ModificarProductoController');
});



$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerMesas');
    $group->post('/insertar', \MesaController::class . ':InsertMesa');
    $group->delete('/eliminar', \MesaController::class . ':BajaMesa');
    $group->post('/modificar', \MesaController::class . ':ModificarMesa');
});


$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidosController::class . ':TraerPedidos');
    $group->post('/insertar', \PedidosController::class . ':InsertarPedido');
    $group->post('/cambiarEstado', \PedidosController::class . ':ModificarEstado');
    $group->delete('/eliminar', \PedidosController::class . ':BajaPedido');
    $group->post('/modificar', \PedidosController::class . ':ModificarPedido');
});

$app->run();
