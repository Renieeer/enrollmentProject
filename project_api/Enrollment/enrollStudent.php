<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['stud_id']) || empty($data['sem_id']) || empty($data['year_id'])) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

try {
    $stmt = $connection->prepare("INSERT INTO enrollment_tbl (stud_id, sem_id, year_id, status) 
                                  VALUES (:stud_id, :sem_id, :year_id, :status)");
    $stmt->bindParam(":stud_id", $data['stud_id']);
    $stmt->bindParam(":sem_id", $data['sem_id']);
    $stmt->bindParam(":year_id", $data['year_id']);
    $stmt->bindParam(":status", $data['status']);

    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Enrollment added successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
