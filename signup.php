<?php
include 'conn.php';
session_start(); // Start session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    // Insert query
    $sql = "INSERT INTO users (firstname, lastname, username, password)
            VALUES ('$firstname', '$lastname', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Set session and redirect to dashboard
        $_SESSION['username'] = $username;
        header("Location: user.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
