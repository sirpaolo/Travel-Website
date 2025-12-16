<?php 
require 'GoogleAPI/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GABAi – Travel Guide Buddy</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">

</head>
<body>

<?php
  if (isset($_SESSION['user'])) {
      $user = $_SESSION['user'];
  } else {
      $user = null;
  }

?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
      <div class="container">
        <a class="navbar-brand fs-3" href="#">GABAi 𓆝 </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navMenu">
          <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
            <li class="nav-item mx-3"><a class="nav-link" href="search.php">SEARCH</a></li>
            <li class="nav-item mx-3"><a class="nav-link" href="home.php">HOME</a></li>
            <li class="nav-item mx-3"><a class="nav-link" href="#destinations">DEALS</a></li>
            <li class="nav-item mx-3"><a class="nav-link" href="#about-us">ABOUT US</a></li>
          </ul>
        </div>

        <?php if ($user): ?>
          <!-- Logged-in view: avatar + name + dropdown -->
          <div class="d-flex align-items-center">
            <div class="dropdown">
              <a class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                style="color:#8b6844;">

                <!-- Avatar -->
                <?php if (!empty($user['picture'])): ?>
                  <img src="<?php echo htmlspecialchars($user['picture']); ?>" 
                      alt="Avatar" width="36" height="36" 
                      class="rounded-circle me-2">
                <?php else: ?>
                  <span class="rounded-circle me-2"
                        style="display:inline-block;
                              width:36px;height:36px;
                              background:#8b6844;color:#fff;
                              line-height:36px;text-align:center;
                              border-radius:50%;">
                      <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                  </span>
                <?php endif; ?>

                <!-- Name -->
                <span class="fw-bold"><?php echo htmlspecialchars($user['name']); ?></span>
              </a>

              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="GoogleAPI/logout.php">Logout</a></li>
              </ul>
            </div>
          </div>

        <?php else: ?>
          <!-- Not logged in: Sign up / Log in buttons -->
          <button type="button" 
                  class="text-hover pe-4 fw-semibold" 
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
            <form action="/mytravel/GoogleAPI/regis.php" method="POST">
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
                <a href="/mytravel/GoogleAPI/google-login.php" 
                  class="btn" 
                  style="background-color: #eceff5ff; color: black; border-radius: 50px; display:inline-flex; align-items:center; gap:8px;">
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
            <form action="/mytravel/GoogleAPI/login.php" method="POST">


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
                <a href="/mytravel/GoogleAPI/google-login.php"
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



  <!-- Hero Section -->
  <section class="hero position-relative overflow-hidden">

    <!-- Background Video -->
    <video
      class="hero-video"
      autoplay
      muted
      loop
      playsinline
    >
      <source src="Videos/lovetheph.mp4" type="video/mp4">
    </video>

    <!-- Overlay -->
    <div class="hero-overlay"></div>

    <!-- Hero content (UNCHANGED) -->
    <div class="w-100 text-center position-absolute top-50 start-50 translate-middle hero-content scroll-animate fade-up">
      <h1 class="display-4 fw-normal text-white scroll-animate fade-up">
        WELCOME TO THE
      </h1>

      <h1 class="hero-title text-white fw-bolder text-center scroll-animate fade-up">
        PHILIPPINES
      </h1>

      <p class="lead text-white scroll-animate fade-up">
        No matter where your heart leads you, we’re here to help you
      </p>
      <p class="lead text-white scroll-animate fade-up">
        find the perfect destination for every moment, every dream, and every adventure.
      </p>
    </div>


  </section>

<!-- Where to next? Section -->
<section class="container my-5">
    <h2 class="fw-semibold mb-4 scroll-animate fade-up">Where to next?</h2>

    <div class="row g-4">

      <div class="col-md-2" style="max-width: 300px;">
        <div class="card destination-card">
          <img src="https://i.pinimg.com/1200x/b6/0e/d4/b60ed4cc08e22ae3306dda3629ddffcc.jpg" class="card-img-top" alt="Destination 1">
        </div>
      </div>

      <div class="col-md-2" style="max-width: 300px;">
        <div class="card destination-card">
          <img src="https://i.pinimg.com/736x/ee/df/3b/eedf3b5961f9a2e727777e4e5ae6b1a1.jpg" class="card-img-top" alt="Destination 2">
        </div>
      </div>

      <div class="col-md-2" style="max-width: 300px;">
        <div class="card destination-card">
          <img src="Images/rizal1.jpg" class="card-img-top" alt="Destination 3">
        </div>
      </div>

      <div class="col-md-2" style="max-width: 300px;">
        <div class="card destination-card">
          <img src="https://i.pinimg.com/736x/49/4a/bd/494abd645df41d9b3b523f6bed010499.jpg" class="card-img-top" alt="Destination 4">
        </div>
      </div>

      <div class="col-md-2" style="max-width: 300px;">
        <div class="card destination-card">
          <img src="https://i.pinimg.com/736x/94/a2/bf/94a2bfb1e1690b9943e7a16197a08eb3.jpg" class="card-img-top" alt="Destination 5">
        </div>
      </div>

      <div class="col-md-2" style="max-width: 300px;">
        <div class="card destination-card">
          <img src="Images/bora2.jpg" class="card-img-top" alt="Destination 6">
        </div>
      </div>

    </div>
