<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['fname'];
    $lastname  = $_POST['lname'];
    $username  = $_POST['username'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT); // encrypt password

    // Insert query
    $sql = "INSERT INTO users (firstname, lastname, username, password) 
            VALUES ('$firstname', '$lastname', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        // Optionally redirect to login page
        // header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
