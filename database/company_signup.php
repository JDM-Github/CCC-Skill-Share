<?php
require_once("./config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photoUrl = $_FILES['photoUrl'];
    $company_name = $_POST['company_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $company_website = $_POST['company_website'];
    $company_address = $_POST['company_address'];

    $photoPath = '../uploads/photos/' . basename($photoUrl['name']);
    move_uploaded_file($photoUrl['tmp_name'], $photoPath);

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("INSERT INTO company (photoUrl, companyName, email, password, website, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $photoPath, $company_name, $email, $password, $company_website, $company_address);
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