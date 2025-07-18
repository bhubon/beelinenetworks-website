<?php
include_once __DIR__ . './partials/header.php';


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$data = null;
$result = null;
if ($id >= 1) {
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $stars = trim($_POST['rating']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']); // Fixed
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($id > 0 && $stars !== '' && $title !== '' && $description !== '' && $name !== '' && $email !== '') {

        $stmt = $conn->prepare("UPDATE testimonials SET stars = ?, title = ?, description = ?, name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("issssi", $stars, $title, $description, $name, $email, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Testimonial updated successfully.";
        } else {
            $_SESSION['error'] = "Error updating testimonial: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all required fields.";
    }

    header("Location: testimonial-edit.php?id=".$id);
    exit();
}

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
                    <div class="card">
                        <div class="card-body">

                            <?php
                            if (!$id || !$result->num_rows > 0) {
                                echo '<h4 class="card-title text-center pt-4 text-danger">No Record Found</h4>';
                            } else {
                                ?>

                                <h4 class="card-title">Testimonial</h4>
                                <?php
                                if (isset($_SESSION['success'])) {
                                    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                                    unset($_SESSION['success']);
                                }
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                    unset($_SESSION['error']);
                                }
                                ?>
                                <form class="cmxform" id="commentForm" method="post" action="">
                                    <fieldset>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <div class="form-group">
                                            <label for="rating">Overall Rating</label>
                                            <select name="rating" class="form-control py-4" id="rating">
                                                <option <?php echo $data['stars'] == '5' ? 'selected' : ''; ?> value="5">5
                                                </option>
                                                <option <?php echo $data['stars'] == '4' ? 'selected' : ''; ?> value="4">4
                                                </option>
                                                <option <?php echo $data['stars'] == '3' ? 'selected' : ''; ?> value="3">3
                                                </option>
                                                <option <?php echo $data['stars'] == '2' ? 'selected' : ''; ?> value="2">2
                                                </option>
                                                <option <?php echo $data['stars'] == '1' ? 'selected' : ''; ?> value="1">1
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Title of your testimonial</label>
                                            <input id="title" class="form-control" type="text" name="title" required
                                                value="<?php echo $data['title'] ? htmlspecialchars($data['title']) : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Your testimonial</label>
                                            <textarea name="description" id="description" class="form-control"
                                                rows="5"><?php echo $data['description'] ? htmlspecialchars($data['description']) : ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Your name</label>
                                            <input id="name" class="form-control" type="text" name="name" required
                                                value="<?php echo $data['name'] ? htmlspecialchars($data['name']) : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Your email</label>
                                            <input id="email" class="form-control" type="email" name="email" required
                                                value="<?php echo $data['email'] ? htmlspecialchars($data['email']) : ''; ?>">
                                        </div>
                                        <input class="btn btn-primary" type="submit" value="Submit" name="submit">
                                    </fieldset>
                                </form>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <?php
        include_once __DIR__ . '/partials/footer.php';
        ?>