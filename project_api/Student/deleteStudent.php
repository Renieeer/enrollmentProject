<?php
header("Content-Type: application/json");
require_once('../trial.php');

// Get data from request
$data = json_decode(file_get_contents("php://input"), true);

$stud_id = $data['stud_id'] ?? null;

if (!$stud_id) {
    echo json_encode(["message" => "Student ID is required"]);
    exit;
}

$query = "DELETE FROM student_tbl WHERE stud_id = :stud_id";
$stmt = $connection->prepare($query);
$stmt->bindParam(":stud_id", $stud_id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Student deleted successfully"]);
} else {
    echo json_encode(["message" => "Failed to delete student"]);
}
?>
