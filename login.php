<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

     $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: user.php"); // ✅ Redirect here
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Username not found'); window.location.href='login.html';</script>";
    }
}
?>
