<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Laporan Tahunan</h2>
        
        <!-- Filter Form -->
        <form action="<?= BASEURL; ?>/report/yearly" method="get" class="d-flex gap-2">
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
        <div class="col-md-12">
            <div class="card shadow-sm border-success border-start border-4">
                <div class="card-body">
                    <h6 class="card-title text-muted text-uppercase mb-2">Total Pengeluaran Tahun <?= $data['filter_year']; ?></h6>
                    <h2 class="mb-0 text-success">Rp <?= number_format($data['total_expense'], 0, ',', '.'); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart: Bar Chart for Months -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Grafik Pengeluaran per Bulan</h5>
        </div>
        <div class="card-body">
            <canvas id="yearlyBarChart" style="height: 300px; width: 100%;"></canvas>
        </div>
    </div>

    <div class="row mb-5">
        <!-- Monthly Breakdown Table -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Rincian per Bulan</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php 
                        $monthNames = ['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni',
                                       '7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                        foreach ($data['expense_by_month'] as $monthNum => $total) : 
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span><?= $monthNames[$monthNum]; ?></span>
                                <span class="fw-bold <?= $total > 0 ? 'text-danger' : 'text-muted'; ?>">
                                    Rp <?= number_format($total, 0, ',', '.'); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Rincian per Kategori</h5>
                </div>
                <div class="card-body">
                    <canvas id="yearlyPieChart" style="max-height: 250px; margin-bottom: 20px;"></canvas>
                    
                    <ul class="list-group list-group-flush mt-3 border-top">
                        <?php if (!empty($data['expense_by_category'])) : ?>
                            <?php foreach ($data['expense_by_category'] as $cat) : ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><?= htmlspecialchars($cat['name']); ?></span>
                                    <span class="fw-bold">Rp <?= number_format($cat['total'], 0, ',', '.'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="list-group-item text-center text-muted border-0">Tidak ada pengeluaran tahun ini.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Data for Bar Chart (Months)
        const barCtx = document.getElementById('yearlyBarChart').getContext('2d');
        const monthNames = <?= json_encode(array_values($monthNames)); ?>;
        const monthTotals = <?= json_encode(array_values($data['expense_by_month'])); ?>;

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Pengeluaran (Rp)',
                    data: monthTotals,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                }
            }
        });

        // Data for Pie Chart (Categories)
        <?php if (!empty($data['expense_by_category'])) : ?>
            <?php
                $catLabels = [];
                $catAmounts = [];
                $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#EA5C6B', '#34495E', '#1ABC9C', '#F1C40F'];
                $bgColors = [];

                $i = 0;
                foreach ($data['expense_by_category'] as $row) {
                    $catLabels[] = $row['name'];
                    $catAmounts[] = $row['total'];
                    $bgColors[] = $colors[$i % count($colors)];
                    $i++;
                }
            ?>
            const pieCtx = document.getElementById('yearlyPieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($catLabels); ?>,
                    datasets: [{
                        data: <?= json_encode($catAmounts); ?>,
                        backgroundColor: <?= json_encode($bgColors); ?>
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' },
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
        <?php endif; ?>
    });
</script>
