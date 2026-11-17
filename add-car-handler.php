<?php
require 'connect.php';

if (empty($_SESSION['user_id'])) { header('Location: signin.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: add-car.php'); exit; }


$model       = trim($_POST['model'] ?? '');
$price       = (float)($_POST['price'] ?? 0);
$description = trim($_POST['description'] ?? ''); //3adlo
$imagePath   = null;

$errors = [];
if ($model === '' || mb_strlen($model) > 100)   { $errors[] = 'model_len'; }
if ($description === '' || mb_strlen($description) > 1000) { $errors[] = 'desc_len'; }
if (!is_numeric($_POST['price']) || $price <= 0) { $errors[] = 'price'; }

if ($errors) {
  header('Location: add-car.php?error=validation&r=' . urlencode(implode(',', $errors)));
  exit;
}

if (!empty($_FILES['photo']['name'])) {
  $tmp  = $_FILES['photo']['tmp_name'];
  $size = (int)$_FILES['photo']['size'];

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime  = finfo_file($finfo, $tmp);
  finfo_close($finfo);

  if (in_array($mime, ['image/jpeg','image/png']) && $size <= 2*1024*1024) {
    if (!is_dir('uploads')) { @mkdir('uploads', 0777, true); }
    $ext  = ($mime === 'image/png') ? 'png' : 'jpg';
    $name = 'car_' . bin2hex(random_bytes(6)) . '.' . $ext;
    move_uploaded_file($tmp, 'uploads/' . $name);
    $imagePath = 'uploads/' . $name;
  } else {
    header('Location: add-car.php?error=upload&r=upload');
    exit;
  }
}

$sql  = "INSERT INTO cars (model, price, description, image_path, user_id) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) { die("Prepare failed: " . mysqli_error($conn)); }


mysqli_stmt_bind_param($stmt, "sdssi", $model, $price, $description, $imagePath, $_SESSION['user_id']);
if (mysqli_stmt_execute($stmt)) {
  header('Location: add-car.php?added=1');  
  exit;
} else {
  die("Insert failed: " . mysqli_error($conn));
}
