<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

try {
    if (!isset($data['sem_id'], $data['sem_name'], $data['year_id'])) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $query = "UPDATE semester_tbl 
              SET sem_name = :sem_name, year_id = :year_id 
              WHERE sem_id = :sem_id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(":sem_name", $data['sem_name']);
    $stmt->bindParam(":year_id", $data['year_id']);
    $stmt->bindParam(":sem_id", $data['sem_id']);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Semester updated successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>