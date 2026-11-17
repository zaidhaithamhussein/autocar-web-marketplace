<?php require 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="google-site-verification" content="GcHJ2BcXFSk-i-_0FRj2aCSFkR6rzMuMm4JPT6j047o" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AutoCar - Home</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="style.css" />
  <link rel="icon" type="image/png" href="ASSETS/images/logo.png" />
</head>
<body>
  <header>
    <div class="logo-title">
      <img src="ASSETS/images/logo.png" alt="AutoCar Logo" class="logo">
      <h1 class="h2 font-weight-bold m-0">AutoCar</h1>
    </div>
    <nav>
  <a href="index.php" class="<?= basename($_SERVER['PHP_SELF'])==='index.php' ? 'active' : '' ?>">Home</a>
  <a href="cars.php" class="<?= basename($_SERVER['PHP_SELF'])==='cars.php' ? 'active' : '' ?>">Cars</a>
  <a href="add-car.php" class="<?= basename($_SERVER['PHP_SELF'])==='add-car.php' ? 'active' : '' ?>">Sell Car</a>

  <?php if (!empty($_SESSION['user_id'])): ?>
    <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username'] ?? 'Account') ?>)</a>
  <?php else: ?>
    <a href="signup.php" class="<?= basename($_SERVER['PHP_SELF'])==='signup.php' ? 'active' : '' ?>">Sign Up</a>
    <a href="signin.php" class="<?= basename($_SERVER['PHP_SELF'])==='signin.php' ? 'active' : '' ?>">Sign In</a>
  <?php endif; ?>
</nav>
  </header>

  <main>
    <section class="hero">
      <div class="hero-text">
        <h2>Welcome to AutoCar</h2>
        <p>Your trusted marketplace for buying and selling cars — safe, fast, and easy.</p>
        <button>Browse Cars</button>
      </div>
      <div class="hero-image">
        <img src="ASSETS/images/car1.jpg" alt="Showcase Car">
      </div>
    </section>

    <section class="about">
      <div class="about-text">
        <h2>Why Choose AutoCar?</h2>
        <p>AutoCar connects buyers and sellers directly. With thousands of cars listed, you’re sure to find the perfect match.  
        Listing your car is free and takes just minutes.</p>
      </div>
      <div class="about-video">
        <video controls width="100%">
          <source src="ASSETS/videos/Elton Cars.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </section>

    <?php
      $slides = [];
      $sql = "SELECT model, COALESCE(image_path, '') AS image_path
              FROM cars
              ORDER BY created_at DESC
              LIMIT 6";
      if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($res)) { $slides[] = $row; }
      }
      if (empty($slides)) {
        $slides = [
          ['model' => 'Showcase 1', 'image_path' => 'ASSETS/images/car1.jpg'],
          ['model' => 'Showcase 2', 'image_path' => 'ASSETS/images/corolla.jpg'],
          ['model' => 'Showcase 3', 'image_path' => 'ASSETS/images/mustang.jpeg'],
        ];
      }
    ?>

    <section class="gallery">
      <h2 class="text-center mb-3">View Our Cars</h2>

      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="4000" data-pause="hover">
        <ol class="carousel-indicators">
          <?php foreach ($slides as $i => $s): ?>
            <li data-target="#carouselExampleIndicators" data-slide-to="<?= $i ?>" class="<?= $i===0 ? 'active' : '' ?>"></li>
          <?php endforeach; ?>
        </ol>

        <div class="carousel-inner">
          <?php foreach ($slides as $i => $s):
            $img = $s['image_path'] ?: 'ASSETS/images/car1.jpg';
          ?>
            <div class="carousel-item <?= $i===0 ? 'active' : '' ?>">
              <img class="d-block w-100" src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($s['model']) ?>">
              <div class="carousel-caption d-none d-md-block">
                <h5><?= htmlspecialchars($s['model']) ?></h5>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

      <div class="carousel-thumbs d-flex justify-content-center mt-3">
        <?php foreach ($slides as $i => $s):
          $img = $s['image_path'] ?: 'ASSETS/images/car1.jpg';
        ?>
          <img class="carousel-thumb" src="<?= htmlspecialchars($img) ?>" alt=""
               data-index="<?= $i ?>" title="<?= htmlspecialchars($s['model']) ?>">
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer>
    <div class="footer-container">
      <div class="footer-column">
        <h3>About AutoCar</h3>
        <p>AutoCar is your trusted online marketplace for buying and selling cars. 
        We make it simple, fast, and secure.</p>
      </div>

      <div class="footer-column">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="cars.php">Browse Cars</a></li>
          <li><a href="add-car.php">Sell a Car</a></li>
          <li><a href="signup.php">Sign Up</a></li>
          <li><a href="signin.php">Sign In</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h3>Contact Us</h3>
        <p>Email: support@autocar.com</p>
        <p>Phone: +962 7 9971 0220 </p>
        <p>Location: Jordan, Amman</p>
        <div class="social">
        </div>
      </div>

      <div class="footer-column">
        <h3>FAQ</h3>
        <p><b>Q:</b> How do I sell my car?<br>
           <b>A:</b> Simply sign up and fill in the details on “Sell a Car”.</p>
        <p><b>Q:</b> Is it free?<br>
           <b>A:</b> Yes, listing your car is free!</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2025 Autocar - All rights reserved.</p>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
