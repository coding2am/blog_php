<?php
require_once('../config/dbconnect.php');

$id = $_GET['id'];

$deleteQry = "DELETE FROM users WHERE id = $id";
$stmt = $pdo->prepare($deleteQry);
$stmt->execute();

echo "<script>alert('Deleted Successfully!');window.location.href='users.php';</script>";