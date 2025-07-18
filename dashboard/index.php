<?php
include_once __DIR__ . './partials/header.php';

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
  $page = 1;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("SELECT id, title, stars, description, name, created_at FROM testimonials ORDER BY created_at DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$total_result = $conn->query("SELECT COUNT(*) as total FROM testimonials");
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);

?>

<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <!-- partial:partials/_sidebar.html -->
  <?php include_once __DIR__ . '/partials/sidebar.php'; ?>
  <!-- partial -->
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
              <h3 class="font-weight-bold">Welcome
                <?php echo $_SESSION['user_name'] ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?>
              </h3>
              <h6 class="font-weight-normal mb-0">
                All systems are running smoothly! You have
                <span class="text-primary"><?php echo $total_row['total'] ? $total_row['total'] : 0; ?> Reviews</span>
              </h6>
            </div>
          </div>
          <div class="row" style="margin-top:30px;">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Testomonials</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name & Designation</th>
                          <th>Stars</th>
                          <th>Title</th>
                          <th>Created At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                          <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['stars']); ?> ★</td>

                            <td>
                              <?php
                              $badgeClass = match ((int) $row['stars']) {
                                5 => 'bg-success',
                                4 => 'bg-info',
                                3 => 'bg-warning',
                                default => 'bg-danger',
                              };
                              ?>
                              <label class="badge <?php echo $badgeClass; ?>">
                                <?php echo $row['title']; ?>
                              </label>
                            </td>
                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td>
                              <a href="<?php echo 'testimonial-edit.php?id='.$row['id'];  ?>" class="btn btn-warning" href="">Edit/View</a>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>

                    </table>


                  </div>

                </div>
                <div class="card-footer">
                  <nav>
                    <ul class="pagination justify-content-end mt-3">
                      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page)
                          echo 'active'; ?>">
                          <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                      <?php endfor; ?>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
    <?php
    include_once __DIR__ . '/partials/footer.php';
    ?>