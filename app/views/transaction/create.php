<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Tambah Pengeluaran</h4>
                </div>
                <div class="card-body">
                    <?php Flasher::flash(); ?>
                    <form action="<?= BASEURL; ?>/transaction/store" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Nominal (Rp)</label>
                                <input type="number" class="form-control" id="amount" name="amount" min="1" step="1" required placeholder="Contoh: 25000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="description" name="description" required placeholder="Contoh: Makan siang" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                <?php foreach ($data['categories'] as $cat) : ?>
                                    <option value="<?= $cat['id']; ?>">
                                        <?= $cat['name']; ?> <?= $cat['is_default'] ? '(Default)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Contoh: Makan siang bersama teman"></textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= BASEURL; ?>/transaction" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
