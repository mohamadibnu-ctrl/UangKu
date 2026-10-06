<?php

class Auth extends Controller {

    public function index()
    {
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }

    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Login';
        $this->view('templates/header', $data);
        $this->view('auth/login', $data);
        $this->view('templates/footer');
    }

    public function loginProcess()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $userModel = $this->model('User_model');
            $user = $userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Mencegah Session Fixation
                session_regenerate_id(true);

                // Set session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'),
                    'email_verified_at' => $user['email_verified_at']
                ];
                
                header('Location: ' . BASEURL . '/dashboard');
                exit;
            } else {
                Flasher::setFlash('Email atau Password salah!', 'danger');
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }
        }
    }

    public function register()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Register';
        $this->view('templates/header', $data);
        $this->view('auth/register', $data);
        $this->view('templates/footer');
    }

    public function registerProcess()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            // Validation
            if (empty($name) || empty($email) || empty($password)) {
                Flasher::setFlash('Semua field wajib diisi!', 'warning');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Flasher::setFlash('Format email tidak valid!', 'warning');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            if ($password !== $password_confirm) {
                Flasher::setFlash('Konfirmasi password tidak cocok!', 'warning');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            $userModel = $this->model('User_model');
            
            // Check if email exists
            if ($userModel->getUserByEmail($email)) {
                Flasher::setFlash('Email sudah terdaftar!', 'danger');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }

            // Generate Verification Token
            $token = bin2hex(random_bytes(32));
            $token_expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $data = [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ];

            if ($userModel->register($data, $token, $token_expiry)) {
                // Send Email
                $emailService = new Email();
                $verifyLink = BASEURL . '/auth/verify/' . $token;
                $subject = "Verifikasi Email Anda - UangKu";
                $body = "Halo $name,<br><br>Klik link berikut untuk memverifikasi email Anda:<br><a href='$verifyLink'>$verifyLink</a><br><br>Link ini berlaku selama 24 jam.";
                
                $emailService->send($email, $subject, $body);

                Flasher::setFlash('Registrasi berhasil! Silakan cek email Anda untuk verifikasi.', 'success');
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            } else {
                Flasher::setFlash('Gagal melakukan registrasi!', 'danger');
                header('Location: ' . BASEURL . '/auth/register');
                exit;
            }
        }
    }

    public function verify($token = '')
    {
        if (empty($token)) {
            Flasher::setFlash('Token tidak valid!', 'danger');
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $userModel = $this->model('User_model');
        $user = $userModel->getUserByVerificationToken($token);

        if ($user) {
            $now = date('Y-m-d H:i:s');
            if ($user['verification_token_expires_at'] < $now) {
                Flasher::setFlash('Token verifikasi sudah kedaluwarsa!', 'danger');
            } else {
                $userModel->verifyEmail($user['id']);
                Flasher::setFlash('Email berhasil diverifikasi! Silakan login.', 'success');
                
                // Update session if user is logged in
                if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $user['id']) {
                    $_SESSION['user']['email_verified_at'] = date('Y-m-d H:i:s');
                }
            }
        } else {
            Flasher::setFlash('Token tidak ditemukan atau sudah diverifikasi!', 'danger');
        }

        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }

    public function forgot()
    {
        $data['judul'] = 'Lupa Password';
        $this->view('templates/header', $data);
        $this->view('auth/forgot', $data);
        $this->view('templates/footer');
    }

    public function forgotProcess()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            
            $userModel = $this->model('User_model');
            $user = $userModel->getUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                if ($userModel->updateResetToken($email, $token, $expiry)) {
                    $emailService = new Email();
                    $resetLink = BASEURL . '/auth/reset/' . $token;
                    $subject = "Reset Password Anda - UangKu";
                    $body = "Halo,<br><br>Klik link berikut untuk mereset password Anda:<br><a href='$resetLink'>$resetLink</a><br><br>Link ini berlaku selama 1 jam.";
                    
                    $emailService->send($email, $subject, $body);
                }
            }
            
            // Selalu tampilkan pesan sukses untuk alasan keamanan (mencegah enumerasi email)
            Flasher::setFlash('Jika email terdaftar, instruksi reset password telah dikirim ke email tersebut.', 'info');
            header('Location: ' . BASEURL . '/auth/forgot');
            exit;
        }
    }

    public function reset($token = '')
    {
        if (empty($token)) {
            Flasher::setFlash('Token tidak valid!', 'danger');
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $userModel = $this->model('User_model');
        $user = $userModel->getUserByResetToken($token);

        if (!$user || $user['reset_token_expires_at'] < date('Y-m-d H:i:s')) {
            Flasher::setFlash('Token reset password tidak valid atau kedaluwarsa!', 'danger');
            header('Location: ' . BASEURL . '/auth/forgot');
            exit;
        }

        $data['judul'] = 'Reset Password';
        $data['token'] = $token;
        $this->view('templates/header', $data);
        $this->view('auth/reset', $data);
        $this->view('templates/footer');
    }

    public function resetProcess()
    {
        CSRF::check();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            if ($password !== $password_confirm) {
                Flasher::setFlash('Konfirmasi password tidak cocok!', 'warning');
                header('Location: ' . BASEURL . '/auth/reset/' . $token);
                exit;
            }

            $userModel = $this->model('User_model');
            $user = $userModel->getUserByResetToken($token);

            if ($user && $user['reset_token_expires_at'] >= date('Y-m-d H:i:s')) {
                $userModel->resetPassword($user['id'], $password);
                Flasher::setFlash('Password berhasil diubah! Silakan login.', 'success');
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            } else {
                Flasher::setFlash('Token tidak valid atau kedaluwarsa!', 'danger');
                header('Location: ' . BASEURL . '/auth/forgot');
                exit;
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }
}
