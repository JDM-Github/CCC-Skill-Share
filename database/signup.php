<?php
require_once("./config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photoUrl = $_FILES['photoUrl'];
    $resumePdf = $_FILES['resumePdf'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $program = $_POST['program'];
    $yearLevel = $_POST['yearLevel'];
    $skills = json_encode($_POST['skills']);

    $photoPath = '../uploads/photos/' . basename($photoUrl['name']);
    move_uploaded_file($photoUrl['tmp_name'], $photoPath);
    $resumePath = '../uploads/resumes/' . basename($resumePdf['name']);
    move_uploaded_file($resumePdf['tmp_name'], $resumePath);
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("INSERT INTO users (photoUrl, fullName, email, password, program, yearLevel, resumePdf, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $photoPath, $fullName, $email, $password, $program, $yearLevel, $resumePath, $skills);
    $stmt->execute();

    if (isset($_SESSION['redirect'])) {
        $location = $_SESSION['redirect'];
        unset($_SESSION['redirect']);
        header("Location: ../{$location}");
        exit;
    }
    header("Location: ../index.php");
    exit;
}
?>