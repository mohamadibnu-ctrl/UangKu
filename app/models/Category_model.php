<?php

class Category_model {
    private $table = 'categories';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getCategoriesByUser($user_id)
    {
        // Mengambil kategori default (user_id NULL) dan kategori milik user
        $this->db->query("SELECT * FROM {$this->table} WHERE user_id = :user_id OR is_default = 1 ORDER BY is_default DESC, name ASC");
        $this->db->bind('user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getCategoryById($id, $user_id)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id AND (user_id = :user_id OR is_default = 1)");
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $user_id);
        return $this->db->single();
    }

    public function getCategoryByName($name, $user_id)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE name = :name AND (user_id = :user_id OR is_default = 1)");
        $this->db->bind('name', $name);
        $this->db->bind('user_id', $user_id);
        return $this->db->single();
    }

    public function addCategory($data, $user_id)
    {
        $query = "INSERT INTO {$this->table} (user_id, name, is_default) VALUES (:user_id, :name, 0)";
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('name', $data['name']);

        return $this->db->execute();
    }

    public function updateCategory($data, $user_id)
    {
        // Hanya bisa update jika milik user sendiri dan bukan default
        $query = "UPDATE {$this->table} SET name = :name WHERE id = :id AND user_id = :user_id AND is_default = 0";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('id', $data['id']);
        $this->db->bind('user_id', $user_id);

        return $this->db->execute();
    }

    public function deleteCategory($id, $user_id)
    {
        // Cek apakah kategori sedang digunakan di tabel transactions
        $this->db->query("SELECT COUNT(*) as count FROM transactions WHERE category_id = :id");
        $this->db->bind('id', $id);
        $result = $this->db->single();

        if ($result['count'] > 0) {
            return false; // Sedang digunakan, tidak boleh dihapus
        }

        // Hapus hanya jika milik user sendiri dan bukan default
        $query = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id AND is_default = 0";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $user_id);

        return $this->db->execute();
    }
}
