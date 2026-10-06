<?php

class AiAssistant extends Controller {

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
        $data['judul'] = 'AI Assistant UangKu';
        $data['user'] = $_SESSION['user'];

        $this->view('templates/header', $data);
        $this->view('ai/index', $data);
        $this->view('templates/footer');
    }

    public function ask()
    {
        // Hanya menerima request POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['csrf_token']) || !CSRF::verify($input['csrf_token'])) {
            echo json_encode(['status' => 'error', 'message' => 'CSRF token validation failed.']);
            exit;
        }

        $question = trim($input['question'] ?? '');

        if (empty($question)) {
            echo json_encode(['status' => 'error', 'message' => 'Pertanyaan tidak boleh kosong.']);
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        
        // Ambil rangkuman data keuangan user secara dinamis
        $financialData = $this->getFinancialSummary($user_id);

        $aiService = new AIService();
        $answer = $aiService->askAI($question, $financialData);

        echo json_encode(['status' => 'success', 'answer' => $answer]);
        exit;
    }

    /**
     * Private function untuk merangkum data keuangan user
     * sebagai konteks yang akan dikirim ke AI API.
     */
    private function getFinancialSummary($user_id)
    {
        $dashboardModel = $this->model('Dashboard_model');
        $reportModel = $this->model('Report_model');

        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');
        
        // Kalkulasi bulan lalu
        $lastMonth = $currentMonth - 1;
        $lastMonthYear = $currentYear;
        if ($lastMonth == 0) {
            $lastMonth = 12;
            $lastMonthYear--;
        }

        // Data bulan ini
        $totalExpenseCurrent = $dashboardModel->getTotalExpenseThisMonth($user_id, $currentMonth, $currentYear);
        $txCountCurrent = $dashboardModel->getTotalTransactionsThisMonth($user_id, $currentMonth, $currentYear);
        $catsCurrent = $dashboardModel->getExpenseByCategoryThisMonth($user_id, $currentMonth, $currentYear);
        
        // Data bulan lalu
        $totalExpenseLast = $dashboardModel->getTotalExpenseThisMonth($user_id, $lastMonth, $lastMonthYear);
        
        // Total keseluruhan & rata-rata setahun terakhir
        $yearlyTotal = $reportModel->getYearlyTotalExpense($user_id, $currentYear);
        
        return [
            'user_name' => $_SESSION['user']['name'],
            'current_month' => [
                'month' => $currentMonth,
                'year' => $currentYear,
                'total_expense' => $totalExpenseCurrent,
                'transaction_count' => $txCountCurrent,
                'categories' => $catsCurrent
            ],
            'last_month' => [
                'month' => $lastMonth,
                'year' => $lastMonthYear,
                'total_expense' => $totalExpenseLast
            ],
            'current_year' => [
                'year' => $currentYear,
                'total_expense' => $yearlyTotal,
                'monthly_average' => $currentMonth > 0 ? ($yearlyTotal / $currentMonth) : 0
            ]
        ];
    }
}
