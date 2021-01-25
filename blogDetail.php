<?php
session_start();
require_once('config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}


$id = $_GET['id'];
$qry = "SELECT * FROM posts WHERE id=$id";
$stmt = $pdo->prepare($qry);
$stmt->execute();
$post = $stmt->fetchAll();

$commentQry = "SELECT * FROM comments WHERE post_id=$id";
$comment_stmt = $pdo->prepare($commentQry);
$comment_stmt->execute();
$comments = $comment_stmt->fetchAll();

$authors = [];


if (!empty($comments)) {
  foreach ($comments as $key => $value) {
    $author_id = $comments[$key]['author_id'];
    $authorQry = "SELECT * FROM users WHERE id=$author_id";
    $author_stmt = $pdo->prepare($authorQry);
    $author_stmt->execute();
    $authors[]  = $author_stmt->fetchAll();
  }
}


if ($_POST) {

  if (empty($_POST['comment'])) {
    $commentError = "Please write something!";
  } else {
    $comment = $_POST['comment'];
    $insertQry = "INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)";
    $stmt = $pdo->prepare($insertQry);
    $result = $stmt->execute(
      array(
        ':content' => $comment,
        ':author_id' => $_SESSION['user_id'],
        ':post_id' => $id,
      )
    );

    if ($result) {
      header("Location:blogDetail.php?id=" . $id);
    }
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Detail Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<style>
.detailImage {
  height: 30rem;
  border: 1px solid black;
  border-radius: 2%;
  cursor: pointer;
  transition: all 1s ease;
}

.detailImage:hover {
  opacity: 0.9;
  transform: scale(1.05);
}

.content {
  text-indent: 5rem;
  text-align: justify;
}
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="">
      <!-- Main content -->
      <div class="container mt-5">
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="card card-widget">
            <div class="card-header">
              <div class="card-title">
                <!-- <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Image"> -->
                <div class="username"><a href="#"><?= $post[0]['title']; ?></a></div>
                <div class="description"><small><?= $post[0]['created_at']; ?></small></div>
              </div>
              <!-- /.user-block -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <img class="img-fluid pad detailImage" src="<?= $post[0]['image']; ?>" alt="Photo">
                </div>
                <div class="col-md-6">
                  <p class="content"><?= $post[0]['content']; ?></p>
                </div>
              </div>
              <div class="mt-3">
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i> Share</button>
                <button type="button" class="btn btn-default btn-sm"><i class="far fa-thumbs-up"></i> Like</button>
                <span class="ml-2 text-muted">127 likes - 3 comments</span>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer card-comments">
              <div class="card-comment">
                <!-- User image -->

                <?php if ($comments) {
                  foreach ($comments as $key => $comment) { ?>
                <div class="comment-text" style="margin-left:0 !important;">
                  <span class="username">
                    <?= $authors[$key][0]['name']; ?>
                    <span class="text-muted float-right">8:03 PM Today</span>
                  </span><!-- /.username -->
                  <?= $comment['content'] ?>
                </div>
                <?php }
                } ?>
                <!-- /.comment-text -->
              </div>
              <!-- /.card-comment -->
            </div>
            <!-- /.card-footer -->
            <div class="card-footer">
              <form action="" method="post">
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push">
                  <input type="text" name="comment" class="form-control form-control-sm"
                    placeholder="Press enter to post comment">
                </div>
                <small class="text-danger"><?= empty($commentError) ? '' : $commentError; ?></small>
              </form>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

      </section>
      <!-- /.content -->

      <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer" style="margin-left:0!important;">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.0.5
      </div>
      <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
      reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
</body>

</html>