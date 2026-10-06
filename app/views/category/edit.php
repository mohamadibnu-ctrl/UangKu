<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Ubah Kategori</h4>
                </div>
                <div class="card-body">
                    <?php Flasher::flash(); ?>
                    <form action="<?= BASEURL; ?>/category/update" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        <input type="hidden" name="id" value="<?= $data['category']['id']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $data['category']['name']; ?>" required autocomplete="off">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASEURL; ?>/category" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
