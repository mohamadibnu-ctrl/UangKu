<?php

class Report extends Controller {

    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        if (empty($_SESSION['user']['email_verified_at'])) {
            Flasher::setFlash('Silakan verifikasi email Anda terlebih dahulu.', 'warning');
            header('Location: ' . BASEURL . '/home/verify_notice');
            exit;
        }
    }

    public function index()
    {
        header('Location: ' . BASEURL . '/report/monthly');
        exit;
    }

    public function monthly()
    {
        $data['judul'] = 'Laporan Bulanan';
        $user_id = $_SESSION['user']['id'];

        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        $data['filter_month'] = $month;
        $data['filter_year'] = $year;

        $reportModel = $this->model('Report_model');
        
        $data['total_expense'] = $reportModel->getMonthlyTotalExpense($user_id, $month, $year);
        $data['total_transactions'] = $reportModel->getMonthlyTotalTransactions($user_id, $month, $year);
        $data['expense_by_category'] = $reportModel->getMonthlyExpenseByCategory($user_id, $month, $year);
        $data['transactions'] = $reportModel->getMonthlyTransactions($user_id, $month, $year);

        $this->view('templates/header', $data);
        $this->view('report/monthly', $data);
        $this->view('templates/footer');
    }

    public function yearly()
    {
        $data['judul'] = 'Laporan Tahunan';
        $user_id = $_SESSION['user']['id'];

        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        $data['filter_year'] = $year;

        $reportModel = $this->model('Report_model');

        $data['total_expense'] = $reportModel->getYearlyTotalExpense($user_id, $year);
        $data['expense_by_category'] = $reportModel->getYearlyExpenseByCategory($user_id, $year);
        
        // Memformat data bulanan agar terisi 0 untuk bulan yang kosong
        $rawMonthlyData = $reportModel->getYearlyExpenseByMonth($user_id, $year);
        $monthlyFormatted = [];
        
        // Inisialisasi 12 bulan dengan 0
        for ($i = 1; $i <= 12; $i++) {
            $monthlyFormatted[$i] = 0;
        }

        // Isi dengan data asli
        foreach ($rawMonthlyData as $row) {
            $monthlyFormatted[(int)$row['month']] = (float)$row['total'];
        }
        
        $data['expense_by_month'] = $monthlyFormatted;

        $this->view('templates/header', $data);
        $this->view('report/yearly', $data);
        $this->view('templates/footer');
    }
}
