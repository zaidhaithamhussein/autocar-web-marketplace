<?php
require 'connect.php';
if (empty($_SESSION['user_id'])) { header('Location: signin.php'); exit; }

$error = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: add-car.php'); exit; }


$stmt = mysqli_prepare($conn, "SELECT id, model, price, description, user_id FROM cars WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$car = $res ? mysqli_fetch_assoc($res) : null;

if (!$car || (int)$car['user_id'] !== (int)$_SESSION['user_id']) {
  header('Location: add-car.php?error=notfound'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $model = trim($_POST['model'] ?? '');
  $price = is_numeric($_POST['price'] ?? null) ? (float)$_POST['price'] : -1;
  $desc  = trim($_POST['description'] ?? '');

  $errs = [];
  if ($model === '' || mb_strlen($model) > 100)  { $errs[] = 'Model must be 1–100 characters.'; }
  if ($desc  === '' || mb_strlen($desc) > 1000)  { $errs[] = 'Description must be 1–1000 characters.'; }
  if ($price <= 0)                               { $errs[] = 'Price must be a positive number.'; }

  if ($errs) {
    $error = implode(' ', $errs);
  } else {
    $upd = mysqli_prepare($conn,
      "UPDATE cars SET model = ?, price = ?, description = ?, updated_at = NOW() WHERE id = ? AND user_id = ?"
    );
    mysqli_stmt_bind_param($upd, "sdsii", $model, $price, $desc, $id, $_SESSION['user_id']);
    mysqli_stmt_execute($upd);
    header('Location: add-car.php?updated=1');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Car - AutoCar</title>
  <link rel="icon" type="image/png" href="ASSETS/images/logo.png" />
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
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
    <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username'] ?? 'Account') ?>)</a>
  </nav>
</header>

<main>
  <h2 class="center">Edit Your Car</h2>
  <?php if (!empty($error)): ?>
    <p class="center" style="color:red"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <div class="form-container">
    <form method="post">
      <label>Car Model</label>
      <input type="text" name="model" value="<?= htmlspecialchars($car['model'] ?? '') ?>" required>

      <label>Price</label>
      <input type="number" step="1" min="1" name="price" value="<?= htmlspecialchars($car['price'] ?? '') ?>" required>

      <label>Description</label>
      <textarea name="description" required><?= htmlspecialchars($car['description'] ?? '') ?></textarea>

      <button type="submit">Update</button>
    </form>
  </div>
</main>
</body>
</html>
