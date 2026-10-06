<?php

class Dashboard extends Controller {

    public function __construct()
    {
        // Pengecekan autentikasi global untuk Dashboard
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        if (empty($_SESSION['user']['email_verified_at'])) {
            Flasher::setFlash('Silakan verifikasi email Anda terlebih dahulu untuk mengakses Dashboard.', 'warning');
            header('Location: ' . BASEURL . '/home/verify_notice');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Dashboard';
        $user_id = $_SESSION['user']['id'];
        $data['user'] = $_SESSION['user'];

        $month = date('m');
        $year = date('Y');

        $dashboardModel = $this->model('Dashboard_model');
        
        $data['total_expense'] = $dashboardModel->getTotalExpenseThisMonth($user_id, $month, $year);
        $data['total_transactions'] = $dashboardModel->getTotalTransactionsThisMonth($user_id, $month, $year);
        $data['highest_category'] = $dashboardModel->getHighestExpenseCategoryThisMonth($user_id, $month, $year);
        $data['recent_transactions'] = $dashboardModel->getRecentTransactions($user_id, 5);
        $data['expense_by_category'] = $dashboardModel->getExpenseByCategoryThisMonth($user_id, $month, $year);

        $this->view('templates/header', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/footer');
    }
}
