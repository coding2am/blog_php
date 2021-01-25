<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}

$id = $_GET['id'];
$qry = "SELECT * FROM posts WHERE id = $id";
$stmt = $pdo->prepare($qry);
$stmt->execute();
$post = $stmt->fetchAll();
//updating process
if ($_POST) {
  if (empty($_POST['title']) || empty($_POST['content'])) {
    if (empty($_POST['title'])) {
      $titleError = "Title can't be empty!";
    }
    if (empty($_POST['content'])) {
      $contentError = "Please write something!";
    }
  } else {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $oldPhoto = $_POST['oldPhoto'];

    if ($_FILES['image']['name'] != null) {
      $file = "../images/posts/" . $_FILES['image']['name'];
      $fileType = pathinfo($file, PATHINFO_EXTENSION);

      if ($fileType != "png" && $fileType != "jpg" && $fileType != "jpeg") {
        echo "<script>alert('your file is not allow format!')</script>";
      } else {
        move_uploaded_file($_FILES['image']['tmp_name'], $file);
        $qry = "UPDATE posts SET title='$title',content='$content', image='$file' WHERE id='$id'";
        $stmt = $pdo->prepare($qry);
        $result = $stmt->execute();
        unlink($oldPhoto);
        if ($result) {
          echo "<script>alert('Updated Successfully!');window.location.href='index.php';</script>";
        }
      }
    } else {
      $qry = "UPDATE posts SET title='$title',content='$content' WHERE id='$id'";
      $stmt = $pdo->prepare($qry);
      $result = $stmt->execute();

      if ($result) {
        echo "<script>alert('Updated Successfully!');window.location.href='index.php';</script>";
      }
    }
  }
}
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
      <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title">Titile</label>
          <span class="text-danger ml-2"><?= empty($titleError) ? '' : $titleError; ?></span>
          <input type="hidden" name="id" value="<?= $post[0]['id'] ?>">
          <input type="hidden" name="oldPhoto" value="<?= $post[0]['image'] ?>">
          <input type="text" name="title" class="form-control" id="title" value="<?= $post[0]['title']; ?>">
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <span class="text-danger ml-2"><?= empty($contentError) ? '' : $contentError; ?></span>
          <textarea name="content" id="content" class="form-control" rows=10><?= $post[0]['content']; ?></textarea>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="mr-5">
              <label for="oldPhoto">Current Photo Preview :</label>
              <div>
                <img src="<?= $post[0]['image']; ?>" class="preview" name="oldPhoto">
              </div>
            </div>
            <div>
              <label for="image">Update an Image (optional) * jpg | jpeg | png </label>
              <input type="file" name="image" class="form-control-file" id="image">
            </div>
          </div>
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