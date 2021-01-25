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
  if (empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])) {
    if (empty($_POST['title'])) {
      $titleError = "Title can't be empty!";
    }
    if (empty($_POST['content'])) {
      $contentError = "Please write something!";
    }
    if (empty($_FILES['image'])) {
      $imageError = "Pick some photo!";
    }
  } else {
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
          <span class="text-danger ml-2"><?= empty($titleError) ? '' : $titleError; ?></span>
          <input type="text" name="title" class="form-control" id="title">
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <span class="text-danger ml-2"><?= empty($contentError) ? '' : $contentError; ?></span>
          <textarea name="content" id="content" class="form-control" rows=10></textarea>
        </div>
        <div class="form-group">
          <label for="image">Pick an Image * jpg | jpeg | png </label>
          <span class="text-danger ml-2"><?= empty($imageError) ? '' : $imageError; ?></span>
          <input type="file" name="image" class="form-control-file" id="image">
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