<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

//updating process
if ($_POST) {

  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];

  $qry = "UPDATE users SET name='$name',email='$email' WHERE id='$id'";
  $stmt = $pdo->prepare($qry);
  $result = $stmt->execute();

  if ($result) {
    echo "<script>alert('Updated Successfully!');window.location.href='users.php';</script>";
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
      <form action="user_edit.php" method="post">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" class="form-control" id="name" value="<?= $user[0]['name']; ?>">
          <input type="hidden" name="id" value="<?= $user[0]['id']; ?>">
        </div>
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" class="form-control" value="<?= $user[0]['email']; ?>">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-success" value="Update">
          <a href="index.php" class="btn btn-info">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once('../layouts/backend/admin_footer.php');