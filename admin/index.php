<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}

if (isset($_POST['search'])) {
  setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
  if (empty($_GET['pageno'])) {
    unset($_COOKIE['search']);
    setcookie('search', null, -1, '/');
  }
}
?>

<?php require_once('../layouts/backend/admin_body.php'); ?>
<!-- main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Blogs Table</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div>
              <a href="add.php" class="btn btn-sm btn-success m-2">Add new blog</a>
            </div>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Title</th>
                  <th>Content</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                //pagination calculate
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                } else {
                  $pageno = 1;
                }

                $numberOfRecords = 3;
                $offset = ($pageno - 1) * $numberOfRecords;

                if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                  $qry = "SELECT * FROM posts ORDER BY id DESC";
                  $stmt = $pdo->prepare($qry);
                  $stmt->execute();
                  $posts = $stmt->fetchAll();
                  $totalPages = ceil(count($posts) / $numberOfRecords);

                  $qry = "SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numberOfRecords";
                  $stmt = $pdo->prepare($qry);
                  $stmt->execute();
                  $limitResults = $stmt->fetchAll();
                } else {
                  $searchKey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                  $qry = "SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC";
                  $stmt = $pdo->prepare($qry);
                  $stmt->execute();

                  $posts = $stmt->fetchAll();
                  $totalPages = ceil(count($posts) / $numberOfRecords);

                  $qry = "SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numberOfRecords";
                  $stmt = $pdo->prepare($qry);
                  $stmt->execute();
                  $limitResults = $stmt->fetchAll();
                }


                $num = 1;
                if ($posts) {
                  foreach ($limitResults as $post) { ?>
                <tr>
                  <td><?= $num++; ?></td>
                  <td><?= $post['title']; ?></td>
                  <td><?= substr($post['content'], 0, 70); ?> ...</td>
                  <td>
                    <div class="btn-group">
                      <a href="edit.php?id=<?= $post['id']; ?>" class="btn btn-sm btn-dark">Edit</a>
                      <a href="delete.php?id=<?= $post['id']; ?>" class="btn btn-sm btn-danger"
                        onClick="return confirm('Are you sure you want to delete this ?')">Delete</a>
                    </div>
                  </td>
                </tr>
                <?php }
                }
                ?>
              </tbody>
            </table>
            <!-- Pagination -->
            <div class="mt-3">
              <ul class="pagination float-right">
                <!-- First -->
                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>

                <!-- Previous -->
                <li class="page-item 
                  <?php if ($pageno <= 1) {
                    echo 'disabled';
                  } ?>">
                  <a class="page-link" href="
                  <?php if ($pageno <= 1) {
                    echo '#';
                  } else {
                    echo "?pageno=" . ($pageno - 1);
                  } ?>">Previous</a>
                </li>

                <!-- Current -->
                <li class="page-item"><a class="page-link" href="#"><?= $pageno; ?></a></li>

                <!-- Next -->
                <li class="page-item 
                  <?php if ($pageno >= $totalPages) {
                    echo 'disabled';
                  } ?>">
                  <a class="page-link" href="
                  <?php if ($pageno >= $totalPages) {
                    echo '#';
                  } else {
                    echo "?pageno=" . ($pageno + 1);
                  } ?>">Next</a>
                </li>

                <!-- Last -->
                <li class="page-item"><a class="page-link" href="?pageno=<?= $totalPages ?>">Last</a></li>
              </ul>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php require_once('../layouts/backend/admin_footer.php');