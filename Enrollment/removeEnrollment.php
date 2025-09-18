<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['enrollment_id'])) {
    echo json_encode(["success" => false, "message" => "Enrollment ID is required."]);
    exit;
}

try {
    $stmt = $connection->prepare("DELETE FROM enrollment_tbl WHERE enrollment_id = :enrollment_id");
    $stmt->bindParam(":enrollment_id", $data['enrollment_id']);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Enrollment deleted successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
S