<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use App\Models\UserModelAuth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserControllerAuth
{
    private $secretKey;
    private $userModel;

    public function __construct($secretKey,\PDO $db)
    {
        $this->userModel = new UserModelAuth($db);
        $this->secretKey = $secretKey;
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $username = $data['correo'];
        $password = $data['password'];

        // Consultar la base de datos para obtener el usuario por correo
        $user = $this->userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Generar el token JWT con el ID del usuario
            $payload = [
                'iss' => 'http://medisys.com', // Emisor
                'sub' => $user['id'],      // ID del usuario (Sujeto)
                'iat' => time(),           // Emitido en
                'exp' => time() + (60 * 60), // Expiración en 1 hora
                'data'=>[
                    'username'=> $username,
                    'rol'=>$user['rol']
                ]
            ];

            $token = JWT::encode($payload, $this->secretKey, 'HS256');

            $response->getBody()->write(json_encode(['token' => $token,'rol'=>$user['rol']]));
            return $response->withHeader('Content-Type','application/json')->withStatus(201);
        } else {
            $response->getBody()->withStatus(401)->write(json_encode(['error' => 'Invalid credentials']));
            return $response->withHeader('Content-Type','application/json')->withStatus(500);
        }
    }
    public function register(Request $request, Response $response)
    {
        $datos = $request->getParsedBody();

        // Validación básica de los datos
        if (!isset($datos['nombre_usuario']) || !isset($datos['password']) || !isset($datos['rol'])) {
            $response->getBody()->write("Datos inválidos");
            return $response->withStatus(400);
        }

        // Guardar paciente en la base de datos
        $idNuevoUsuario = $this->userModel->createUser($datos);

        if ($idNuevoUsuario) {
            $response->getBody()->write(json_encode(["message"=> "Usuario creado con exito", "id" => $idNuevoUsuario]));
            return $response->withHeader('Content-Type','application/json')->withStatus(201);
            
        } else {
            $response->getBody()->write("Error al crear el usuario");
            return $response->withStatus(500);
        }
    }
}
