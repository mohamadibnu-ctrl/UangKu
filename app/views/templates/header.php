<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> | UangKu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= BASEURL; ?>"><strong>UangKu</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>">Home</a>
        </li>
        <?php if(isset($_SESSION['user'])) : ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/transaction">Transaksi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= BASEURL; ?>/category">Kategori</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarReport" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Laporan
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarReport">
            <li><a class="dropdown-item" href="<?= BASEURL; ?>/report/monthly">Laporan Bulanan</a></li>
            <li><a class="dropdown-item" href="<?= BASEURL; ?>/report/yearly">Laporan Tahunan</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link text-warning fw-bold" href="<?= BASEURL; ?>/aiassistant">
            ✨ Tanya AI
          </a>
        </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user'])) : ?>
          <li class="nav-item">
            <span class="nav-link text-light">Halo, <?= explode(' ', trim($_SESSION['user']['name']))[0]; ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-danger btn-sm text-white ms-2" href="<?= BASEURL; ?>/auth/logout">Logout</a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASEURL; ?>/auth/login">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASEURL; ?>/auth/register">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
