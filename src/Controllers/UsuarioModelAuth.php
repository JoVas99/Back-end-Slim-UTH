<?php

namespace App\Models;

class UserModelAuth
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    // Método para obtener el usuario de la base de datos
    public function getUserById($userId)
    {
        $query = "SELECT id, correo, rol FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getUserByEmail($nombre_usuario)
    {
        $query = "SELECT id, correo, password, rol FROM usuarios WHERE correo = :correo";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function createUser($data)
    {
        $hashedContraseña = password_hash($data['password'],PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios (correo, password, rol) VALUES (:correo, :password, :rol)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo',$data['correo']);
        $stmt->bindParam(':password',$hashedContraseña);
        $stmt->bindParam(':rol',$data['rol']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
}

