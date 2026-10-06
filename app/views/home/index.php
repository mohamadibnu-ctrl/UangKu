<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold text-primary mb-3">Selamat Datang di UangKu</h1>
        <p class="lead text-muted mb-4">Sistem Pencatatan Keuangan Pribadi yang Cerdas, Aman, dan Mudah Digunakan.</p>
        
        <?php if(!isset($_SESSION['user'])) : ?>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="<?= BASEURL; ?>/auth/register" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">Mulai Sekarang</a>
                <a href="<?= BASEURL; ?>/auth/login" class="btn btn-outline-primary btn-lg px-5 rounded-pill shadow-sm bg-white">Masuk</a>
            </div>
        <?php else: ?>
            <div class="mt-4">
                <a href="<?= BASEURL; ?>/dashboard" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">Buka Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-5 pt-5 mb-5">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="card h-100 card-hover p-4 border-0 bg-white">
                <div class="d-flex justify-content-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <h4 class="fw-bold mt-3">Pencatatan Mudah</h4>
                <p class="text-muted">Catat setiap pengeluaran Anda dengan cepat. Kategorikan untuk analisis yang lebih baik.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 card-hover p-4 border-0 bg-white">
                <div class="d-flex justify-content-center">
                    <div class="feature-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-pie-chart"></i>
                    </div>
                </div>
                <h4 class="fw-bold mt-3">Laporan Visual</h4>
                <p class="text-muted">Pahami kebiasaan finansial Anda melalui grafik interaktif dan laporan terperinci bulanan & tahunan.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 card-hover p-4 border-0 bg-white">
                <div class="d-flex justify-content-center">
                    <div class="feature-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-robot"></i>
                    </div>
                </div>
                <h4 class="fw-bold mt-3">AI Assistant</h4>
                <p class="text-muted">Tanyakan berbagai hal tentang riwayat keuangan Anda kepada Asisten Kecerdasan Buatan (AI) pribadi.</p>
            </div>
        </div>
    </div>
</div>
