<?php
require 'connect.php';
if (empty($_SESSION['user_id'])) { header('Location: signin.php'); exit; }

$myCars = [];
$sql = "SELECT id, model, price, description, image_path
        FROM cars
        WHERE user_id = ?
        ORDER BY created_at DESC";
if ($stmt = mysqli_prepare($conn, $sql)) {
  mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($res)) { $myCars[] = $row; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sell & Manage Cars - AutoCar</title>
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
      <a href="index.php">Home</a>
      <a href="cars.php">Cars</a>
      <a href="add-car.php" class="active">Sell Car</a>
      <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
    </nav>
  </header>

  <main>
    <h2 class="center">Sell Your Car</h2>
    <?php

$alerts = [];
if (!empty($_GET['added'])) {
  $alerts[] = ['type' => 'success', 'msg' => 'Car added successfully.'];
}
if (!empty($_GET['error'])) {
  $reasons = isset($_GET['r']) ? explode(',', $_GET['r']) : [];
  foreach ($reasons as $r) {
    if ($r === 'model_len')    $alerts[] = ['type' => 'error', 'msg' => 'Model must be 1–100 characters.'];
    if ($r === 'desc_len')     $alerts[] = ['type' => 'error', 'msg' => 'Description must be 1–1000 characters.'];
    if ($r === 'price')        $alerts[] = ['type' => 'error', 'msg' => 'Price must be a positive number.'];
    if ($r === 'invalid')      $alerts[] = ['type' => 'error', 'msg' => 'Please fill all required fields.'];
    if ($r === 'upload')       $alerts[] = ['type' => 'error', 'msg' => 'Image upload failed. Use a valid image (JPEG/PNG) under the size limit.'];
  }
}
?>
<?php if (!empty($alerts)): ?>
  <div class="alerts">
    <?php foreach ($alerts as $a): ?>
      <p class="alert alert-<?= htmlspecialchars($a['type']) ?>"><?= htmlspecialchars($a['msg']) ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
    <div class="form-container">
      <form action="add-car-handler.php" method="post" enctype="multipart/form-data">
        <label>Car Model</label>
<input type="text" name="model" required>

<label>Price</label>
<input type="number" name="price" step="1" min="1" required>

<label>Description</label>
<textarea name="description" required></textarea>
        <label>Photo (optional)</label>
        <input type="file" name="photo" accept="image/*">

        <button type="submit" name="insert">Add Car</button>
      </form>
    </div>

    <h2 class="center" style="margin-top:30px">Your Cars</h2>
    <?php if (isset($_GET['updated'])): ?>
      <p class="center" style="color:green">Car updated successfully.</p>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
      <p class="center" style="color:green">Car deleted.</p>
    <?php endif; ?>

    <?php if (count($myCars) === 0): ?>
      <p class="center">You haven’t listed any cars yet.</p>
    <?php else: ?>
      <div class="car-grid">
        <?php foreach ($myCars as $row): ?>
          <?php $img = $row['image_path'] ?: 'ASSETS/images/car1.jpg'; ?>
          <div class="car-card">
            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($row['model']) ?>">
            <h3><?= htmlspecialchars($row['model']) ?></h3>
            <p>$<?= number_format((float)$row['price'], 2) ?></p>
            <p class="desc"><?= nl2br(htmlspecialchars($row['description'])) ?></p>

        <div class="actions">
  <a href="edit-car.php?id=<?= (int)$row['id'] ?>" class="btn btn-primary">Edit</a>

  <form action="delete-car.php" method="post" class="inline-delete" onsubmit="return confirm('Delete this car?');">
    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
    <button type="submit" class="btn btn-primary">Delete</button>
  </form>
</div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
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
