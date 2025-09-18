<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['enrollment_id'])) {
    echo json_encode(["success" => false, "message" => "Enrollment ID is required."]);
    exit;
}

try {
    $stmt = $connection->prepare("UPDATE enrollment_tbl 
                                  SET stud_id = :stud_id, sem_id = :sem_id, year_id = :year_id, status = :status
                                  WHERE enrollment_id = :enrollment_id");

    $stmt->bindParam(":stud_id", $data['stud_id']);
    $stmt->bindParam(":sem_id", $data['sem_id']);
    $stmt->bindParam(":year_id", $data['year_id']);
    $stmt->bindParam(":status", $data['status']);
    $stmt->bindParam(":enrollment_id", $data['enrollment_id']);

    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Enrollment updated successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>

