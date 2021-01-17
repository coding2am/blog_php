<?php
require_once('../config/dbconnect.php');

$id = $_GET['id'];

$fileSearch = "SELECT image FROM posts WHERE id = $id";
$stmt = $pdo->prepare($fileSearch);
$stmt->execute();
$result = $stmt->fetchAll()[0]['image'];
if ($result) {
  unlink($result);
} else {
  exit();
}

$deleteQry = "DELETE FROM posts WHERE id = $id";
$stmt = $pdo->prepare($deleteQry);
$stmt->execute();

echo "<script>alert('Deleted Successfully!');window.location.href='index.php';</script>";