</section>


<section class="py-3"></section>


<!-- Second Hero (single background with clickable thumbnails) -->
<section class="hero-two position-relative">

  <!-- Background layer (changes via JS) -->
  <div class="hero-two-bg position-absolute top-0 start-0 w-100 h-100"
       style="background-image: url('https://i.pinimg.com/1200x/39/17/3d/39173dc813d7c8f53d8cd42ff0e37fd0.jpg');">
  </div>

  <!-- Dark overlay for readability -->
  <div class="hero-two-overlay position-absolute top-0 start-0 w-100 h-100" aria-hidden="true"></div>

  <!-- Centered text content -->
  <div class="hero-two-content">
    <h2 id="hero-two-title" class="h1 fw-bold text-white"></h2>
    <p id="hero-two-desc" class="lead text-white mb-5"></p>
  </div>


  <!-- Thumbnail row positioned bottom-right -->
  <div class="thumb-row" role="list">

  <!-- thumb 1 -->
  <button class="thumb-card"
    data-bg="Images/fd3.jpg"
    data-title="Mabini, Batangas"
    data-desc="Renowned for its stunning diving spots, beautiful beaches, and rich cultural heritage, making it a popular destination for both adventure seekers and history enthusiasts."
    aria-label="Set background to image 1">
    <img src="Images/fd3.jpg" alt="Thumbnail 1">
  </button>

  <!-- thumb 2 -->
  <button class="thumb-card active"
    data-bg="Images/coron1.jpg"
    data-title=" Coron, Palawan"
    data-desc="Renowned for its stunning crystal-clear waters, unique brackish composition, and breathtaking limestone formations, making it one of the cleanest lakes in Asia."
    aria-label="Set background to image 2">
    <img src="Images/coron1.jpg" alt="Thumbnail 2">
  </button>

  <!-- thumb 3 -->
  <button class="thumb-card"
    data-bg="Images/iao1.jpg"
    data-title="Cloud 9 Siargao"
    data-desc="The Surfing Capital of the Philippines, known for Cloud 9 waves and peaceful island life."
    aria-label="Set background to image 3">
    <img src="Images/iao1.jpg" alt="Thumbnail 3">
  </button>

  <!-- thumb 4 -->
  <button class="thumb-card"
    data-bg="Images/bora4.jpg"
    data-title="Boracay Island"
    data-desc="One of the world's finest beaches, famous for powder-soft sand and vibrant nightlife."
    aria-label="Set background to image 4">
    <img src="Images/bora4.jpg" alt="Thumbnail 4">
  </button>

    <!-- thumb 5 -->
  <button class="thumb-card"
    data-bg="Images/iao2.jpg"
    data-title="Coconut View Deck Siargao"
    data-desc="If you’re motorbiking around the island, the Coconut View Deck makes for a great place to pause and soak in the scenery."
    aria-label="Set background to image 5">
    <img src="Images/iao2.jpg" alt="Thumbnail 5">
  </button>

      <!-- thumb 6 -->
  <button class="thumb-card"
    data-bg="Images/zamb2.jpg"
    data-title="Coto Mines Zambales"
    data-desc="Whether you’re an outdoor enthusiast or someone looking to escape the hustle and bustle of city life, Coto Mines Zambales is a destination that promises a unique and immersive travel experience."
    aria-label="Set background to image 6">
    <img src="Images/zamb2.jpg" alt="Thumbnail 6">
  </button>

  </div>

  
</section>


