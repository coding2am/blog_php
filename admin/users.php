<?php
session_start();
require_once('../config/dbconnect.php');
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
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
            <h3 class="card-title">Users Table</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div>
              <a href="user_add.php" class="btn btn-sm btn-success m-2">Add new</a>
            </div>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name</th>
                  <th>E-mail</th>
                  <th>Role</th>
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

                if (empty($_POST['search'])) {
                  $qry = "SELECT * FROM users ORDER BY id DESC";
                  $stmt = $pdo->prepare($qry);
                  $stmt->execute();
                  $users = $stmt->fetchAll();
                  $totalPages = ceil(count($users) / $numberOfRecords);

                  $limitQry = "SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numberOfRecords";
                  $limitStmt = $pdo->prepare($limitQry);
                  $limitStmt->execute();
                  $limitResults = $limitStmt->fetchAll();
                } else {
                  $searchKey = $_POST['search'];
                  $searchQry = "SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC";
                  $searchStmt = $pdo->prepare($searchQry);
                  $searchStmt->execute();

                  $users = $searchStmt->fetchAll();
                  $totalPages = ceil(count($users) / $numberOfRecords);

                  $qry = "SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numberOfRecords";
                  $stmt = $pdo->prepare($qry);
                  $stmt->execute();
                  $limitResults = $stmt->fetchAll();
                }


                $num = 1;
                if ($limitResults) {
                  foreach ($limitResults as $user) { ?>
                <tr>
                  <td><?= $num++; ?></td>
                  <td><?= $user['name']; ?></td>
                  <td><?= $user['email']; ?></td>
                  <td><?php if ($user['role'] == 0) {
                            echo 'user';
                          } else {
                            echo 'admin';
                          } ?></td>
                  <td>
                    <div class="btn-group">
                      <a href="user_edit.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                      <a href="user_delete.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-danger"
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