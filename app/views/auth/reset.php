<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center mb-4">Reset Password</h3>
            <?php Flasher::flash(); ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/auth/resetProcess" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        <input type="hidden" name="token" value="<?= $data['token']; ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="6">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
