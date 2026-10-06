<div class="container mt-5">
    <?php Flasher::flash(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Daftar Kategori</h2>
                <a href="<?= BASEURL; ?>/category/create" class="btn btn-primary">Tambah Kategori</a>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Kategori</th>
                                    <th scope="col">Jenis</th>
                                    <th scope="col" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($data['categories'] as $category) : ?>
                                <tr>
                                    <th scope="row" class="ps-3"><?= $i++; ?></th>
                                    <td><?= htmlspecialchars($category['name']); ?></td>
                                    <td>
                                        <?php if ($category['is_default'] == 1) : ?>
                                            <span class="badge bg-secondary">Default</span>
                                        <?php else : ?>
                                            <span class="badge bg-success">Kustom</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <?php if ($category['is_default'] == 0) : ?>
                                            <a href="<?= BASEURL; ?>/category/edit/<?= $category['id']; ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                            <a href="<?= BASEURL; ?>/category/delete/<?= $category['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?');"><i class="bi bi-trash"></i></a>
                                        <?php else : ?>
                                            <span class="text-muted small"><em>Tidak dapat diubah</em></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($data['categories'])) : ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-tags"></i>
                                            <h5>Belum ada kategori</h5>
                                            <p>Anda belum memiliki kategori pengeluaran apapun.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
