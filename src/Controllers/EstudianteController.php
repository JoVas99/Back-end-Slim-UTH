<?php

namespace App\Controllers;

use App\Models\EstudianteModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class EstudianteController
{
    private $estudianteModel;

    public function __construct(PDO $db)
    {
        $this->estudianteModel = new EstudianteModel($db);
    }

    public function getAllEstudiante(Request $request, Response $response)
    {
        $citas=$this->estudianteModel->getAllEstudiante();

        // Escribir en el cuerpo de la respuesta con getBody()->write()
        $response->getBody()->write(json_encode($citas));
        return $response->withHeader('Content-Type','application/json')->withStatus(200);
    }
    public function getEstudianteById(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $alumno = $this->estudianteModel->getEstudianteById($id);

        // Escribir en el cuerpo de la respuesta con getBody()->write()
        $response->getBody()->write(json_encode($alumno));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    public function createEstudiante(Request $request, Response $response)
    {
        $datos = $request->getParsedBody();

        // Validación básica de los datos
        if (!isset($datos['nombre']) || !isset($datos['apellido']) || !isset($datos['edad']) || !isset($datos['genero']) || !isset($datos['usuario_id'])) {
            $response->getBody()->write("Datos inválidos");
            return $response->withStatus(400);
        }

        // Guardar alumno en la base de datos
        $idNuevoEstudiante = $this->estudianteModel->createEstudiante($datos);

        if ($idNuevoEstudiante) {
            $response->getBody()->write(json_encode(["Estudiante creado con ID"=> $idNuevoEstudiante]));
            return $response->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(["error"=>"Error al crear el estudiante"]));
            return $response->withStatus(500);
        }
    }
    public function updateEstudiante(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $datos = $request->getParsedBody();

        // Validación básica de los datos
        if (!isset($datos['nombre']) || !isset($datos['apellido']) || !isset($datos['edad']) || !isset($datos['genero']) || !isset($datos['usuario_id'])) {
            $response->getBody()->write("Datos inválidos");
            return $response->withStatus(400);
        }

        // Actualizar alumno en la base de datos
        $resultado = $this->estudianteModel->updateEstudiante($id, $datos);

        if ($resultado) {
            $response->getBody()->write(json_encode(["message"=>"Alumno actualizado"]));
            return $response->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(["error"=>"Error al actualizar el estudiante"]));
            return $response->withStatus(500);
        }
    }
    public function deleteEstudiante(Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        // Eliminar alumno en la base de datos
        $resultado = $this->estudianteModel->deleteEstudiante($id);

        if ($resultado) {
            $response->getBody()->write(json_encode(["message"=>"Estudiante eliminado"]));
            return $response->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(["error"=>"Error al eliminar el estudiante"]));
            return $response->withStatus(500);
        }
    }
}