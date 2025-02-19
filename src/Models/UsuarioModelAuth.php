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
        $query = "CALL spUsuariosSelectId(:id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getUserByEmail($correo)
    {
        $query = "CALL spUsuariosSelectCorreo(:correo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function createUser($data)
    {
        $hashedContraseña = password_hash($data['password'],PASSWORD_DEFAULT);

        $query = "CALL spUsuariosInsert(:correo, :password, :rol, @idUsuario)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':correo',$data['correo']);
        $stmt->bindParam(':password',$hashedContraseña);
        $stmt->bindParam(':rol',$data['rol']);
        
        $stmt->execute();
        $stmt->closeCursor(); // IMPORTANTE: liberar la conexión antes de la siguiente consulta

        // Recuperar el ID generado
        $query = "SELECT @idUsuario AS id";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch();

        return $result['id'] ?? null; // Devolver el ID o null si hubo un error
    }
}

