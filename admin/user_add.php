<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}

//inserting process
if ($_POST) {

  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
    if (empty($_POST['name'])) {
      $nameError = "Name can't be empty!";
    }
    if (empty($_POST['email'])) {
      $emailError = "E-mail can't be empty!";
    }
    if (empty($_POST['password'])) {
      $passwordError = "Password can't be empty!";
    }

    if (strlen($_POST['password']) < 4) {
      $passwordError = "Passsword should be 4 characters atleast!";
    }
  } else {

    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    if (isset($_POST['role'])) {
      $permit_role = 1;
    } else {
      $permit_role = 0;
    }
    $qry = "SELECT * FROM users WHERE email=:email";
    $stmt = $pdo->prepare($qry);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email is already has been used! Try another email.')</script>";
    } else {
      $insertQry = "INSERT INTO users(name,password,email,role) VALUES (:name,:password,:email,:role)";
      $stmt = $pdo->prepare($insertQry);
      $result = $stmt->execute(
        array(
          ':name' => $name,
          ':email' => $email,
          ':password' => $password,
          ':role' => $permit_role,
        )
      );

      if ($result) {
        echo "<script>alert('Register Successfully!');window.location.href='users.php';</script>";
      }
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
          <span class="text-danger ml-2"><?= empty($nameError) ? '' : $nameError; ?></span>
          <input type="text" name="name" class="form-control" id="name">
        </div>
        <div class="form-group">
          <label for="email">E-mail</label>
          <span class="text-danger ml-2"><?= empty($emailError) ? '' : $emailError; ?></span>
          <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <span class="text-danger ml-2"><?= empty($passwordError) ? '' : $passwordError; ?></span>
          <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="role" value="admin" id="defaultCheck1">
          <label class="form-check-label" for="defaultCheck1">
            Admin Role
          </label>
        </div>
        <div class="form-group mt-4">
          <input type="submit" class="btn btn-success" value="Create">
          <a href="index.php" class="btn btn-info">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once('../layouts/backend/admin_footer.php');