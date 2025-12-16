<?php 
require 'GoogleAPI/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GABAi – Explore & Book</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --brand-orange: #FF823D;
      --brand-purple: #5A3CFF;
      --brand-cyan:   #00D8FF;
      --brand-dark:   #1E1E1E;
      --brand-light:  #F9F9F9;
      --brand-brown:  #8b6844;
      --brand-lime:   #cdee73;
      --glass: rgba(255,255,255,0.65);
      --muted: #6b6b6b;
      --radius-lg: 18px;
    }

    body {
      font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;
      color: var(--brand-dark);
      background-color: var(--brand-light);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    .navbar-nav .nav-link {
      color: var(--brand-brown);
    }

    .navbar {
      background-color: white;
    }

    .navbar .navbar-brand {
      color: var(--brand-brown);
      font-weight: 600;
    }


    /* Navbar links */
.navbar .nav-link {
  color: var(--brand-brown);
  transition: color 0.3s ease;
}

/* Hover color */
.navbar .nav-link:hover {
  color: #ddb33d; /* change to any color you want */
}
    /* Sign up button */
.btn-signup {
  background-color: transparent;
  color: #8b6844;
  border-radius: 50px;
  border: 1px solid #8b6844;
  transition: all 0.3s ease;
}

/* Hover effect */
.text-hover {
  background: none;
  border: none;
  padding: 0;
  color: var(--brand-brown);        /* normal text color */
  cursor: pointer;
  font: inherit;        /* match surrounding text */
}

.text-hover:hover {
  color: #ddb33d;       /* hover text color */

}


    /* ---------- HERO ---------- */
    .hero {
      position: relative;
      height: 65vh;
      min-height: 380px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      overflow: visible;
      border-bottom-left-radius: 28px;
      border-bottom-right-radius: 28px;
      margin-top: 56px; /* account for fixed navbar */
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.30);         /* DARK overlay effect */
      z-index: 2;
      pointer-events: none;
      mix-blend-mode: multiply;
    }

    /* Glass search card centered in hero */
    .hero-card {
      position: relative;
      z-index: 3;
      width: min(600px, 94%);
      background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(255,255,255,0.76));
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 10px 30px rgba(15,30,50,0.12);
      display: flex;
      gap: 18px;
      align-items: center;
      backdrop-filter: blur(6px) saturate(120%);
    }

    .hero-card .left {
      flex: 1;
      min-width: 220px;
    }
    .hero-card h2 {
      margin: 0 0 6px 0;
      font-size: 20px;
      color: var(--brand-dark);
      font-weight: 700;
    }
    .hero-card p { margin: 0; color: var(--muted); font-size: 13px; }

    .hero-card .search-controls {
      display:flex;
      gap:10px;
      align-items:center;
      width: 100%;
    }
    .hero-card .form-control {
      border-radius: 12px;
      padding: 14px 16px;
      border: 1px solid rgba(30,30,30,0.06);
      box-shadow: none;
    }

    /* changed: make primary button solid brown (no gradient) */
    .hero-card .btn-primary {
      border-radius: 12px;
      padding: 10px 18px;
      background: var(--brand-brown) !important;
      border: none !important;
      color: #fff !important;
      font-weight: 600;
      box-shadow: none;
    }

    /* changed: default/neutral chip style (no hero bg glass) */
    .quick-filter {
      display:flex;
      gap:8px;
      margin-top: 12px;
      flex-wrap:wrap;
    }
    .chip {
      background: #ffffff;
      color: var(--brand-dark);
      border: 1px solid rgba(14,20,40,0.06);
      padding: 8px 12px;
      border-radius: 999px;
      font-size: 13px;
      cursor: pointer;
      transition: transform .12s ease, box-shadow .12s ease;
    }
    .chip:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(15,30,50,0.06); }

    /* ---------- FEATURES / WHY ---------- */
    .features {
      max-width: 1200px;
      margin: 40px auto;
      padding: 8px 18px 0;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 18px;
    }

    .feature {
      background: white;
      border-radius: 12px;
      padding: 18px;
      box-shadow: 0 8px 24px rgba(15,30,50,0.06);
      display:flex;
      gap: 12px;
      align-items:center;
      transition: transform .12s ease, box-shadow .12s ease;
    }
    .feature:hover { transform: translateY(-6px); box-shadow: 0 18px 36px rgba(15,30,50,0.10); }
    .feature svg { width: 42px; height: 42px; flex-shrink:0; }
    .feature h4 { margin:0; font-size:16px; }
    .feature p { margin:2px 0 0; color:var(--muted); font-size:13px; }

    /* ---------- GALLERY / HOTEL CARDS ---------- */
    #hotel-gallery {
      max-width: 1200px;
      margin: 18px auto 48px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      padding: 0 18px;
    }

    .hotel-card {
      background: #fff;
      border-radius: 14px;
      padding: 12px;
      box-shadow: 0 8px 28px rgba(15,30,50,0.06);
      text-align: left;
      transition: transform .18s cubic-bezier(.2,.9,.2,1), box-shadow .18s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
      overflow: hidden;
      border: 1px solid rgba(14,20,40,0.03);
    }
    .hotel-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 24px 56px rgba(15,30,50,0.12);
    }

    .hotel-card .thumb {
      width: 100%;
      height: 170px;
      border-radius: 10px;
      overflow: hidden;
      background: linear-gradient(180deg,#eee,#ddd);
    }
    .hotel-card .thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display:block;
    }

    .hotel-card .body {
      padding: 12px 2px 6px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .hotel-card h3 { margin: 6px 0 6px; font-size: 16px; color: var(--brand-dark); }
    .hotel-card p { margin:0; color:var(--muted); font-size:13px; line-height:1.35; }

    .hotel-card .meta { display:flex; justify-content:space-between; align-items:center; margin-top:12px; }
    .price { font-weight:700; color: var(--brand-orange); }
    .btn-book { background: var(--brand-brown); color:white; border:none; padding:8px 12px; border-radius:10px; font-size:13px; }


      /* Modal custom layout */
    .booking-modal .modal-body { padding-top: 0.5rem; }
    .booking-modal .summary-row { display:flex; justify-content:space-between; gap:12px; align-items:center; padding:8px 0; }
    .booking-modal .summary-label { color:#6b6b6b; font-size:0.95rem; }
    .booking-modal .summary-value { font-weight:600; }
    .booking-modal .divider { border-top:1px solid #e9ecef; margin:10px 0; }
    .booking-modal .total-row { display:flex; justify-content:space-between; align-items:center; margin-top:8px; }
    .booking-modal .total-amount { font-size:1.25rem; font-weight:800; }
    .booking-modal .note { font-size:0.85rem; color:#6b6b6b; margin-top:6px; }
    .booking-modal .btn-cancel { background:#6c757d; color:#fff; border:none; }
    .booking-modal .btn-confirm { background:#28a745; color:#fff; border:none; }
    .booking-modal .guests-input { width:80px; }

      /* Ensure meta row layout and spacing */
  .hotel-card .meta {
    display: flex;                  /* already in your CSS; repeated to be sure */
    align-items: center;
    gap: 12px;
    margin-top: 12px;
  }

  /* Force the price on the left and button to the right */
  .hotel-card .price {
    font-weight: 700;
    color: #ff6f00; /* fallback orange */
    align-self: flex-start;
  }

  /* Visible, accessible book button */
  .btn-book {
    margin-left: auto;              /* push to the right side */
    background: #1f6feb;            /* visible blue fallback */
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 13px;
    cursor: pointer;
    box-shadow: 0 6px 14px rgba(31,111,235,0.18);
    transition: transform .12s ease, box-shadow .12s ease;
  }

  /* Hover/active affordances */
  .btn-book:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(31,111,235,0.22); }
  .btn-book:active { transform: translateY(0); }

  /* Disabled state if no booking available */
  .btn-book--disabled,
  .btn-book[disabled] {
    opacity: 0.46;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

  /* Debug: force-visible book button (remove these after testing) */
  .btn-book--force {
    display: inline-flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    background: #b3934eff !important;   /* bright color to notice instantly */
    color: #fff !important;
    border: 2px solid rgba(0,0,0,0.06) !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    margin-left: auto !important;
  }

  /* Ensure the meta row isn't clipped at the bottom */
  .hotel-card { overflow: visible !important; }
  .hotel-card .body { padding-bottom: 14px !important; }



    /* ---------- CTA BAND ---------- */
    .cta {
      max-width: 1200px;
      margin: 0 auto 36px;
      background: linear-gradient(90deg, rgba(90,60,255,0.06), rgba(255,130,61,0.04));
      border-radius: 14px;
      padding: 18px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 10px;
      padding-left: 22px;
      padding-right: 22px;
    }
    .cta h3 { margin:0; font-size:18px; }
    .cta p { margin:0; color:var(--muted); font-size:13px; }

    footer {
      background: white;
      padding: 40px 0;
      text-align: center;
      color: #666;
    }

    /* Responsive tweaks */
    @media (max-width: 900px) {
      .hero { height: 58vh; min-height: 420px; }
      .hero-card { flex-direction: column; align-items:stretch; gap:12px; }
      .hero-card .search-controls { flex-direction: column; }
      .blob--one, .blob--two { display:none; }
      .hero { margin-top: 64px; }
    }
    @media (max-width: 420px) {
      .hero { height: auto; padding-bottom: 24px; }
      .hero-card h2 { font-size:18px; }
    }

    /* small animation */
    @keyframes floaty {
      0% { transform: translateY(0px) scale(1); }
      50% { transform: translateY(-6px) scale(1.02); }
      100% { transform: translateY(0px) scale(1); }
    }
    .blob { animation: floaty 8s ease-in-out infinite; }

    /* Hover color */
    .navbar .nav-link:hover {
      color: #ddb33d; /* change to any color you want */
    }
        /* Sign up button */
    .btn-signup {
      background-color: transparent;
      color: #8b6844;
      border-radius: 50px;
      border: 1px solid #8b6844;
      transition: all 0.3s ease;
    }

    /* Hover effect */
    .text-hover {
      background: none;
      border: none;
      padding: 0;
      color: var(--brand-brown);        /* normal text color */
      cursor: pointer;
      font: inherit;        /* match surrounding text */
    }

    .text-hover:hover {
      color: #ddb33d;       /* hover text color */

    }

    

/* ===== Travel-style Footer ===== */
.site-footer {
  background: #ffffff;
  border-top: 1px solid #eee;
  padding: 60px 0 30px;
  color: #555;
  font-size: 14px;
}

.site-footer h6 {
  font-size: 15px;
  font-weight: 600;
  margin-bottom: 16px;
  color: var(--brand-dark);
}

.site-footer ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.site-footer ul li {
  margin-bottom: 8px;
}

.site-footer ul li a {
  text-decoration: none;
  color: #6b6b6b;
  transition: color 0.2s ease;
}

.site-footer ul li a:hover {
  color: var(--brand-brown);
}

.footer-brand {
  font-size: 22px;
  font-weight: 700;
  color: var(--brand-brown);
}

.footer-desc {
  color: #777;
  line-height: 1.6;
  max-width: 320px;
}

.footer-bottom {
  border-top: 1px solid #eee;
  margin-top: 40px;
  padding-top: 20px;
  font-size: 13px;
  color: #888;
}

.social-links a {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 50%;
  border: 1px solid #ddd;
  margin-right: 8px;
  color: var(--brand-brown);
  text-decoration: none;
  transition: all 0.2s ease;
}

.social-links a:hover {
  background: var(--brand-brown);
  color: #fff;
  border-color: var(--brand-brown);
}

.footer-brand-col {
  display: flex;
  flex-direction: column;
  align-items: center;   /* horizontal center */
}

.footer-brand-col .footer-desc {
  text-align: center;
}

.footer-brand-col .social-links {
  justify-content: center;
}

  </style>
</head>
<body>

<?php
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand fs-3" href="home.php">GABAi 𓆝 </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navMenu">
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
        <li class="nav-item mx-3">
          <button id="search-button"
                  type="button"
                  class="nav-link btn">
            SEARCH
          </button>
        </li>
        <li class="nav-item mx-3"><a class="nav-link" href="home.php">HOME</a></li>
        <li class="nav-item mx-3"><a class="nav-link" href="#destinations">DEALS</a></li>
        <li class="nav-item mx-3"><a class="nav-link" href="#">ABOUT US</a></li>
      </ul>
    </div>

    <?php if ($user): ?>
      <!-- Logged in menu -->
      <div class="d-flex align-items-center">
        <div class="dropdown">
          <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
             href="#" id="userDropdown" data-bs-toggle="dropdown"
             aria-expanded="false" style="color:#8b6844;">

            <?php if (!empty($user['picture'])): ?>
              <img src="<?= htmlspecialchars($user['picture']); ?>"
                   alt="Avatar" width="36" height="36"
                   class="rounded-circle me-2">
            <?php else: ?>
              <span class="rounded-circle me-2"
                    style="display:inline-block;width:36px;height:36px;
                           background:#8b6844;color:#fff;line-height:36px;
                           text-align:center;border-radius:50%;">
                <?= strtoupper(substr($user['name'], 0, 1)); ?>
              </span>
            <?php endif; ?>

            <span class="fw-bold"><?= htmlspecialchars($user['name']); ?></span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="GoogleAPI/logout.php">Logout</a></li>
          </ul>
        </div>
      </div>

    <?php else: ?>
      <!-- Not logged in -->
          <button type="button" class="btn mx-2" 
                  style="background-color: transparent; 
                        color: #8b6844; 
                        border-radius: 50px; 
                        border-color: #8b6844;"
                  data-bs-toggle="modal" data-bs-target="#regisModal">
            Sign up
          </button>

          <button type="button" class="btn"
                  style="background-color: #8b6844; 
                        color: white; 
                        border-radius: 50px;"
                  data-bs-toggle="modal" data-bs-target="#loginModal">
            Log in
          </button>
    <?php endif; ?>

  </div>
</nav>

    <!-- Registration Modal -->
    <div class="modal fade" id="regisModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Create Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="GoogleAPI/regis.php" method="POST">
              <div class="mb-3">
                <label for="username" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
              </div>

              <div class="options-btns mt-4">
              <button type="submit" class="btn">Sign up</button>
              </div>

              <div class="d-flex align-items-center my-3">
                <hr class="flex-grow-1">
                <span class="px-2" style="color:#8b6844; font-weight:600;">OR</span>
                <hr class="flex-grow-1">
              </div>


              <div class="mb-3 text-center">
                <!-- Google login button — uses same rounded style as your Log in button -->
                <a href="GoogleAPI/google-login.php" class="btn" style="background-color: #eceff5ff; color: black; border-radius: 50px; display:inline-flex; align-items:center; gap:8px;">
                  <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="width:20px;height:20px;">
                  Sign up with Google
                </a>
              </div>
              
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Log In</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form action="GoogleAPI/login.php" method="POST">

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>

              <div class="options-btns mt-4">
                <button type="submit" class="btn">Log In</button>
              </div>

              <div class="d-flex align-items-center my-3">
                <hr class="flex-grow-1">
                <span class="px-2" style="color:#8b6844; font-weight:600;">OR</span>
                <hr class="flex-grow-1">
              </div>

              <div class="mb-3 text-center">
                <a href="GoogleAPI/google-login.php"
                  class="btn"
                  style="background-color: #eceff5ff; color: black; border-radius: 50px; display:inline-flex; align-items:center; gap:8px;">
                  <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                      alt="Google"
                      style="width:20px;height:20px;">
                  Login with Google
                </a>
              </div>

              <!-- Sign Up Link -->
              <div class="text-center mt-3">
                <p style="margin: 0;">
                  Don't have an account?
                  <a href="#" data-bs-toggle="modal" data-bs-target="#regisModal"
                    data-bs-dismiss="modal"
                    style="color:#8b6844; font-weight:600; text-decoration:none;">
                    Sign up
                  </a>
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>



<main>

<!-- Hero Section -->
<section class="hero position-relative py-5">


  <!-- Carousel -->
  <div id="heroCarousel" class="carousel slide w-100 h-100 position-absolute top-0 start-0" data-bs-ride="carousel">
    <div class="carousel-inner h-100">
      <div class="carousel-item active h-100" >
        <img src="Images/pinatubo1.jpg" class="d-block w-100 h-100" alt="Slide 1">
      </div>
      <div class="carousel-item h-100">
        <img src="Images/fd4.jpg" class="d-block w-100 h-100" alt="Slide 2">
      </div>
      <div class="carousel-item h-100">
        <img src="https://i.pinimg.com/1200x/d9/ad/59/d9ad5911ab6b224a94af3823bc508d0d.jpg" class="d-block w-100 h-100" alt="Slide 3">
      </div>
      <div class="carousel-item h-100">
        <img src="https://i.pinimg.com/1200x/23/1c/81/231c817b08e7e1b77e055c4b3bdcfdaa.jpg" class="d-block w-100 h-100" alt="Slide 4">
      </div>
    </div>
  </div>

  <!-- Overlay -->
  <div class="hero-overlay position-absolute w-100 h-100" style="background: rgba(0,0,0,0.45); pointer-events: none;"></div>
  

  <!-- Centered Search / CTA card -->
  <div class="hero-card shadow-sm">
    <div class="left">
      <h2>Find your next getaway</h2>
      <p>Search hotels, cottages and unique stays — curated deals in one place.</p>

      <div class="search-controls mt-3">
        <form id="hero-search-form" onsubmit="event.preventDefault(); document.getElementById('search-button').click();" style="display:flex; gap:10px; width:100%;">
          <input id="city-input"
                 class="form-control search-input"
                 placeholder="Search destination"
                 aria-label="Search city"
                 style="border-radius: 50px; padding-right: 16px;"
                 value="Manila" autocomplete="off">

          <button id="hero-search-button" class="btn btn-primary" type="button" href="#gallery" style="border-radius: 50px">Search</button>
        </form>
      </div>

      <div class="quick-filter" aria-hidden="true">
        <div class="chip">PRICE STARTS AT ₱1200</div>
        <div class="chip">Free cancellation</div>
        <div class="chip">Breakfast included</div>
        <div class="chip">Family friendly</div>
      </div>
    </div>

  </div>

  

  <!-- Carousel Controls (outside content layer, clickable) -->
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>

</section>

<!-- Why GABAi / Features -->
<section class="features" aria-label="Why choose GABAi" >
  <div class="feature">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M12 2l2 5 5 .5-4 3 1.2 5L12 14l-4.2 1.5L9 10 5 7.5 10 7 12 2z" fill="url(#g1)"></path>
      <defs><linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#5A3CFF"/><stop offset="1" stop-color="#FF823D"/></linearGradient></defs>
    </svg>
    <div>
      <h4>Handpicked stays</h4>
      <p>Quality-checked hotels and unique local homes.</p>
    </div>
  </div>

  <div class="feature">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <circle cx="12" cy="8" r="3" fill="#00D8FF"/>
      <path d="M4 20c2-4 6-6 8-6s6 2 8 6" stroke="#5A3CFF" stroke-width="1.2" fill="none"/>
    </svg>
    <div>
      <h4>Best price guarantee</h4>
      <p>Competitive rates + exclusive deals for members.</p>
    </div>
  </div>

  <div class="feature">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <rect x="3" y="3" width="18" height="14" rx="2" fill="#8b6844"/>
      <path d="M7 20h10" stroke="#1E1E1E" stroke-width="1.2"/>
    </svg>
    <div>
      <h4>Easy booking</h4>
      <p>Fast checkout, secure payments, and flexible options.</p>
    </div>
  </div>

  <div class="feature">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M12 2v6" stroke="#5A3CFF" stroke-width="1.6" stroke-linecap="round"/>
      <path d="M6 10l6 10 6-10" stroke="#FF823D" stroke-width="1.6" stroke-linejoin="round"/>
    </svg>
    <div>
      <h4>24/7 support</h4>
      <p>Local support and travel help whenever you need it.</p>
    </div>
  </div>
</section>

<section id="gallery"></section>

<!-- Featured deals / gallery -->
<section id="deals" aria-label="Featured deals">
  <div id="hotel-gallery" aria-live="polite">
    <!-- Keep this as an empty container (no script will populate it). You can render server-side PHP cards here. -->
    <noscript>
      <div class="hotel-card">
        <div class="thumb"><img src="https://picsum.photos/600/400?random=1" alt="Hotel"></div>
        <div class="body">
          <div>
            <h3>Seaside Retreat</h3>
            <p>Cozy seaside rooms, ocean views, breakfast included.</p>
          </div>
          <div class="meta">
            <div class="price">₱3,200 / night</div>
            <button class="btn-book">Book</button>
          </div>
        </div>
      </div>
    </noscript>
  </div>
</section>

<!-- CTA -->
<section style="padding:0 18px; margin-bottom: 24px;">
  <div class="cta">
    <div>
      <h3>Ready to explore? Save on your next stay.</h3>
      <p>Join GABAi and unlock member-only discounts, flexible booking, and priority support.</p>
    </div>
    <div style="display:flex; gap:10px;">
      <a href="#" class="btn btn-outline-primary" style="border-radius:10px;">Learn more</a>
      <a href="#" class="btn btn-primary" style="border-radius:10px; background: var(--brand-brown); border:none; color:#fff;">Get started</a>
    </div>
  </div>
</section>

  <!-- Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="row gy-4">

      <!-- Brand / About -->
      <div class="col-lg-4 col-md-6 text-center footer-brand-col">
        <div class="footer-brand mb-2">GABAi 𓆝</div>
        <p class="footer-desc">
          Your friendly travel guide and buddy. Discover destinations, book experiences,
          and explore the Philippines with confidence.
        </p>
      </div>

      <!-- Explore -->
      <div class="col-lg-2 col-md-6">
        <h6>Explore</h6>
        <ul>
          <li><a href="#">Destinations</a></li>
          <li><a href="#">Deals</a></li>
          <li><a href="#">Activities</a></li>
          <li><a href="#">Hotels</a></li>
        </ul>
      </div>

      <!-- Company -->
      <div class="col-lg-2 col-md-6">
        <h6>Company</h6>
        <ul>
          <li><a href="#about-us">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Press</a></li>
          <li><a href="#">Blog</a></li>
        </ul>
      </div>

      <!-- Support -->
      <div class="col-lg-2 col-md-6">
        <h6>Support</h6>
        <ul>
          <li><a href="#">Help Center</a></li>
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Cancellation Policy</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>

      <!-- Legal -->
      <div class="col-lg-2 col-md-6">
        <h6>Legal</h6>
        <ul>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Cookies</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom -->
    <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div>
        © 2025 <strong>GABAi</strong>. WEB DESIGN AND DEVELOPMENT 
      </div>
      <div class="mt-2 mt-md-0">
        CREUS - CPE42

      </div>
    </div>
  </div>
</footer>

</main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
  ?>

  <script>
    const isLoggedIn = <?php echo $user ? 'true' : 'false'; ?>;
    const currentUserName = <?php echo $user ? json_encode($user['name']) : 'null'; ?>;
  </script>

  <script src="/mytravel/Deals/book.js"></script>

  <script src="destination.js"></script>

  <!-- forward hero actions to the existing navbar search button so destination.js triggers normally -->
  <script>
document.addEventListener("DOMContentLoaded", () => {
  const heroBtn = document.getElementById("hero-search-button");
  const navBtn  = document.getElementById("search-button"); // used by destination.js
  const gallery = document.getElementById("gallery");

  if (heroBtn) {
    heroBtn.addEventListener("click", () => {

      if (navBtn) navBtn.click();

      if (gallery) {
        setTimeout(() => {
          gallery.scrollIntoView({ behavior: "smooth", block: "start" });
        }, 300); 
      }
    });
  }
});
  </script>

<!-- Booking Modal -->

<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered booking-modal">
    <div class="modal-content">
      <form id="bookingModalForm" action="checkout.php" method="POST" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="bookingModalLabel">Confirm Booking</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body px-4">
          <p id="modal-top-line" class="mb-2">
            You are booking for
            <span id="modal-top-guests">1</span>
            guest(s) on
            <span id="modal-top-date">No date selected</span>.
            Proceed to payment?
          </p>

          <!-- Per person / Guests row -->
          <div class="summary-row">
            <div>
              <div class="summary-label">Per person</div>
              <div id="modal-per-person" class="summary-value">₱0.00</div>
            </div>
            <div style="text-align:right;">
              <div class="summary-label">Guests</div>
              <div>
                <input id="modal-guests-input" name="guests_display" class="form-control guests-input" type="number" min="1" value="1" />
              </div>
            </div>
          </div>

          <div class="mt-3">
            <label for="modal-book-date" class="form-label fw-semibold">Select Date</label>
            <div class="summary-label">Check in</div>
            <input id="modal-book-date" name="checkin" class="form-control" type="date" />
          </div>

          <div class="mt-3">
            <div class="summary-label">Check out</div>
            <input id="modal-book-date-out" name="checkout" class="form-control" type="date" />
          </div>

          <div class="divider"></div>


          <!-- Subtotals -->


          <div class="summary-row">
            <div class="summary-label">Subtotal</div>
            <div id="modal-subtotal" class="summary-value">₱0.00</div>
          </div>

          <div class="summary-row">
            <div class="summary-label">Service fee</div>
            <div id="modal-service" class="summary-value">₱0.00</div>
          </div>
          <div class="summary-row">
            <div class="summary-label">Tax (12%)</div>
            <div id="modal-tax" class="summary-value">₱0.00</div>
          </div>

          <div class="divider"></div>

          <div class="total-row">
            <div>
              <div class="summary-label">Total</div>
              <div class="note">You will be redirected to Stripe to complete payment.</div>
            </div>
            <div class="text-end">
              <div class="total-amount" id="modal-total">₱0.00</div>
            </div>
          </div>
        </div>

        <div class="modal-footer px-4">
          <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
          <button id="modal-confirm-btn" type="submit" class="btn btn-confirm">Confirm &amp; Pay</button>
        </div>

        <!-- Hidden inputs posted to checkout.php  -->
        <input type="hidden" name="name" id="modal-input-name" value="">
        <input type="hidden" name="address" id="modal-input-address" value="">
        <input type="hidden" name="book_date" id="modal-input-book-date" value="">
        <input type="hidden" name="guests" id="modal-input-guests" value="1">
        <input type="hidden" name="per_person_amount" id="modal-input-per-person" value="0.00">
        <input type="hidden" name="service_fee_php" id="modal-input-service-hidden" value="0.00">
        <input type="hidden" name="tax_php" id="modal-input-tax-hidden" value="0.00">
        <input type="hidden" name="subtotal_php" id="modal-input-subtotal-hidden" value="0.00">
        <input type="hidden" name="total_php" id="modal-input-total-hidden" value="0.00">
      </form>
    </div>
  </div>
</div>

<script>
(function() {
  // Configuration
  const TAX_RATE = 0.12;           // 12%
  const SERVICE_FEE = 50.00;      // fixed service fee
  const DEFAULT_PER_PERSON = 600.00;
  const NIGHTLY_RATE = 1200.00;

const dateInputIn = document.getElementById('modal-book-date');
const dateInputOut = document.getElementById('modal-book-date-out');


  // Elements
  const modalEl = document.getElementById('bookingModal');
  if (!modalEl) return;

  const bsModal = new bootstrap.Modal(modalEl, { keyboard: true });
  const displayTopGuests = document.getElementById('modal-top-guests');
  const displayTopDate = document.getElementById('modal-top-date');

  const displayPerPerson = document.getElementById('modal-per-person');
  const guestsInput = document.getElementById('modal-guests-input');

  const displaySubtotal = document.getElementById('modal-subtotal');
  const displayService = document.getElementById('modal-service');
  const displayTax = document.getElementById('modal-tax');
  const displayTotal = document.getElementById('modal-total');

  const hiddenName = document.getElementById('modal-input-name');
  const hiddenAddress = document.getElementById('modal-input-address');
  const hiddenGuests = document.getElementById('modal-input-guests');
  const hiddenPerPerson = document.getElementById('modal-input-per-person');
  const hiddenService = document.getElementById('modal-input-service-hidden');
  const hiddenTax = document.getElementById('modal-input-tax-hidden');
  const hiddenSubtotal = document.getElementById('modal-input-subtotal-hidden');
  const hiddenTotal = document.getElementById('modal-input-total-hidden');

  const dateInput = document.getElementById('modal-book-date');

  // State
  let currentPerPerson = DEFAULT_PER_PERSON;
  let currentGuests = 1;

function calcAndRender() {
  currentGuests = Math.max(1, parseInt(guestsInput.value || '1', 10));
  displayTopGuests.textContent = currentGuests;

  // ---- DATE CALCULATION ----
  let nights = 0;
  if (dateInputIn.value && dateInputOut.value) {
    const checkIn = new Date(dateInputIn.value);
    const checkOut = new Date(dateInputOut.value);

    const diffMs = checkOut - checkIn;
    nights = diffMs > 0 ? Math.ceil(diffMs / (1200 * 60 * 60 * 24)) : 0;
  }

  // ---- COSTS ----
  const guestCost = currentPerPerson * currentGuests;
  const nightlyCost = nights * NIGHTLY_RATE;
  const subtotal = guestCost + nightlyCost;

  const service = SERVICE_FEE;
  const tax = subtotal * TAX_RATE;
  const total = subtotal + service + tax;

  const fmt = (v) =>
    '₱' + Number(v).toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });

  // ---- DISPLAY ----
  displayPerPerson.textContent = fmt(currentPerPerson);
  displaySubtotal.textContent = fmt(subtotal);
  displayService.textContent = fmt(service);
  displayTax.textContent = fmt(tax);
  displayTotal.textContent = fmt(total);

  // ---- HIDDEN INPUTS ----
  hiddenGuests.value = currentGuests;
  hiddenPerPerson.value = currentPerPerson.toFixed(2);
  hiddenService.value = service.toFixed(2);
  hiddenTax.value = tax.toFixed(2);
  hiddenSubtotal.value = subtotal.toFixed(2);
  hiddenTotal.value = total.toFixed(2);

  // ---- TOP DATE DISPLAY ----
  if (dateInputIn.value && dateInputOut.value) {
    displayTopDate.textContent =
      `${dateInputIn.value} to ${dateInputOut.value} (${nights} night${nights !== 1 ? 's' : ''})`;
  } else {
    displayTopDate.textContent = 'No date selected';
  }
}

  // Listeners
guestsInput.addEventListener('input', calcAndRender);
dateInputIn.addEventListener('input', calcAndRender);
dateInputOut.addEventListener('input', calcAndRender);


  // copy date into hidden field if needed 
  const form = document.getElementById('bookingModalForm');
  form.addEventListener('submit', function(ev) {
    const btn = document.getElementById('modal-confirm-btn');
    if (btn) btn.disabled = true;
  });

  // Global function so makeCard() can open the modal
  window.openBookingModal = function(hotel) {
    const name = hotel?.name || 'Unknown Destination';
    const address = hotel?.address_obj?.address_string || hotel?.address || '';

    hiddenName.value = name;
    hiddenAddress.value = address;

    // try to parse per-person from hotel fields (fallback to default)
    const parsedPrice = parseFloat(String(hotel?.price || hotel?.price_string || '').replace(/[^0-9.\-]/g, ''));
    currentPerPerson = Number.isFinite(parsedPrice) && parsedPrice > 0 ? parsedPrice : DEFAULT_PER_PERSON;

    guestsInput.value = 1;
    dateInput.value = '';

    calcAndRender();
    bsModal.show();
  };

  // initial render
  calcAndRender();
})();
</script>


<!-- Login required modal -->
<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginRequiredLabel">Login required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">You need to be logged in to book. Would you like to log in or create an account?</p>
      </div>
      <div class="modal-footer">


        <button id="lr-open-signup" type="button" class="btn btn-outline-secondary" data-bs-target="#regisModal" data-bs-toggle="modal" data-bs-dismiss="modal">Sign up</button>
        <button id="lr-open-login" type="button" class="btn" data-bs-target="#loginModal" data-bs-toggle="modal" data-bs-dismiss="modal" style="background:#8b6844;color:#fff;border-radius:8px;">Log in</button>

      </div>
    </div>
  </div>
</div>

<script>
(function () {
  // If isLoggedIn is not defined for some reason, treat as false
  const loggedIn = (typeof isLoggedIn !== 'undefined') ? Boolean(isLoggedIn) : false;

  // Bootstrap modal instance for login-required
  const lrModalEl = document.getElementById('loginRequiredModal');
  let lrModal = null;
  if (lrModalEl) lrModal = new bootstrap.Modal(lrModalEl, { keyboard: true });

  // Helper to show login-required modal
  function showLoginRequired() {
    if (!lrModal) return;
    lrModal.show();
  }

  // Intercept clicks on any .btn-book
  document.addEventListener('click', function (ev) {
    // find closest .btn-book ancestor (handles inner elements)
    const btn = ev.target.closest && ev.target.closest('.btn-book');
    if (!btn) return; // not a book button click

    // If user is logged in allow the normal handler to run
    if (loggedIn) {
      // nothing to do — let existing booking handlers (book.js / makeCard) proceed
      return;
    }

    // Not logged in: prevent any existing handlers from opening booking modal / submitting
    ev.preventDefault();
    ev.stopImmediatePropagation();

    showLoginRequired();
  }, true /* use capture so we block other handlers as early as possible */);


})();
</script>


</body>
</html>
