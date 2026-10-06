<?php

class Dashboard_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getTotalExpenseThisMonth($user_id, $month, $year)
    {
        $query = "SELECT SUM(amount) as total 
                  FROM transactions 
                  WHERE user_id = :user_id 
                  AND MONTH(date) = :month 
                  AND YEAR(date) = :year";
        
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('month', $month);
        $this->db->bind('year', $year);
        
        $result = $this->db->single();
        return $result['total'] ? $result['total'] : 0;
    }

    public function getTotalTransactionsThisMonth($user_id, $month, $year)
    {
        $query = "SELECT COUNT(*) as total 
                  FROM transactions 
                  WHERE user_id = :user_id 
                  AND MONTH(date) = :month 
                  AND YEAR(date) = :year";
        
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('month', $month);
        $this->db->bind('year', $year);
        
        return $this->db->single()['total'];
    }

    public function getHighestExpenseCategoryThisMonth($user_id, $month, $year)
    {
        $query = "SELECT c.name, SUM(t.amount) as total 
                  FROM transactions t
                  JOIN categories c ON t.category_id = c.id
                  WHERE t.user_id = :user_id 
                  AND MONTH(t.date) = :month 
                  AND YEAR(t.date) = :year
                  GROUP BY c.id, c.name
                  ORDER BY total DESC
                  LIMIT 1";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('month', $month);
        $this->db->bind('year', $year);
        
        return $this->db->single();
    }

    public function getRecentTransactions($user_id, $limit = 5)
    {
        $query = "SELECT t.*, c.name as category_name 
                  FROM transactions t
                  JOIN categories c ON t.category_id = c.id
                  WHERE t.user_id = :user_id 
                  ORDER BY t.date DESC, t.id DESC 
                  LIMIT :limit";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    public function getExpenseByCategoryThisMonth($user_id, $month, $year)
    {
        $query = "SELECT c.name, SUM(t.amount) as total 
                  FROM transactions t
                  JOIN categories c ON t.category_id = c.id
                  WHERE t.user_id = :user_id 
                  AND MONTH(t.date) = :month 
                  AND YEAR(t.date) = :year
                  GROUP BY c.id, c.name
                  ORDER BY total DESC";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('month', $month);
        $this->db->bind('year', $year);
        
        return $this->db->resultSet();
    }
}
