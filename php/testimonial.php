<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['submit_btn'])) {
    require_once __DIR__ . '/../dashboard/partials/conn.php';

    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $title = trim($_POST['title'] ?? '');
    $testimonial = trim($_POST['testimonial'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($rating < 1 || $rating > 5 || empty($title) || empty($testimonial) || empty($name) || empty($email)) {
        $_SESSION['error'] = "Invalid input. Please fill all fields correctly.";
        header('Location: ../testimonials.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO testimonials (title, stars, description, name,email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $title, $rating, $testimonial, $name, $email);


    if ($stmt->execute()) {
        $_SESSION['success'] = "Thank you! Your testimonial has been submitted.";
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again later.";
    }

    $stmt->close();
    $conn->close();

    header('Location: ../testimonials.php');
    exit;

}