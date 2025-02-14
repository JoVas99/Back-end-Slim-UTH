<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserModelAuth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    private $secretKey;
    private $userModel;
    private $allowedRoles;

    public function __construct(\PDO $db,$secretKey, array $allowedRoles = [])
    {
        $this->secretKey = $secretKey;
        $this->userModel = new UserModelAuth($db);
        $this->allowedRoles = $allowedRoles;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');
        $response = new \Slim\Psr7\Response();

        if (!$authHeader) {
            $response->getBody()->write(json_encode(['error' => 'Token not provided']));
            return $response->withHeader('Content-Type','application/json')->withStatus(401);
        }

        $token = str_replace('Bearer ', '', $authHeader[0]);

        try {
            // Decodificar el token JWT
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            // Consultar la base de datos para obtener el usuario
            $user = $this->userModel->getUserById($decoded->sub);

            if (!$user) {
                $response->getBody()->write(json_encode(['error' => 'User not found']));
                return $response->withHeader('Content-Type','application/json')->withStatus(401);
            }

            // Verificar si el rol del usuario está permitido para acceder a la ruta
            if (!empty($this->allowedRoles) && !in_array($user['rol'], $this->allowedRoles)) {
                $response->getBody()->write(json_encode(['error' => 'Access denied']));
                return $response->withHeader('Content-Type','application/json')->withStatus(403);
            }

            // Agregar el usuario a la solicitud para acceder a él en el controlador
            $request = $request->withAttribute('user', $user);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Token is invalid or expired']));
            return $response->withHeader('Content-Type','application/json')->withStatus(401);
        }

        // Pasa la solicitud al siguiente middleware o controlador
        return $handler->handle($request);
    }
}
