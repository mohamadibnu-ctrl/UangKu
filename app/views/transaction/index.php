<div class="container mt-5">
    <?php Flasher::flash(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Riwayat Pengeluaran</h2>
                <a href="<?= BASEURL; ?>/transaction/create" class="btn btn-primary">Tambah Pengeluaran</a>
            </div>

            <!-- Filter & Search Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/transaction" method="get" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Cari Keterangan</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($data['filters']['search']); ?>" placeholder="Ketik kata kunci...">
                        </div>
                        <div class="col-md-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($data['categories'] as $cat) : ?>
                                    <option value="<?= $cat['id']; ?>" <?= ($data['filters']['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?= $cat['name']; ?> <?= $cat['is_default'] ? '(Default)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($data['filters']['start_date']); ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($data['filters']['end_date']); ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Table -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Catatan</th>
                                    <th scope="col" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['transactions'])) : ?>
                                    <?php foreach ($data['transactions'] as $trx) : ?>
                                    <tr>
                                        <td class="ps-3"><?= date('d-m-Y', strtotime($trx['date'])); ?></td>
                                        <td><?= htmlspecialchars($trx['description']); ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($trx['category_name']); ?></span></td>
                                        <td class="text-danger fw-bold">Rp <?= number_format($trx['amount'], 0, ',', '.'); ?></td>
                                        <td class="text-muted small"><?= htmlspecialchars($trx['notes']); ?></td>
                                        <td class="text-end">
                                            <a href="<?= BASEURL; ?>/transaction/edit/<?= $trx['id']; ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                            <a href="<?= BASEURL; ?>/transaction/delete/<?= $trx['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini?');"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="bi bi-receipt"></i>
                                                <h5>Belum ada transaksi</h5>
                                                <p>Anda belum mencatat pengeluaran apapun berdasarkan filter ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($data['pagination']['total_pages'] > 1) : ?>
                    <nav aria-label="Page navigation" class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php 
                                $params = $_GET;
                                for ($i = 1; $i <= $data['pagination']['total_pages']; $i++) : 
                                    $params['page'] = $i;
                                    $query_string = http_build_query($params);
                            ?>
                                <li class="page-item <?= ($data['pagination']['current_page'] == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?= BASEURL; ?>/transaction?<?= $query_string; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
