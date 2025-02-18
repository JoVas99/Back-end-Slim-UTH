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
        $sql = "SELECT * FROM estudiante";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    public function getEstudianteById($id)
    {
        $sql = "SELECT * FROM estudiante WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function createEstudiante($data)
    {
        $sql = "INSERT INTO estudiante(nombre, apellido, edad, genero, usuario_id) VALUES (:nombre, :apellido, :edad, :genero, :usuario_id)";
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
        $stmt = $this->db->prepare("UPDATE estudiante SET nombre = :nombre, apellido = :apellido, edad = :edad, genero = :genero, usuario_id = :usuario_id WHERE id = :id");
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':edad', $data['edad']);
        $stmt->bindParam(':genero', $data['genero']);
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function deleteEstudiante($id)
    {
        $stmt = $this->db->prepare("DELETE FROM estudiante WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}