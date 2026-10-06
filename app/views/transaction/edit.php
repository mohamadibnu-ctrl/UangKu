<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Ubah Pengeluaran</h4>
                </div>
                <div class="card-body">
                    <?php Flasher::flash(); ?>
                    <form action="<?= BASEURL; ?>/transaction/update" method="post">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generate(); ?>">
                        
                        <input type="hidden" name="id" value="<?= $data['transaction']['id']; ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" value="<?= $data['transaction']['date']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Nominal (Rp)</label>
                                <input type="number" class="form-control" id="amount" name="amount" min="1" step="1" value="<?= $data['transaction']['amount']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="description" name="description" value="<?= htmlspecialchars($data['transaction']['description']); ?>" required autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <?php foreach ($data['categories'] as $cat) : ?>
                                    <option value="<?= $cat['id']; ?>" <?= ($data['transaction']['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?= $cat['name']; ?> <?= $cat['is_default'] ? '(Default)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($data['transaction']['notes']); ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= BASEURL; ?>/transaction" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
