<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center mb-4">Login ke UangKu</h3>
            <?php Flasher::flash(); ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/auth/loginProcess" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <a href="<?= BASEURL; ?>/auth/forgot" class="text-decoration-none">Lupa Password?</a> |
                        <a href="<?= BASEURL; ?>/auth/register" class="text-decoration-none">Belum punya akun? Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
