<?php 
require '../GoogleAPI/config.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tri-Island Tour in Siargao — Activity • Booking</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="deals.css">
</head>
<body>

<?php
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
      <div class="container">
        <a class="navbar-brand fs-3" href="../home.php">GABAi 𓆝 </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navMenu">
          <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
            <li class="nav-item mx-3"><a class="nav-link" href="../search.php">SEARCH</a></li>
            <li class="nav-item mx-3"><a class="nav-link" href="../home.php">HOME</a></li>
            <li class="nav-item mx-3"><a class="nav-link" href="#destinations">DEALS</a></li>
            <li class="nav-item mx-3"><a class="nav-link" href="#">ABOUT US</a></li>
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
                <li><a class="dropdown-item" href="../GoogleAPI/logout.php">Logout</a></li>
              </ul>
            </div>
          </div>

        <?php else: ?>
          <!-- Not logged in: Sign up / Log in buttons -->
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
            <form action="../GoogleAPI/regis.php" method="POST">
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
                <a href="../GoogleAPI/google-login.php" class="btn" style="background-color: #eceff5ff; color: black; border-radius: 50px; display:inline-flex; align-items:center; gap:8px;">
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
            <form action="../GoogleAPI/login.php" method="POST">

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
                <a href="../GoogleAPI/google-login.php"
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


  <!-- hero -->
  <header class="hero" style="background-image:url('https://theworldtravelguy.com/wp-content/uploads/2020/02/DJI_0292.jpg)">
    <div class="overlay"></div>
    <div class="container hero-inner">
      <div class="row align-items-end">
        <div class="col-lg-8">
          <h1 class="h2 mb-1">Coron Ultimate Island Day Tour</h1>
          <div class="d-flex align-items-center mb-2">
            <div class="rating me-3">★ 4.6</div>
            <div class="text-white-50 me-3">(810 reviews)</div>
            <div class="badge bg-light text-dark">Water sports • Coron</div>
          </div>
          <p class="mb-0">Enjoy swimming, snorkeling, kayaking, and picture-perfect sightseeing spots with an experienced local guide. A delicious buffet lunch is included to keep you 
                         energized throughout your adventure.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
          <div class="pill text-white-50">Free cancellation • Instant confirmation</div>
        </div>
      </div>
    </div>
  </header>

  <!-- main content -->
  <main class="container my-5">
    <div class="row gy-4">

      <div class="col-lg-8">

        <!-- gallery -->
        <div class="row mb-3">
          <div class="col-12 mb-2">
            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
              <img id="mainGalleryImg" src="../Images/coron2.jpg" alt="main" style="width:100%; height:100%; object-fit:cover">
            </div>
          </div>

          <div class="col-6">
            <div class="row thumb-row gx-2">
              <div class="col-3"><img class="gallery-thumb rounded" src="../Images/coron2.jpg" 
                data-full="../Images/coron2.jpg" alt="t1"></div>
              <div class="col-3"><img class="gallery-thumb rounded" src="../Images/coron5.jpg" 
                data-full="../Images/coron5.jpg" alt="t2"></div>
              <div class="col-3"><img class="gallery-thumb rounded" src="https://i.ytimg.com/vi/9VGqOPQ5Aa0/maxresdefault.jpg" 
                data-full="https://i.ytimg.com/vi/9VGqOPQ5Aa0/maxresdefault.jpg" alt="t3"></div>
            </div>
          </div>
        </div>

        <!-- about / description -->
        <section class="mb-4">
          <h3>About this activity</h3>
            <p>
                Discover the most iconic destinations in Coron with this full-day Ultimate Island 
                Hopping Tour. Visit turquoise lagoons, dramatic limestone cliffs, white-sand beaches, 
                and some of the clearest lakes in the world. This tour is perfect for travelers 
                who want to experience the very best of Coron in one unforgettable day.
            </p>

            <h5>What's included</h5>
            <ul>
                <li>Boat transfers for all island stops</li>
                <li>Licensed local tour guide</li>
                <li>Buffet lunch and drinking water</li>
                <li>Life vests</li>
                <li>Entrance fees to selected attractions</li>
                <li>Free use of cottage or rest stops</li>
            </ul>

            <h5>Optional add-ons</h5>
            <ul>
                <li>Snorkeling gear (mask, fins, snorkel)</li>
                <li>Kayak rental</li>
                <li>Round-trip hotel transfers</li>
            </ul>
        </section>

            
        <section class="mb-4">
            <h5>Important to know</h5>
            <ul>
                <li>Tour duration: approximately 7–8 hours</li>
                <li>Guests must be comfortable with swimming and boat travel</li>
                <li>Weather may affect itinerary and availability</li>
                <li>Bring sun protection (hat, sunscreen, shades)</li>
                <li>Wear comfortable beachwear and aqua shoes</li>
                <li>Entrance fees vary and may require additional payment depending on season</li>
            </ul>
        </section>

        <!-- itinerary -->
        <section class="mb-4">
            <h3>Itinerary</h3>
            <ol>
                <li>Hotel or meet-up pick-up</li>
                <li>Boat departure from Coron Town port</li>
                <li>Kaynayan Lake or Kayangan Lake – swimming & viewpoint photos</li>
                <li>Twin Lagoon – snorkeling & optional kayak</li>
                <li>Atwayan Beach – buffet lunch</li>
                <li>CYC Beach – swimming & relaxation</li>
                <li>Siete Pecados Marine Park – snorkeling with marine life</li>
                <li>Return boat trip to Coron Port</li>
                <li>Hotel drop-off</li>
            </ol>
        </section>


        <!-- reviews -->
        <section class="mb-4">
        <h3>Reviews</h3>
        <div class="row g-3">

            <div class="col-12 review-card">
            <div class="d-flex justify-content-between mb-2">
                <div>
                <strong>Ella T.</strong> 
                <span class="text-muted">• March 2025</span>
                </div>
                <div class="rating">★ 4.8</div>
            </div>
            <p class="mb-0">
                Beautiful lakes and lagoons! Twin Lagoon was absolutely magical. 
                The buffet lunch was delicious too.
            </p>
            </div>

            <div class="col-12 review-card">
            <div class="d-flex justify-content-between mb-2">
                <div>
                <strong>John P.</strong> 
                <span class="text-muted">• February 2025</span>
                </div>
                <div class="rating">★ 4.7</div>
            </div>
            <p class="mb-0">
                A must-do tour when in Coron. The guide was friendly and explained 
                everything well. Great experience!
            </p>
            </div>

            <div class="col-12 review-card">
            <div class="d-flex justify-content-between mb-2">
                <div>
                <strong>Hannah G.</strong> 
                <span class="text-muted">• January 2025</span>
                </div>
                <div class="rating">★ 4.9</div>
            </div>
            <p class="mb-0">
                Stunning scenery at every stop! The water was incredibly clear, perfect 
                for snorkeling. Highly recommended.
            </p>
            </div>

        </div>
        </section>


      </div>

      <!-- booking sidebar -->
      <aside class="col-lg-4">
        <div class="booking-panel shadow-sm p-4 bg-white rounded">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <div class="small text-muted">From</div>
              <div class="price-large">₱ 1,689</div>
            </div>
            <div class="text-end">
              <div class="rating">★ 4,9</div>
              <div class="text-muted small">(810)</div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small">Date</label>
            <input class="form-control" type="date" id="bookDate" />
          </div>

          <div class="mb-3">
            <label class="form-label small">Guests</label>
            <select class="form-select" id="guestsSelect">
              <option value="1">1 guest</option>
              <option value="2" selected>2 guests</option>
              <option value="3">3 guests</option>
              <option value="4">4 guests</option>
            </select>
          </div>

          <button id="bookNowBtn" class="btn btn-success w-100 mb-2">Book Now</button>

          <hr>
          <div class="small text-muted">Free cancellation up to 24 hours before departure</div>
        </div>
      </aside>

    </div>
  </main>


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

