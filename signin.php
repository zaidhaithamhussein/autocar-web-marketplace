<?php
require 'connect.php';
$err = '';
if (isset($_POST['signin'])) {
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($email === '' || $password === '') {
    $err = 'Please enter email and password.';
  } else {
    $stmt = mysqli_prepare($conn, "SELECT id, username, password_hash FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && $row = mysqli_fetch_assoc($res)) {
      if (password_verify($password, $row['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header('Location: index.php'); exit;
      }
    }
    $err = 'Invalid credentials.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign In - AutoCar</title>
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
    <a href="signup.php">Sign Up</a>
  </nav>
</header>

<main>
  <h2 class="center">Sign In</h2>
  <?php if ($err): ?><p class="center" style="color:red"><?= htmlspecialchars($err) ?></p><?php endif; ?>
  <?php if (isset($_GET['ok'])): ?><p class="center" style="color:green">Account created. Please sign in.</p><?php endif; ?>
  <div class="form-container">
    <form method="post">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <button type="submit" name="signin">Sign In</button>
    </form>
  </div>
</main>

<script src="script.js"></script>
</body>
</html>
