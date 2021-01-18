<?php
session_start();
require_once('config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Home</title>
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
.imagePreview {
  width: 100%;
  height: 20rem !important;
  object-fit: cover;
  cursor: pointer;
  transition: all 1s ease;
}

.imageContainer {
  overflow: hidden;
}

.imagePreview:hover {
  transform: scale(1.2);
  opacity: 0.8;
}
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Widgets</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <div class="row">
        <?php

        if (!empty($_GET['page_no'])) {
          $page_no = $_GET['page_no'];
        } else {
          $page_no = 1;
        }
        $numbers_of_records = 6;
        $offset = ($page_no - 1) * $numbers_of_records;

        $qry = "SELECT * FROM posts ORDER BY id DESC";
        $stmt = $pdo->prepare($qry);
        $stmt->execute();
        $posts = $stmt->fetchAll();
        $total_pages = ceil(count($posts) / $numbers_of_records);

        $limitQry = "SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numbers_of_records";
        $limitStmt = $pdo->prepare($limitQry);
        $limitStmt->execute();
        $limitResults = $limitStmt->fetchAll();

        if ($limitResults) {
          $num = 1;
          foreach ($limitResults as $value) {
        ?>
        <div class="col-md-4">
          <!-- Box Comment -->
          <div class="card card-widget">
            <div class="card-header">
              <div class="card-title">
                <!-- <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Image"> -->
                <div class="username"><a href="#"><?= $value['title']; ?></a></div>
                <div class="description"><small><?= $value['created_at']; ?></small></div>
              </div>
              <!-- /.user-block -->
            </div>
            <div class="p-1 imageContainer">
              <a href="blogDetail.php?id=<?= $value['id']; ?>"><img class="img-fluid pad imagePreview"
                  src="<?= $value['image']; ?>" alt="Photo"></a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <p>I took this photo this morning. What do you guys think?</p>
              <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i> Share</button>
              <button type="button" class="btn btn-default btn-sm"><i class="far fa-thumbs-up"></i> Like</button>
              <span class="float-right text-muted">127 likes - 3 comments</span>
            </div>
          </div>
          <!-- /.col -->

        </div>
        <?php }
        } ?>


        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
          <i class="fas fa-chevron-up"></i>
        </a>
      </div>

      <!-- pagination -->
      <div class="overflow-hidden">
        <ul class="pagination float-right">
          <li class="page-item"><a href="index.php?page_no=1" class="page-link">First</a></li>
          <li class="page-item 
          <?php if ($page_no <= 1) {
            echo "disabled";
          } ?>">
            <a class="page-link" href="
            <?php if ($page_no <= 1) {
              echo '#';
            } else {
              echo "?page_no=" . ($page_no - 1);
            } ?>">Previous</a>
          </li>
          <li class="page-item"><a href="#" class="page-link"><?= $page_no; ?></a></li>
          <li class="page-item  
          <?php if ($page_no >= $total_pages) {
            echo " disabled";
          } ?>"> <a class="page-link" href="
          <?php if ($page_no >= $total_pages) {
            echo '#';
          } else {
            echo "?page_no=" . ($page_no + 1);
          } ?>">Next</a></li>
          <li class="page-item"><a href="index.php?page_no=<?= $total_pages; ?>" class="page-link">Last</a></li>
        </ul>
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