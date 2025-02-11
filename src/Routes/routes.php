<?php
// src/Routes/routes.php

use Slim\App;
use App\Controllers\EstudianteController;
use App\Controllers\UserControllerAuth;
use App\Middleware\AuthMiddleware;

return function (App $app) {
    // Manejar solicitudes OPTIONS para preflight
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    });

    $app->get('/estudiante', [EstudianteController::class, 'getAllEstudiante']);
    $app->get('/estudiante/{id}', [EstudianteController::class, 'getEstudianteById']);
    $app->post('/estudiante', [EstudianteController::class, 'createEstudiante']);
    $app->delete('/estudiante/{id}', [EstudianteController::class, 'deleteEstudiante']);
    
    //$app->get('/citas', [CitaController::class, 'getAllCitas'])->add(new AuthMiddleware($app->getContainer()->get(PDO::class), $app->getContainer()->get('secretKey')),['paciente']);
    //$app->post('/citas', [CitaController::class, 'createCita']);

    //$app->get('/medicos', [MedicoController::class, 'getAllMedicos']);

    $app->post('/usuario', [UserControllerAuth::class, 'register']);
    $app->post('/login', [UserControllerAuth::class, 'login']);
};