<!-- Why GABAi / Features -->
<section class="features scroll-animate" aria-label="Why choose GABAi" id="destinations">
  <div class="feature scroll-animate fade-up">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M12 2l2 5 5 .5-4 3 1.2 5L12 14l-4.2 1.5L9 10 5 7.5 10 7 12 2z" fill="url(#g1)"></path>
      <defs><linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#5A3CFF"/><stop offset="1" stop-color="#FF823D"/></linearGradient></defs>
    </svg>
    <div>
      <h4>Handpicked stays</h4>
      <p>Quality-checked hotels and unique local homes.</p>
    </div>
  </div>

  <div class="feature scroll-animate fade-up">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <circle cx="12" cy="8" r="3" fill="#00D8FF"/>
      <path d="M4 20c2-4 6-6 8-6s6 2 8 6" stroke="#5A3CFF" stroke-width="1.2" fill="none"/>
    </svg>
    <div>
      <h4>Best price guarantee</h4>
      <p>Competitive rates + exclusive deals for members.</p>
    </div>
  </div>

  <div class="feature scroll-animate fade-up">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <rect x="3" y="3" width="18" height="14" rx="2" fill="#8b6844"/>
      <path d="M7 20h10" stroke="#1E1E1E" stroke-width="1.2"/>
    </svg>
    <div>
      <h4>Easy booking</h4>
      <p>Fast checkout, secure payments, and flexible options.</p>
    </div>
  </div>

  <div class="feature scroll-animate fade-up">
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


<!-- Popular Destinations Section -->
<section id="" class="container my-5">
  <h2 class="text-center fw-semibold mb-4">Traveler's Choice</h2>

  <div id="cardCarousel" class="carousel slide" data-bs-ride="false">
    <div class="carousel-inner">

      <!-- First Slide -->
      <div class="carousel-item active">
        <div class="row g-4 justify-content-center">

          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
                style="cursor: pointer;"
                onclick="window.location.href='Deals/iao.php';">

              <img src="https://i.pinimg.com/1200x/df/ac/00/dfac00de75d4315b35b9f65062098a30.jpg" 
                  class="card-img-top" alt="Destination 1">

              <div class="card-body">
                <p class="card-text text-muted">Boat tours • Siargao Island</p>
                <h5 class="card-title"><strong>Tri-Island Tour in Siargao</strong></h5>
                <p class="card-text text-muted">Hotel pick-up</p>
                <p class="card-text text-muted">Buffet Lunch</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★5.0</strong></span> (1,964) • 10K+ booked</p>
                <p><strong>₱ 1,470</strong></p>
              </div>

            </div>
          </div>


          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
              style="cursor: pointer;"
              onclick="window.location.href='Deals/mbrental.php';">
              <img src="https://i.pinimg.com/1200x/1a/ca/f0/1acaf0e79fbe00831ab089bb868dd70b.jpg" class="card-img-top" alt="Destination 2">
              <div class="card-body">
                <p class="card-text text-muted">Scooters & Bikes • Siargao Island</p>
                <h5 class="card-title"><strong>Siargao Island Motorbike Rental</strong></h5>
                <p class="card-text text-muted">Gasoline</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★4.8</strong></span> (467) • 700+ booked</p>
                <p><strong>₱ 395</strong></p>
              </div>
            </div>
          </div>

          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
              style="cursor: pointer;"
              onclick="window.location.href='Deals/boraparasail.php';">
              <img src="https://i.pinimg.com/1200x/71/75/5e/71755e6c7716a5b951f430ceb07f6035.jpg" class="card-img-top" alt="Destination 3">
              <div class="card-body">
                <p class="card-text text-muted">Paragliding • Boracay</p>
                <h5 class="card-title"><strong>Boracay Parasailing</strong></h5>
                <p class="card-text text-muted">English guided</p>
                <p class="card-text text-muted">Morning departure</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★4.9</strong></span> (2,876) • 30K+ booked</p>
                <p><strong>₱ 1,764</strong></p>
              </div>
            </div>
          </div>

          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
              style="cursor: pointer;"
              onclick="window.location.href='Deals/corondaytour.php';">
              <img src="https://i.pinimg.com/1200x/6e/88/06/6e88066b8a86d4f02b7c2c892c185e4c.jpg" class="card-img-top" alt="Destination 4">
              <div class="card-body">
                <p class="card-text text-muted">Water sports • Coron</p>
                <h5 class="card-title"><strong>Coron Ultimate Island Day Tour</strong></h5>
                <p class="card-text text-muted">Book now for tomorrow</p>
                <p class="card-text text-muted">Free cancellation</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★4.6</strong></span> (810) • 9K+ booked</p>
                <p><strong>₱ 1,689</strong></p>
              </div>
            </div>
          </div>


        </div>
      </div>

      <!-- Second Slide -->
      <div class="carousel-item">
        <div class="row g-4 justify-content-center">
          
          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
              style="cursor: pointer;"
              onclick="window.location.href='Deals/selah.php';">
              <img src="https://cdn.studios.skies.asia/www.selahpods.com/LRlbmSPuqR_1590551856.jpg" class="card-img-top" alt="Destination 5">
              <div class="card-body">
                <p class="card-text text-muted">Hotels • Pasay</p>
                <h5 class="card-title"><strong>Selah Pods Hotel Manila</strong></h5>
                <p class="card-text text-muted">Instant confirmation</p>
                <p class="card-text text-muted">Buffet breakfast</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★4.5</strong></span> (420) • 400+ booked</p>
                <p><strong>₱ 1,702</strong></p>
              </div>
            </div>
          </div>

          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
              style="cursor: pointer;"
              onclick="window.location.href='Deals/mayon.php';">
              <img src="https://i.pinimg.com/1200x/a2/05/45/a20545ee412364dd62f1e58dcd5cef21.jpg" class="card-img-top" alt="Destination 6">
              <div class="card-body">
                <p class="card-text text-muted">Day trips • Legazpi</p>
                <h5 class="card-title"><strong>Mayon ATV Bicol Adventure with Roundtrip Shuttle</strong></h5>
                <p class="card-text text-muted">Private tour</p>
                <p class="card-text text-muted">Small group</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★4.7</strong></span> (1,532) • 9K+ booked</p>
                <p><strong>₱ 2,045</strong></p>
              </div>
            </div>
          </div>

          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
              style="cursor: pointer;"
              onclick="window.location.href='Deals/vikings.php';">
              <img src="https://assets.micontenthub.com/DXBDJ/outlets/fogueira/1680868167-Buffet3.jpg" class="card-img-top" alt="Destination 7">
              <div class="card-body">
                <p class="card-text text-muted">Food & dining • Cebu City</p>
                <h5 class="card-title"><strong>Viking Luxury Buffet in SM City Cebu</strong></h5>
                <p class="card-text text-muted">Instant confirmation</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★4.9</strong></span> (22) • 600+ booked</p>
                <p><strong>₱ 910</strong></p>
              </div>
            </div>
          </div>

          <div class="col-md-3" style="max-width: 300px;">
            <div class="card destination-card"
              style="cursor: pointer;"
              onclick="window.location.href='Deals/oceanpark.php';">
              <img src="https://i.pinimg.com/736x/b4/b1/29/b4b129d0f76370a4ccd13d11e4fd111d.jpg" class="card-img-top" alt="Destination 8">
              <div class="card-body">
                <p class="card-text text-muted">Zoos & aquariums • Cebu City</p>
                <h5 class="card-title"><strong>Cebu Ocean Park Ticket</strong></h5>
                <p class="card-text text-muted">Book now for tomorrow</p>
                <p class="card-text text-muted"><span class="review-stars"><strong>★4.8</strong></span> (3,222) • 100K+ booked</p>
                <p><strong>₱ 690</strong></p>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#cardCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>

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
      <a href="#" class="btn btn-primary" style="border-radius:10px; background: var(--brand-brown); border:none; color:#fff;">Get started</a>
    </div>
  </div>
</section>


<!-- About -->
<section id="about-us" aria-labelledby="about-heading">
  <div class="about-container">

    <div class="bg-blobs">
      <!-- <span class="blob blob-green"></span> -->
      <span class="blob blob-yellow"></span>
    </div>

    <!-- Logo Image -->
    <img 
      src="Images/me4.jpg" 
      alt="GABAi logo" 
      class="about-logo"
    />

    <div class="about-content">
      <h2 id="about-heading">About Me</h2>

      <p>
        Hi! I'm the creator of <strong>GABAi</strong> — a name inspired by the words
        "<em>gabay</em>" meaning guide and "<em>bai</em>" meaning buddy in Bisaya. 
        Inspired by the beauty of the Philippines,
        GABAi was built with one simple mission: to be your friendly and reliable
        travel companion wherever your journey takes you.
      </p>

      <p>
        As a solo developer and passionate traveler, I’ve experienced both the
        excitement and the challenges of exploring new places — from planning routes
        and finding hidden spots to managing time and budgets. Those experiences
        inspired me to create a platform that helps travelers travel smarter, not harder.
      </p>

      <p>
        More than just a website, GABAi aims to celebrate culture, encourage exploration,
        and make travel more meaningful. My goal is to grow GABAi into a trusted companion
        for travelers who seek authentic experiences and unforgettable journeys.
      </p>

    </div>
  </div>
</section>





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


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="herobg.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("show");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    document.querySelectorAll(".scroll-animate").forEach(el => {
      observer.observe(el);
    });
  });
  </script>


</body>
</html>
