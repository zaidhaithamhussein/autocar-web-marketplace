<?php require 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cars - AutoCar</title>
  <link rel="stylesheet" href="style.css?v=<?= filemtime('style.css') ?>">
  <link rel="icon" type="image/png" href="ASSETS/images/logo.png" />
</head>
<body>
  <header>
    <div class="logo-title">
      <img src="ASSETS/images/logo.png" class="logo" alt="AutoCar Logo">
      <h1>AutoCar</h1>
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
    <?php if (isset($_GET['added'])): ?>
      <p class="center" style="color:green">Car added successfully.</p>
    <?php endif; ?>

    <h2 class="center">Available Cars</h2>
    <div class="car-grid">
      <?php
        $sql  = "SELECT id, model, price, description, image_path FROM cars ORDER BY created_at DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0):
          while ($row = mysqli_fetch_assoc($result)):
            $img = $row['image_path'] ?: 'ASSETS/images/car1.jpg';
      ?>
        <div class="car-card">
          <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($row['model']) ?>">
          <h3><?= htmlspecialchars($row['model']) ?></h3>
          <p>$<?= number_format((float)$row['price'], 2) ?></p>
          <p class="desc"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
          
        </div>
      <?php
          endwhile;
        else:
      ?>
        <p class="center">No cars yet. Be the first to <a href="add-car.php">add a car</a>!</p>
      <?php endif; ?>
    </div>
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

  <script src="script.js"></script>
</body>
</html>
