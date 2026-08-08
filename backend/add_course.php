<?php
include 'db_connect.php';

$semester = $_POST['semester'];
$course_name = $conn->real_escape_string($_POST['course_name']);
$teacher = $conn->real_escape_string($_POST['teacher']);
$room = $conn->real_escape_string($_POST['room']);
$day = $_POST['day'];
$start = $_POST['start_time'];
$end = $_POST['end_time'];

if (!$semester || !$course_name || !$teacher || !$room || !$day || !$start || !$end) {
  echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
  exit;
}

$query = "SELECT * FROM courses WHERE day='$day' AND semester='$semester' 
          AND ((start_time < '$end' AND end_time > '$start') 
          AND (teacher='$teacher' OR room='$room'))";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
  echo json_encode(['status' => 'conflict', 'message' => 'Conflict detected: teacher or room already occupied in this time slot.']);
} else {
  $sql = "INSERT INTO courses (semester, course_name, teacher, room, day, start_time, end_time)
          VALUES ('$semester', '$course_name', '$teacher', '$room', '$day', '$start', '$end')";
  if ($conn->query($sql)) {
    echo json_encode(['status' => 'ok', 'message' => 'Course added successfully.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
  }
}

$conn->close();
?>