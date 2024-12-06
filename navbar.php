<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm " style="background-color: rgba(255, 255, 255, 0.95);">
  <div class="container">
    <!-- Logo Section -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="img/Screenshotremovebg-preview.png" alt="Logo" style="max-height: 50px; margin-right: 5px;" />
      <span class="font-weight-bold" style="font-size: 1.5rem; color: #005b9a;">Smart Helmet</span>
    </a>

    <!-- Navbar Toggle Button (for mobile) -->
    <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <!-- Dropdown Button -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle btn btn-outline-primary" href="#" id="userMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 5px 15px;">
            <i class="fas fa-user"></i> <!-- أيقونة المستخدم -->
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userMenu">
            <a class="dropdown-item" href="super_home.php">Reset Password</a>
            <a class="dropdown-item text-danger" href="logout.php">Log Out</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
