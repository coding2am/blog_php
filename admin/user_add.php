<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

//inserting process
if ($_POST) {
  $email = $_POST['email'];
  $name = $_POST['name'];
  $password = $_POST['password'];

  $qry = "SELECT * FROM users WHERE email=:email";
  $stmt = $pdo->prepare($qry);
  $stmt->bindValue(':email', $email);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    echo "<script>alert('Email is already has been used! Try another email.')</script>";
  } else {
    $insertQry = "INSERT INTO users(name,password,email) VALUES (:name,:password,:email)";
    $stmt = $pdo->prepare($insertQry);
    $result = $stmt->execute(
      array(
        ':name' => $name,
        ':email' => $email,
        ':password' => $password,
      )
    );

    if ($result) {
      echo "<script>alert('Register Successfully!');window.location.href='users.php';</script>";
    }
  }
}
?>

<?php require_once('../layouts/backend/admin_body.php'); ?>
<div class="content">
  <div class="card p-3">
    <div class="card-header">
      <h2>Register New User</h2>
    </div>
    <div class="card-body">
      <form action="user_add.php" method="post">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-success" value="Create">
          <a href="index.php" class="btn btn-info">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once('../layouts/backend/admin_footer.php');