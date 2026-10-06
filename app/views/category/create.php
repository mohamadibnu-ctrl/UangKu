<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Tambah Kategori Baru</h4>
                </div>
                <div class="card-body">
                    <?php Flasher::flash(); ?>
                    <form action="<?= BASEURL; ?>/category/store" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="name" name="name" required autocomplete="off">
                            <div class="form-text">Contoh: Belanja Bulanan, Uang Jajan, dll.</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASEURL; ?>/category" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
