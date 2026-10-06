<?php

class Category extends Controller {

    public function __construct()
    {
        // Pastikan user sudah login
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        // Pastikan email sudah diverifikasi
        if (empty($_SESSION['user']['email_verified_at'])) {
            Flasher::setFlash('Silakan verifikasi email Anda terlebih dahulu.', 'warning');
            header('Location: ' . BASEURL . '/home/verify_notice');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Kategori Pengeluaran';
        $user_id = $_SESSION['user']['id'];
        
        $data['categories'] = $this->model('Category_model')->getCategoriesByUser($user_id);

        $this->view('templates/header', $data);
        $this->view('category/index', $data);
        $this->view('templates/footer');
    }

    public function create()
    {
        $data['judul'] = 'Tambah Kategori';
        
        $this->view('templates/header', $data);
        $this->view('category/create', $data);
        $this->view('templates/footer');
    }

    public function store()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $user_id = $_SESSION['user']['id'];

            if (empty($name)) {
                Flasher::setFlash('Nama kategori tidak boleh kosong!', 'danger');
                header('Location: ' . BASEURL . '/category/create');
                exit;
            }

            $categoryModel = $this->model('Category_model');

            // Cek duplikasi
            if ($categoryModel->getCategoryByName($name, $user_id)) {
                Flasher::setFlash('Kategori dengan nama tersebut sudah ada!', 'warning');
                header('Location: ' . BASEURL . '/category/create');
                exit;
            }

            if ($categoryModel->addCategory($_POST, $user_id)) {
                Flasher::setFlash('Kategori berhasil ditambahkan!', 'success');
                header('Location: ' . BASEURL . '/category');
                exit;
            } else {
                Flasher::setFlash('Gagal menambahkan kategori!', 'danger');
                header('Location: ' . BASEURL . '/category/create');
                exit;
            }
        }
    }

    public function edit($id)
    {
        $user_id = $_SESSION['user']['id'];
        $data['category'] = $this->model('Category_model')->getCategoryById($id, $user_id);

        if (!$data['category'] || $data['category']['is_default'] == 1) {
            Flasher::setFlash('Kategori tidak ditemukan atau tidak dapat diubah!', 'danger');
            header('Location: ' . BASEURL . '/category');
            exit;
        }

        $data['judul'] = 'Ubah Kategori';
        
        $this->view('templates/header', $data);
        $this->view('category/edit', $data);
        $this->view('templates/footer');
    }

    public function update()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $id = $_POST['id'];
            $user_id = $_SESSION['user']['id'];

            if (empty($name)) {
                Flasher::setFlash('Nama kategori tidak boleh kosong!', 'danger');
                header('Location: ' . BASEURL . '/category/edit/' . $id);
                exit;
            }

            $categoryModel = $this->model('Category_model');

            // Cek duplikasi untuk kategori lain
            $existing = $categoryModel->getCategoryByName($name, $user_id);
            if ($existing && $existing['id'] != $id) {
                Flasher::setFlash('Kategori dengan nama tersebut sudah ada!', 'warning');
                header('Location: ' . BASEURL . '/category/edit/' . $id);
                exit;
            }

            if ($categoryModel->updateCategory($_POST, $user_id)) {
                Flasher::setFlash('Kategori berhasil diubah!', 'success');
                header('Location: ' . BASEURL . '/category');
                exit;
            } else {
                Flasher::setFlash('Gagal mengubah kategori!', 'danger');
                header('Location: ' . BASEURL . '/category/edit/' . $id);
                exit;
            }
        }
    }

    public function delete($id)
    {
        $user_id = $_SESSION['user']['id'];
        $categoryModel = $this->model('Category_model');
        
        $category = $categoryModel->getCategoryById($id, $user_id);

        if (!$category || $category['is_default'] == 1) {
            Flasher::setFlash('Kategori tidak ditemukan atau tidak dapat dihapus!', 'danger');
            header('Location: ' . BASEURL . '/category');
            exit;
        }

        if ($categoryModel->deleteCategory($id, $user_id)) {
            Flasher::setFlash('Kategori berhasil dihapus!', 'success');
        } else {
            Flasher::setFlash('Gagal menghapus! Kategori sedang digunakan dalam transaksi.', 'danger');
        }
        
        header('Location: ' . BASEURL . '/category');
        exit;
    }
}
