<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);
$subject_id = $data['subject_id'] ?? null;

if (!$subject_id) {
    echo json_encode(["success" => false, "message" => "Subject ID is required"]);
    exit;
}

try {
    $query = "DELETE FROM subject_tbl WHERE subject_id = :subject_id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(":subject_id", $subject_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Subject deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "No subject found with that ID"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Delete query failed"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
