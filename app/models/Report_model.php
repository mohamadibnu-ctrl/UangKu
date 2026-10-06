<?php

class Report_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // ==========================================
    // LAPORAN BULANAN
    // ==========================================

    public function getMonthlyTotalExpense($user_id, $month, $year)
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

    public function getMonthlyTotalTransactions($user_id, $month, $year)
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

    public function getMonthlyExpenseByCategory($user_id, $month, $year)
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

    public function getMonthlyTransactions($user_id, $month, $year)
    {
        $query = "SELECT t.*, c.name as category_name 
                  FROM transactions t
                  JOIN categories c ON t.category_id = c.id
                  WHERE t.user_id = :user_id 
                  AND MONTH(t.date) = :month 
                  AND YEAR(t.date) = :year
                  ORDER BY t.date DESC, t.id DESC";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('month', $month);
        $this->db->bind('year', $year);
        
        return $this->db->resultSet();
    }

    // ==========================================
    // LAPORAN TAHUNAN
    // ==========================================

    public function getYearlyTotalExpense($user_id, $year)
    {
        $query = "SELECT SUM(amount) as total 
                  FROM transactions 
                  WHERE user_id = :user_id 
                  AND YEAR(date) = :year";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('year', $year);
        
        $result = $this->db->single();
        return $result['total'] ? $result['total'] : 0;
    }

    public function getYearlyExpenseByMonth($user_id, $year)
    {
        $query = "SELECT MONTH(date) as month, SUM(amount) as total 
                  FROM transactions 
                  WHERE user_id = :user_id 
                  AND YEAR(date) = :year
                  GROUP BY MONTH(date)
                  ORDER BY month ASC";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('year', $year);
        
        return $this->db->resultSet();
    }

    public function getYearlyExpenseByCategory($user_id, $year)
    {
        $query = "SELECT c.name, SUM(t.amount) as total 
                  FROM transactions t
                  JOIN categories c ON t.category_id = c.id
                  WHERE t.user_id = :user_id 
                  AND YEAR(t.date) = :year
                  GROUP BY c.id, c.name
                  ORDER BY total DESC";
                  
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('year', $year);
        
        return $this->db->resultSet();
    }
}
