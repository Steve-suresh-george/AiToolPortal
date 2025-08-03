<?php
include 'conn.php';
session_start(); // Start session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $username = $_POST['username'];
    $hashedpassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    // Insert query using prepared statements
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $firstname, $lastname, $username, $password);
        if ($stmt->execute()) {
            // Set session and redirect to dashboard
            $_SESSION['username'] = $username;
            header("Location: user.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
