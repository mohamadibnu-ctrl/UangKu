<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Laporan Bulanan</h2>
        
        <!-- Filter Form -->
        <form action="<?= BASEURL; ?>/report/monthly" method="get" class="d-flex gap-2">
            <select name="month" class="form-select">
                <?php
                $months = ['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni',
                           '7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                foreach ($months as $num => $name) {
                    $selected = ($data['filter_month'] == $num) ? 'selected' : '';
                    echo "<option value='$num' $selected>$name</option>";
                }
                ?>
            </select>
            <select name="year" class="form-select">
                <?php
                $current_year = date('Y');
                for ($y = $current_year; $y >= $current_year - 5; $y--) {
                    $selected = ($data['filter_year'] == $y) ? 'selected' : '';
                    echo "<option value='$y' $selected>$y</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-primary border-start border-4">
                <div class="card-body">
                    <h6 class="card-title text-muted text-uppercase mb-2">Total Pengeluaran</h6>
                    <h3 class="mb-0 text-primary">Rp <?= number_format($data['total_expense'], 0, ',', '.'); ?></h3>
                    <p class="text-muted mt-2 mb-0">Dari <?= $data['total_transactions']; ?> transaksi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Rincian per Kategori</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($data['expense_by_category'])) : ?>
                            <?php foreach ($data['expense_by_category'] as $cat) : ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <span><?= htmlspecialchars($cat['name']); ?></span>
                                    <span class="fw-bold">Rp <?= number_format($cat['total'], 0, ',', '.'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="list-group-item text-center text-muted p-4">Belum ada pengeluaran di bulan ini.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Grafik Kategori</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <?php if (!empty($data['expense_by_category'])) : ?>
                        <canvas id="monthlyChart" style="max-height: 300px;"></canvas>
                    <?php else : ?>
                        <p class="text-muted mb-0">Grafik tidak tersedia.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction List -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Transaksi</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Keterangan</th>
                            <th>Kategori</th>
                            <th class="text-end pe-3">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['transactions'])) : ?>
                            <?php foreach ($data['transactions'] as $trx) : ?>
                            <tr>
                                <td class="ps-3"><?= date('d-m-Y', strtotime($trx['date'])); ?></td>
                                <td><?= htmlspecialchars($trx['description']); ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($trx['category_name']); ?></span></td>
                                <td class="text-end text-danger fw-bold pe-3">Rp <?= number_format($trx['amount'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada data transaksi di periode ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if (!empty($data['expense_by_category'])) : ?>
<?php
    $labels = [];
    $amounts = [];
    $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#EA5C6B', '#34495E', '#1ABC9C', '#F1C40F'];
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
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    data: <?= json_encode($amounts); ?>,
                    backgroundColor: <?= json_encode($bgColors); ?>
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
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
