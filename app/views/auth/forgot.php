<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center mb-4">Lupa Password</h3>
            <?php Flasher::flash(); ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted text-center">Masukkan email Anda untuk menerima link reset password.</p>
                    <form action="<?= BASEURL; ?>/auth/forgotProcess" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <a href="<?= BASEURL; ?>/auth/login" class="text-decoration-none">Kembali ke Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
