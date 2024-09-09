<?php
session_start(); // Ensure the session is started to manage authentication and user state

// Define the base URL for navigation
$base_url = "index.php"; // Adjust based on your actual file structure

// Function to create a navigation link
function createNavLink($label, $url) {
    return '<li><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a></li>';
}

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit(); // Ensure no further code is executed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="enrol.php">Enroll in Course</a></li>
                <li><a href="enrolled-courses.php">Enrolled Courses</a></li>
                <li><a href="edit-profile.php">Edit Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Enrolled Courses</h1>       
        </div>
    </div>
</body>
</html>