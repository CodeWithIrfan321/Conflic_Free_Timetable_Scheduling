<?php
include 'db_connect.php';
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : 0;
$stmt = $conn->prepare("SELECT * FROM courses WHERE semester=? ORDER BY FIELD(day,'Monday','Tuesday','Wednesday','Thursday','Friday'), start_time");
$stmt->bind_param('i', $semester);
$stmt->execute();
$res = $stmt->get_result();
$data = [];
while ($row = $res->fetch_assoc()) {
  $data[] = $row;
}
echo json_encode($data);
$conn->close();
?>