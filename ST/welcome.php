<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

echo '<h2>Welcome, ' . htmlspecialchars($_SESSION['user']) . '!</h2>';
echo '<p><a href="logout.php">Logout</a></p>';
?>
