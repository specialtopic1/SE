<?php
// Enable error reporting for development (disable or adjust in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable display of errors to users

// Include the database connection file
include('database/connection.php');
session_start(); // Start the session if not already started

// Ensure the user is authenticated
if (!isset($_SESSION['Userid']) || !is_numeric($_SESSION['Userid'])) {
    // Redirect to login if the session does not have a valid user ID
    header('Location: login.php');
    exit();
}

$userId = (int)$_SESSION['Userid']; // Cast to integer to ensure it's numeric

try {
    // Prepare and execute a SQL query to fetch course data for the logged-in user
    $query = "
        SELECT courseid, userid, subjectid, subjectname, prerequisite, year, sem, coursetype, enrollmentStatus, creditHour, description 
        FROM courses 
        WHERE userid = :userid
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userid', $userId, PDO::PARAM_INT); // Bind the user ID parameter
    $stmt->execute();

    // Fetch all results as an associative array
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log error details to a file
    error_log('Database query failed: ' . $e->getMessage(), 3, 'logs/errors.log'); // Adjust path as needed
    // Display a user-friendly message
    echo 'An error occurred while fetching course data. Please try again later.';
    exit(); // Stop further execution
}
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List</title>
    <link rel="stylesheet" href="css/sidebar.css"> <!-- Adjust path if necessary -->
    <style>
        /* Additional styling for the table */
        .table-container {
            margin-left: 250px; /* Adjust according to your sidebar width */
            padding: 20px;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #7869B5;
            color: white;
        }
        h1 {
            color: #7869B5;; /* Change this color as needed */
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e96852;
            cursor: pointer; /* Change cursor to pointer on hover */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <!-- Sidebar content -->
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="enrol.php">Enroll in Course</a></li>
            <li><a href="enrolled-courses.php">Enrolled Courses</a></li>
            <li><a href="edit-profile.php">Edit Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="table-container">
        <h1>Course List</h1>
        <table>
            <thead>
                <tr>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Prerequisite</th>
                    <th>Year</th>
                    <th>Semester</th>
                    <th>Course Type</th>
                    <th>Enrollment Status</th>
                    <th>Credit Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each course and display it in the table
                foreach ($courses as $course) {
                    echo "<tr onclick=\"navigateToCourse(" .
                        "'" . addslashes($course['courseid']) . "'" . 
                        ")\">";
                    echo "<td>" . htmlspecialchars($course['subjectid'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($course['subjectname'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($course['prerequisite'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($course['year'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($course['sem'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($course['coursetype'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($course['enrollmentStatus'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($course['creditHour'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
       function navigateToCourse(courseid) {
    if (!courseid) {
        console.error('Invalid subject ID');
        return;
    }
    console.log("Course ID received in function:", courseid);
    const url = `course-details.php?id=${encodeURIComponent(courseid)}`;
    console.log('Navigating to:', url);
    window.location.href = url;
}

    </script>
</body>
</html>



