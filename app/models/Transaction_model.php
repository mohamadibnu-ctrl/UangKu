<?php

class Transaction_model {
    private $table = 'transactions';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getTransactionsByUser($user_id, $filters = [], $limit = 10, $offset = 0)
    {
        $query = "SELECT t.*, c.name as category_name 
                  FROM {$this->table} t
                  JOIN categories c ON t.category_id = c.id 
                  WHERE t.user_id = :user_id";
        
        $params = ['user_id' => $user_id];

        // Apply filters
        if (!empty($filters['search'])) {
            $query .= " AND t.description LIKE :search";
            $params['search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['category_id'])) {
            $query .= " AND t.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }

        if (!empty($filters['start_date'])) {
            $query .= " AND t.date >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND t.date <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        $query .= " ORDER BY t.date DESC, t.id DESC LIMIT $limit OFFSET $offset";

        $this->db->query($query);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    public function countTransactionsByUser($user_id, $filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} t WHERE t.user_id = :user_id";
        $params = ['user_id' => $user_id];

        if (!empty($filters['search'])) {
            $query .= " AND t.description LIKE :search";
            $params['search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['category_id'])) {
            $query .= " AND t.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }

        if (!empty($filters['start_date'])) {
            $query .= " AND t.date >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND t.date <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        $this->db->query($query);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->single()['total'];
    }

    public function getTransactionById($id, $user_id)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id");
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $user_id);
        return $this->db->single();
    }

    public function addTransaction($data, $user_id)
    {
        $query = "INSERT INTO {$this->table} 
                  (user_id, category_id, date, description, amount, notes) 
                  VALUES (:user_id, :category_id, :date, :description, :amount, :notes)";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('date', $data['date']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('notes', $data['notes']);

        return $this->db->execute();
    }

    public function updateTransaction($data, $user_id)
    {
        $query = "UPDATE {$this->table} SET 
                  category_id = :category_id, 
                  date = :date, 
                  description = :description, 
                  amount = :amount, 
                  notes = :notes 
                  WHERE id = :id AND user_id = :user_id";
                  
        $this->db->query($query);
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('date', $data['date']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('notes', $data['notes']);
        $this->db->bind('id', $data['id']);
        $this->db->bind('user_id', $user_id);

        return $this->db->execute();
    }

    public function deleteTransaction($id, $user_id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $user_id);

        return $this->db->execute();
    }
}
