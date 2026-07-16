<?php
date_default_timezone_set('Asia/Manila'); // ✅ ADD HERE

// Database connection
$host = "localhost";
$db   = "dell_attendance_db";
$user = "root";
$pass = "laragon";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Users to insert
$user_ids = [1, 2, 3, 5, 7, 8, 9, 10, 11];

// Get today's date
$today = date('Y-m-d');

foreach ($user_ids as $user_id) {

    // Check if record already exists (avoid duplicates)
    $check = $conn->prepare("SELECT id FROM attendance WHERE user_id = ? AND work_date = ?");
    $check->bind_param("is", $user_id, $today);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {

        // Insert new attendance record
        $stmt = $conn->prepare("
            INSERT INTO attendance (user_id, work_date, created_at, updated_at)
            VALUES (?, ?, NOW(), NOW())
        ");

        $stmt->bind_param("is", $user_id, $today);
        $stmt->execute();
        $stmt->close();
    }

    $check->close();
}

$conn->close();

echo "Attendance records checked/created successfully.";
?>
