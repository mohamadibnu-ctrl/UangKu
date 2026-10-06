<div class="container mt-4">
    <?php Flasher::flash(); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <span class="text-muted">Bulan ini: <strong><?= date('F Y'); ?></strong></span>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 border-primary border-start border-4">
                <div class="card-body">
                    <h6 class="card-title text-muted text-uppercase mb-2">Total Bulan Ini</h6>
                    <h3 class="mb-0 text-primary">Rp <?= number_format($data['total_expense'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 border-success border-start border-4">
                <div class="card-body">
                    <h6 class="card-title text-muted text-uppercase mb-2">Jumlah Transaksi</h6>
                    <h3 class="mb-0 text-success"><?= number_format($data['total_transactions'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100 border-warning border-start border-4">
                <div class="card-body">
                    <h6 class="card-title text-muted text-uppercase mb-2">Kategori Terbesar</h6>
                    <h3 class="mb-0 text-warning">
                        <?php if ($data['highest_category']) : ?>
                            <?= htmlspecialchars($data['highest_category']['name']); ?> 
                            <span class="fs-6 text-muted d-block mt-1">— Rp <?= number_format($data['highest_category']['total'], 0, ',', '.'); ?></span>
                        <?php else : ?>
                            Belum ada
                        <?php endif; ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Grafik Kategori Bulan Ini</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <?php if (empty($data['expense_by_category'])) : ?>
                        <p class="text-muted mb-0">Belum ada data pengeluaran bulan ini.</p>
                    <?php else : ?>
                        <canvas id="expenseChart" style="max-height: 300px;"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">5 Transaksi Terbaru</h5>
                    <a href="<?= BASEURL; ?>/transaction" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['recent_transactions'])) : ?>
                                    <?php foreach ($data['recent_transactions'] as $trx) : ?>
                                    <tr>
                                        <td><?= date('d M', strtotime($trx['date'])); ?></td>
                                        <td><?= htmlspecialchars($trx['description']); ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($trx['category_name']); ?></span></td>
                                        <td class="text-end text-danger fw-bold">Rp <?= number_format($trx['amount'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Belum ada transaksi terbaru.</td>
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if (!empty($data['expense_by_category'])) : ?>
<?php
    $labels = [];
    $amounts = [];
    $colors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', 
        '#FF9F40', '#EA5C6B', '#34495E', '#1ABC9C', '#F1C40F'
    ];
    $bgColors = [];

    $i = 0;
    foreach ($data['expense_by_category'] as $row) {
        $labels[] = $row['name'];
        $amounts[] = $row['total'];
        $bgColors[] = $colors[$i % count($colors)];
        $i++;
    }
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    label: 'Pengeluaran (Rp)',
                    data: <?= json_encode($amounts); ?>,
                    backgroundColor: <?= json_encode($bgColors); ?>,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<?php endif; ?>
