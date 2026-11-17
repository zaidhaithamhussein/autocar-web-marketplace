<?php
require __DIR__ . '/config.php';

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
