<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

try {
    if (!isset($data['sem_name'], $data['year_id'])) {
        echo json_encode(["success" => false, "message" => "Semester name and year_id are required."]);
        exit;
    }

   $sem_id   = $data['sem_id'] ?? null;
$sem_name = $data['sem_name'] ?? null;
$year_id  = $data['year_id'] ?? null;

if (!$sem_id || !$sem_name || !$year_id) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

$stmt = $connection->prepare("INSERT INTO semester_tbl (sem_id, sem_name, year_id) 
                              VALUES (:sem_id, :sem_name, :year_id)");
$stmt->bindParam(":sem_id", $sem_id);
$stmt->bindParam(":sem_name", $sem_name);
$stmt->bindParam(":year_id", $year_id);
$stmt->execute();

    echo json_encode(["success" => true, "message" => "Semester inserted successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
