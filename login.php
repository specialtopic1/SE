<?php
session_start(); // Start the session

include('database/connection.php'); // Include the database connection

if ($_POST) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $query = 'SELECT * FROM users WHERE username = :username AND password = :password';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $_SESSION['Userid'] = $user['id'];
        $_SESSION['userFirstname'] = $user['firstname'];
        $_SESSION['userLastname'] = $user['lastname'];
        $_SESSION['user'] = $username;
    
        header('Location: dashboard.php');
        exit;
    } else {
        $error_message = 'Username or Password is incorrect.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1 class="app-name">Student Enrollment</h1>
<div class="login-container">
    <h2>Login</h2>
    <?php
    if (!empty($error_message)) {
        echo '<p class="message">' . htmlspecialchars($error_message) . '</p>';
    }
    ?>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
