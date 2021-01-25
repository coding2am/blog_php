<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}


//updating process
if ($_POST) {

  if (empty($_POST['name']) || empty($_POST['email'])) {
    if (empty($_POST['name'])) {
      $nameError = "Name can't be empty!";
    }
    if (empty($_POST['email'])) {
      $emailError = "E-mail can't be empty!";
    }
  } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
    $passwordError = "Passsword should be 4 characters atleast!";
  } else {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($_POST['role'])) {
      if ($_POST['role'] == "admin") {
        $permit_role = 1;
      } else {
        $permit_role = 0;
      }
    } else {
      $permit_role = 0;
    }

    $qry = "SELECT * FROM users WHERE email=:email AND id !=:id";
    $stmt = $pdo->prepare($qry);
    $stmt->execute(array(
      ':email' => $email,
      ':id' => $id,
    ));

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email is already has been used! Try another email.');window.location.href='user_edit.php?id=$id';</script>";
    } else {

      if ($password != null) {
        $qry = "UPDATE users SET name='$name',email='$email', password='$password',role='$permit_role' WHERE id='$id'";
      } else {
        $qry = "UPDATE users SET name='$name',email='$email',role='$permit_role' WHERE id='$id'";
      }

      $stmt = $pdo->prepare($qry);
      $result = $stmt->execute();

      if ($result) {
        echo "<script>alert('Updated Successfully!');window.location.href='users.php';</script>";
      }
    }
  }
}

$id = $_GET['id'];
$qry = "SELECT * FROM users WHERE id = $id";
$stmt = $pdo->prepare($qry);
$stmt->execute();
$user = $stmt->fetchAll();

?>

<?php require_once('../layouts/backend/admin_body.php'); ?>
<style>
.preview {
  border: 1px solid black;
  border-radius: 10px;
  cursor: pointer;
  width: 10rem;
  height: 10rem;
  object-fit: cover;
}
</style>
<div class="content">
  <div class="card p-3">
    <div class="card-header">
      <h2>Create New Post</h2>
    </div>
    <div class="card-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="name">Name</label>
          <span class="text-danger ml-2"><?= empty($nameError) ? '' : $nameError; ?></span>
          <input type="text" name="name" class="form-control" id="name" value="<?= $user[0]['name']; ?>">
          <input type="hidden" name="id" value="<?= $user[0]['id']; ?>">
        </div>
        <div class="form-group">
          <label for="email">E-mail</label>
          <span class="text-danger ml-2"><?= empty($emailError) ? '' : $emailError; ?></span>
          <input type="email" id="email" name="email" class="form-control" value="<?= $user[0]['email']; ?>">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <span class="text-danger ml-2"><?= empty($passwordError) ? '' : $passwordError; ?></span>
          <p style="font-size: 12px; color:cornflowerblue; font-weight:bold;">Password may not change when you don't
            inserting new password!</p>
          <input type="password" id="password" name="password" class="form-control" placeholder="This is optional.">
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="role" value="admin" id="defaultCheck1" <?php if ($user[0]['role'] == 1) {
                                                                                                          echo "checked";
                                                                                                        } ?>>
          <label class="form-check-label" for="defaultCheck1">
            Admin Role
          </label>
        </div>
        <div class="form-group mt-4">
          <input type="submit" class="btn btn-success" value="Update">
          <a href="users.php" class="btn btn-info">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once('../layouts/backend/admin_footer.php');