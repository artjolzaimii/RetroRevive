<?php session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="http://localhost/RetroRevive/index.php">
      <img src="/RetroRevive/Images/logoR.png" alt="RetroRevive Logo" width="40" height="40" class="me-2" />
      <span class="fw-bold fs-5">RetroRevive</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">

        <li class="nav-item"><a class="nav-link" href="/RetroRevive/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/RetroRevive/buysection/buy.php">Buy</a></li>
        <li class="nav-item"><a class="nav-link" href="/RetroRevive/showroom/book.php">Book</a></li>
        <li class="nav-item"><a class="nav-link" href="/RetroRevive/about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="/RetroRevive/contact.php">Contact</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($_SESSION['username']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="/RetroRevive/profile.php">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="/RetroRevive/logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/RetroRevive/login.php">Login</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
