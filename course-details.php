                <?php
                session_start();
                include('database/connection.php');
                
    // Store the referring page URL
    $_SESSION['referrer'] = $_SERVER['HTTP_REFERER'];

                try {
                    // Handle GET request to fetch course details
                    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                        $courseid = intval($_GET['id']);
                        echo "Received course ID: " . htmlspecialchars($courseid, ENT_QUOTES, 'UTF-8'); // Debugging output

                        // Prepare and execute SQL query to fetch course details
                        $stmt = $conn->prepare("SELECT * FROM courses WHERE courseid = :courseid");
                        $stmt->bindParam(':courseid', $courseid, PDO::PARAM_INT);
                        $stmt->execute();
                        $course = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$course) {
                            throw new Exception('Course not found.');
                        }

                    } else {
                        throw new Exception('Invalid course ID.');
                    }

                    // Handle POST request to update enrollment status
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['courseid']) && is_numeric($_POST['courseid'])) {
                        $courseid = intval($_POST['courseid']);

                        // Prepare and execute SQL query to fetch course details
                        $stmt = $conn->prepare("SELECT * FROM courses WHERE courseid = :courseid");
                        $stmt->bindParam(':courseid', $courseid, PDO::PARAM_INT);
                        $stmt->execute();
                        $course = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$course) {
                            throw new Exception('Course not found.');
                        }

                        // Check if the course is available for enrollment
                        if ($course['enrollmentStatus'] === 'Available') {
                            // Update the enrollment status to 'Waiting'
                            $updateStmt = $conn->prepare("UPDATE courses SET enrollmentStatus = 'Waiting' WHERE courseid = :courseid");
                            $updateStmt->bindParam(':courseid', $courseid, PDO::PARAM_INT);
                            $updateStmt->execute();

                            // Redirect to the course details page with updated status
                            header("Location: course-details.php?id=" . $courseid);
                            exit();
                        } else {
                            throw new Exception('Enrollment status is not available for this course.');
                        }
                    }

                } catch (PDOException $e) {
                    echo 'Query failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                    exit(); // Stop further execution
                } catch (Exception $e) {
                    echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                    exit(); // Stop further execution
                }
                ?>

                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Subject Details</title>
                    <link rel="stylesheet" href="css/sidebar.css"> <!-- Adjust path if necessary -->
                    <style>
                        .container {
                            display: flex;
                        }
                        .sidebar {
                            width: 250px;
                            background-color: #7869B5;
                            color: white;
                            padding: 20px;
                            box-sizing: border-box;
                            position: fixed;
                            top: 0;
                            left: 0;
                            height: 100vh;
                        }

                        .main-content {
                            margin-left: 250px; /* Adjust according to your sidebar width */
                            padding: 20px;
                            box-sizing: border-box;
                            flex: 1;
                        }

                        .details-container {
                            background-color: #f9f9f9;
                            border: 1px solid #ddd;
                            padding: 20px;
                            border-radius: 5px;
                            max-width: 800px;
                            margin: 0 auto;
                        }

                        h1 {
                            color: #7869B5;
                        }

                        .detail-item {
                            margin-bottom: 15px;
                        }

                        .detail-item label {
                            font-weight: bold;
                        }

                        .enroll-button {
            background-color: #7869B5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .enroll-button:hover {
            background-color: #e96852;
        }
                        button[type="submit"], .enroll-button {
                        margin: 0;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
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
                            <h1>Subject Details</h1>
                            <div class="details-container">
                                <div class="detail-item">
                                    <label for="subjectid">Subject ID:</label>
                                    <span><?php echo htmlspecialchars($course['subjectid'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="subjectName">Subject Name:</label>
                                    <span><?php echo htmlspecialchars($course['subjectname'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="prerequisite">Prerequisite:</label>
                                    <span><?php echo htmlspecialchars($course['prerequisite'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="year">Year:</label>
                                    <span><?php echo htmlspecialchars($course['year'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="sem">Semester:</label>
                                    <span><?php echo htmlspecialchars($course['sem'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="coursetype">Course Type:</label>
                                    <span><?php echo htmlspecialchars($course['coursetype'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="enrollmentStatus">Enrollment Status:</label>
                                    <span><?php echo htmlspecialchars($course['enrollmentStatus'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="creditHours">Credit Hours:</label>
                                    <span><?php echo htmlspecialchars($course['creditHour'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label for="description">Description:</label>
                                    <span><?php echo htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                
                                <?php if ($course['enrollmentStatus'] === 'Available'): ?>
                    <form action="" method="post" style="display: flex; gap: 10px;">
                        <input type="hidden" name="courseid" value="<?php echo htmlspecialchars($course['courseid'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="enroll-button">Enroll Now</button>
                        <a href="enrol.php" class="enroll-button">Cancel</a>
                    </form>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars($_SESSION['referrer'], ENT_QUOTES, 'UTF-8'); ?>" class="enroll-button">Back</a>
                <?php endif; ?> 
                            </div>
                        </div>
                    </div>
                </body>
                </html>
