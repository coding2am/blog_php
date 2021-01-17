<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

//inserting process
if ($_POST && $_FILES) {
  $file = '../images/posts/' . ($_FILES['image']['name']);
  $imageType = pathinfo($file, PATHINFO_EXTENSION);

  if ($imageType != "png" && $imageType != "jpg" && $imageType != "jpeg") {
    echo "<script>alert('your file is not allow format!')</script>";
  } else {
    move_uploaded_file($_FILES['image']['tmp_name'], $file);

    $qry = "INSERT INTO posts (title,content,author_id,image) VALUES (:title,:content,:author_id,:image)";
    $stmt = $pdo->prepare($qry);
    $result = $stmt->execute([
      ':title' => $_POST['title'],
      ':content' => $_POST['content'],
      ':image' => $file,
      ':author_id' => $_SESSION['user_id'],
    ]);

    if ($result) {
      echo "<script>alert('Post Created Successfully!');window.location.href='index.php';</script>";
    }
  }
}
?>

<?php require_once('../layouts/backend/admin_body.php'); ?>
<div class="content">
  <div class="card p-3">
    <div class="card-header">
      <h2>Create New Post</h2>
    </div>
    <div class="card-body">
      <form action="add.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title">Titile</label>
          <input type="text" name="title" class="form-control" id="title" required>
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <textarea name="content" id="content" class="form-control" rows=10 required></textarea>
        </div>
        <div class="form-group">
          <label for="image">Pick an Image * jpg | jpeg | png </label>
          <input type="file" name="image" class="form-control-file" id="image" required>
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