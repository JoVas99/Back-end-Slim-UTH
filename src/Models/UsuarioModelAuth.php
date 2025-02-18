<?php

namespace App\Models;

class UsuarioModelAuth
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    // Método para obtener el usuario de la base de datos
    public function getUserById($userId)
    {
        $query = "SELECT fObtenerUsuariosPorID(:id) AS usuarios";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getUserByEmail($correo)
    {
        $query = "SELECT fObtenerUsuariosPorCorreo(:correo) AS usuarios";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function createUser($data)
    {
        $hashedContraseña = password_hash($data['password'],PASSWORD_DEFAULT);

        $query = "CALL spUsuariosInsert(:correo, :password, :rol)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo',$data['correo']);
        $stmt->bindParam(':password',$hashedContraseña);
        $stmt->bindParam(':rol',$data['rol']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
}

