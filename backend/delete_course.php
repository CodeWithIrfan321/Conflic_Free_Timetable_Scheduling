<?php
include 'db_connect.php';
$id = intval($_POST['course_id']);
if ($id <= 0) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
  exit;
}
$conn->query("DELETE FROM courses WHERE course_id=$id");
echo json_encode(['status' => 'ok', 'message' => 'Course deleted']);
$conn->close();
?>