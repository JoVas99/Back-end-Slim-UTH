<?php

namespace App\Models;

class EstudianteModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getAllEstudiante()
    {
        $sql = "CALL spEstudianteSelect()";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    public function getEstudianteById($id)
    {
        $sql = "CALL spEstudianteSelectId(:id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function createEstudiante($data)
    {
        $sql = "CALL spEstudianteInsert(:nombre, :apellido, :edad, :genero, :usuario_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':edad', $data['edad']);
        $stmt->bindParam(':genero', $data['genero']);
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->execute();
        return $this->db->lastInsertId(); 
    }
    public function updateEstudiante($id, $data)
    {
        $stmt = $this->db->prepare("CALL spEstudianteUpdate (:id, :nombre, :apellido, :edad, :genero, :usuario_id)");
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':edad', $data['edad']);
        $stmt->bindParam(':genero', $data['genero']);
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function deleteEstudiante($id,$usuario_id)
    {
        //Eliminar estudiante
        $stmt = $this->db->prepare("CALL spEstudianteDelete(:id)");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Eliminar usuario
        $stmt = $this->db->prepare("CALL spUsuariosDelete(:usuario_id)");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        return true;
    }
}