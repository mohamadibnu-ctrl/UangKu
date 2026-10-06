<?php

class Transaction extends Controller {

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
        $data['judul'] = 'Riwayat Pengeluaran';
        $user_id = $_SESSION['user']['id'];
        
        $transactionModel = $this->model('Transaction_model');
        $categoryModel = $this->model('Category_model');

        // Setup Filters
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
            'category_id' => isset($_GET['category_id']) ? $_GET['category_id'] : '',
            'start_date' => isset($_GET['start_date']) ? $_GET['start_date'] : '',
            'end_date' => isset($_GET['end_date']) ? $_GET['end_date'] : ''
        ];

        // Setup Pagination
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $total_transactions = $transactionModel->countTransactionsByUser($user_id, $filters);
        $total_pages = ceil($total_transactions / $limit);

        $data['transactions'] = $transactionModel->getTransactionsByUser($user_id, $filters, $limit, $offset);
        $data['categories'] = $categoryModel->getCategoriesByUser($user_id);
        
        $data['filters'] = $filters;
        $data['pagination'] = [
            'current_page' => $page,
            'total_pages' => $total_pages > 0 ? $total_pages : 1
        ];

        $this->view('templates/header', $data);
        $this->view('transaction/index', $data);
        $this->view('templates/footer');
    }

    public function create()
    {
        $data['judul'] = 'Tambah Pengeluaran';
        $user_id = $_SESSION['user']['id'];
        
        $data['categories'] = $this->model('Category_model')->getCategoriesByUser($user_id);

        $this->view('templates/header', $data);
        $this->view('transaction/create', $data);
        $this->view('templates/footer');
    }

    public function store()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'];
            
            $date = $_POST['date'];
            $description = trim($_POST['description']);
            $category_id = $_POST['category_id'];
            $amount = $_POST['amount'];
            $notes = trim($_POST['notes']);

            // Validasi Server Side
            if (empty($date) || empty($description) || empty($category_id) || empty($amount)) {
                Flasher::setFlash('Tanggal, Keterangan, Kategori, dan Nominal wajib diisi!', 'danger');
                header('Location: ' . BASEURL . '/transaction/create');
                exit;
            }

            if (!is_numeric($amount) || $amount <= 0) {
                Flasher::setFlash('Nominal harus berupa angka lebih besar dari 0!', 'danger');
                header('Location: ' . BASEURL . '/transaction/create');
                exit;
            }

            $categoryModel = $this->model('Category_model');
            $category = $categoryModel->getCategoryById($category_id, $user_id);
            if (!$category) {
                Flasher::setFlash('Kategori tidak valid!', 'danger');
                header('Location: ' . BASEURL . '/transaction/create');
                exit;
            }

            $data = [
                'date' => $date,
                'description' => $description,
                'category_id' => $category_id,
                'amount' => $amount,
                'notes' => $notes
            ];

            if ($this->model('Transaction_model')->addTransaction($data, $user_id)) {
                Flasher::setFlash('Pengeluaran berhasil ditambahkan!', 'success');
                header('Location: ' . BASEURL . '/transaction');
                exit;
            } else {
                Flasher::setFlash('Gagal menambahkan pengeluaran!', 'danger');
                header('Location: ' . BASEURL . '/transaction/create');
                exit;
            }
        }
    }

    public function edit($id)
    {
        $user_id = $_SESSION['user']['id'];
        
        $data['transaction'] = $this->model('Transaction_model')->getTransactionById($id, $user_id);
        
        if (!$data['transaction']) {
            Flasher::setFlash('Data pengeluaran tidak ditemukan!', 'danger');
            header('Location: ' . BASEURL . '/transaction');
            exit;
        }

        $data['judul'] = 'Ubah Pengeluaran';
        $data['categories'] = $this->model('Category_model')->getCategoriesByUser($user_id);

        $this->view('templates/header', $data);
        $this->view('transaction/edit', $data);
        $this->view('templates/footer');
    }

    public function update()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'];
            
            $id = $_POST['id'];
            $date = $_POST['date'];
            $description = trim($_POST['description']);
            $category_id = $_POST['category_id'];
            $amount = $_POST['amount'];
            $notes = trim($_POST['notes']);

            if (empty($date) || empty($description) || empty($category_id) || empty($amount)) {
                Flasher::setFlash('Tanggal, Keterangan, Kategori, dan Nominal wajib diisi!', 'danger');
                header('Location: ' . BASEURL . '/transaction/edit/' . $id);
                exit;
            }

            if (!is_numeric($amount) || $amount <= 0) {
                Flasher::setFlash('Nominal harus berupa angka lebih besar dari 0!', 'danger');
                header('Location: ' . BASEURL . '/transaction/edit/' . $id);
                exit;
            }

            $categoryModel = $this->model('Category_model');
            $category = $categoryModel->getCategoryById($category_id, $user_id);
            if (!$category) {
                Flasher::setFlash('Kategori tidak valid!', 'danger');
                header('Location: ' . BASEURL . '/transaction/edit/' . $id);
                exit;
            }

            $data = [
                'id' => $id,
                'date' => $date,
                'description' => $description,
                'category_id' => $category_id,
                'amount' => $amount,
                'notes' => $notes
            ];

            if ($this->model('Transaction_model')->updateTransaction($data, $user_id)) {
                Flasher::setFlash('Pengeluaran berhasil diubah!', 'success');
                header('Location: ' . BASEURL . '/transaction');
                exit;
            } else {
                Flasher::setFlash('Gagal mengubah pengeluaran!', 'danger');
                header('Location: ' . BASEURL . '/transaction/edit/' . $id);
                exit;
            }
        }
    }

    public function delete($id)
    {
        $user_id = $_SESSION['user']['id'];
        $transactionModel = $this->model('Transaction_model');
        
        $transaction = $transactionModel->getTransactionById($id, $user_id);

        if (!$transaction) {
            Flasher::setFlash('Data pengeluaran tidak ditemukan!', 'danger');
            header('Location: ' . BASEURL . '/transaction');
            exit;
        }

        if ($transactionModel->deleteTransaction($id, $user_id)) {
            Flasher::setFlash('Pengeluaran berhasil dihapus!', 'success');
        } else {
            Flasher::setFlash('Gagal menghapus pengeluaran!', 'danger');
        }
        
        header('Location: ' . BASEURL . '/transaction');
        exit;
    }
}
