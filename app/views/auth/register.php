<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-center mb-4">Daftar Akun Baru</h3>
            <?php Flasher::flash(); ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/auth/registerProcess" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="6">
                        </div>
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-success">Daftar</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <a href="<?= BASEURL; ?>/auth/login" class="text-decoration-none">Sudah punya akun? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
