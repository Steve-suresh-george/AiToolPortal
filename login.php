<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Look for user
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: user.php"); // ✅ Redirect here
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='login_form.html';</script>";
        }
    } else {
        echo "<script>alert('Username not found'); window.location.href='login_form.html';</script>";
    }
}
?>
