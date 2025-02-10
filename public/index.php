<?php
// public/index.php

use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;
use Slim\Middleware\BodyParsingMiddleware;
// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno desde el archivo .env
//$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
//$dotenv->load();

// Crear el contenedor de dependencias
$container = new Container();


// Cargar configuraciones
$settings = require __DIR__ . '/../config/settings.php';
// $dbConfig = require __DIR__ . '/../config/db.php';

// Crear la aplicación Slim

// Configura el contenedor para la conexión PDO
$container->set(PDO::class, function(){
    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASSWORD'];

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    return new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
});


// Configurar la clave secreta en el contenedor
$container->set('secretKey', $_ENV['SECRET_KEY']);

// Registrar el controlador en el contenedor
$container->set(App\Controllers\UserControllerAuth::class, function($container) {
    return new App\Controllers\UserControllerAuth(
        $container->get('secretKey'),
        $container->get(PDO::class)
    );
});

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// Middleware para manejar CORS (se ejecuta en cada petición)
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200') // Permitir tu frontend
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');  // Si necesitas enviar cookies o credenciales
});

// Incluir las rutas
(require __DIR__ . '/../src/Routes/routes.php')($app);

// Ejecutar la aplicación
$app->run();
