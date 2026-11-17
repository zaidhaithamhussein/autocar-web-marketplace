<?php
require 'connect.php';
if (empty($_SESSION['user_id'])) { header('Location: signin.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: add-car.php'); exit; }



$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { header('Location: add-car.php'); exit; }

$stmt = mysqli_prepare($conn, "SELECT image_path FROM cars WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = $res ? mysqli_fetch_assoc($res) : null;

$del = mysqli_prepare($conn, "DELETE FROM cars WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($del, "ii", $id, $_SESSION['user_id']);
mysqli_stmt_execute($del);

if (mysqli_stmt_affected_rows($del) === 1) {
  if ($row && !empty($row['image_path']) && str_starts_with($row['image_path'], 'uploads/')) {
    @unlink($row['image_path']);
  }
  header('Location: add-car.php?deleted=1'); exit;
} else {
  header('Location: add-car.php?error=notowner'); exit;
}