<!-- Booking Modal -->
<!-- coron.php booking modal (₱1689) -->
<form id="bookingForm" method="post" action="../connect.php">
  <input type="hidden" name="destination" id="hiddendestination" value="Coron Ultimate Island Tour">
  <input type="hidden" name="country" id="hiddencountry" value="PHL">
  <input type="hidden" name="book_date" id="hiddenBookDate" value="">
  <input type="hidden" name="guests" id="hiddenGuests" value="1">
  <input type="hidden" name="per_person_amount" id="hiddenPerPersonAmount" value="1689">
  <input type="hidden" name="service_fee_php" id="hiddenServiceFee" value="50">
  <input type="hidden" name="tax_php" id="hiddenTax" value="0">
  <input type="hidden" name="subtotal_php" id="hiddenSubtotal" value="0">
  <input type="hidden" name="total_php" id="hiddenTotal" value="0">

  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Confirm Booking</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <p id="confirmText" class="mb-3">Review your booking details and confirm.</p>
          <div id="priceBreakdown" class="mb-2">
            <div class="d-flex justify-content-between"><div class="small text-muted">Per person</div><div class="small fw-bold" id="bdPerPerson">₱ 1,689.00</div></div>
            <div class="d-flex justify-content-between"><div class="small text-muted">Guests</div><div class="small" id="bdGuests">1</div></div>
            <hr>
            <div class="d-flex justify-content-between"><div>Subtotal</div><div id="bdSubtotal" class="fw-bold">₱ 1,689.00</div></div>
            <div class="d-flex justify-content-between"><div>Service fee</div><div id="bdServiceFee">₱ 50.00</div></div>
            <div class="d-flex justify-content-between"><div>Tax (12%)</div><div id="bdTax">₱ 202.68</div></div>
            <hr>
            <div class="d-flex justify-content-between fs-5"><div>Total</div><div id="bdTotal" class="fw-bold fs-5">₱ 1,941.68</div></div>
          </div>
          <div class="small text-muted">You will be redirected to Stripe to complete payment.</div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button id="confirmSubmit" type="submit" class="btn btn-success">Confirm & Pay</button></div>
      </div>
    </div>
  </div>
</form>

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


<script src="book.js"></script>
<script>
  //thumbnail gallery functionality
  document.querySelectorAll('.gallery-thumb').forEach(thumb => {
    thumb.addEventListener('click', function () {
      document.getElementById('mainGalleryImg').src = this.dataset.full;

      // Optional: highlight the active thumbnail
      document.querySelectorAll('.gallery-thumb').forEach(img => img.classList.remove('active-thumb'));
      this.classList.add('active-thumb');
    });
  });
</script>

</body>
</html>