<?php
require 'connect.php';
$err = '';
if (isset($_POST['signup'])) {
  $username = trim($_POST['username'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? ''; 

  if ($username === '' || $email === '' || $password === '') {
    $err = 'Please fill all fields.';
  } elseif (strlen($password) < 6) {
    $err = 'Password must be at least 6 characters.';
  } else {
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) > 0) {
      $err = 'Email already registered.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $ins  = mysqli_prepare($conn, "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
      mysqli_stmt_bind_param($ins, "sss", $username, $email, $hash);
      if (mysqli_stmt_execute($ins)) {
        header('Location: signin.php?ok=1'); exit;
      } else {
        $err = 'Could not create account.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up - AutoCar</title>
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
    <a href="signin.php">Sign In</a>
  </nav>
</header>

<main>
  <h2 class="center">Create Account</h2>
  <?php if ($err): ?><p class="center" style="color:red"><?= htmlspecialchars($err) ?></p><?php endif; ?>
  <div class="form-container">
    <form method="post">
      <label>Username</label>
      <input type="text" name="username" id="username" required>
      <label>Email</label>
      <input type="email" name="email" id="email" required>
      <label>Password</label>
      <input type="password" name="password" id="password" minlength="6" required>
      <button type="submit" name="signup">Sign Up</button>
    </form>
  </div>
</main>

<script src="script.js"></script>
</body>
</html>
