<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUserById($id)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function getUserByVerificationToken($token)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE verification_token = :token");
        $this->db->bind('token', $token);
        return $this->db->single();
    }
    
    public function getUserByResetToken($token)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE reset_token = :token");
        $this->db->bind('token', $token);
        return $this->db->single();
    }

    public function register($data, $token, $token_expiry)
    {
        $query = "INSERT INTO {$this->table} 
                  (name, email, password, verification_token, verification_token_expires_at) 
                  VALUES (:name, :email, :password, :token, :expiry)";
                  
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind('token', $token);
        $this->db->bind('expiry', $token_expiry);

        return $this->db->execute();
    }

    public function verifyEmail($userId)
    {
        $query = "UPDATE {$this->table} 
                  SET email_verified_at = :verified_at, 
                      verification_token = NULL, 
                      verification_token_expires_at = NULL 
                  WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('verified_at', date('Y-m-d H:i:s'));
        $this->db->bind('id', $userId);
        return $this->db->execute();
    }

    public function updateResetToken($email, $token, $expiry)
    {
        $query = "UPDATE {$this->table} 
                  SET reset_token = :token, 
                      reset_token_expires_at = :expiry 
                  WHERE email = :email";
                  
        $this->db->query($query);
        $this->db->bind('token', $token);
        $this->db->bind('expiry', $expiry);
        $this->db->bind('email', $email);
        return $this->db->execute();
    }

    public function resetPassword($userId, $newPassword)
    {
        $query = "UPDATE {$this->table} 
                  SET password = :password, 
                      reset_token = NULL, 
                      reset_token_expires_at = NULL 
                  WHERE id = :id";
                  
        $this->db->query($query);
        $this->db->bind('password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->bind('id', $userId);
        return $this->db->execute();
    }
}
