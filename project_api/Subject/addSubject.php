<?php
header("Content-Type: application/json");
require_once('../trial.php');

// Get data from request
$data = json_decode(file_get_contents("php://input"), true);

$subject_id = $data['subject_id'] ?? null;

if (!$subject_id) {
    echo json_encode(["success" => false, "message" => "Subject ID is required"]);
    exit;
}

try {
    $query = "DELETE FROM subject_tbl WHERE subject_id = :subject_id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(":subject_id", $subject_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Subject deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete subject"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
