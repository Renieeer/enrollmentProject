<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);
$year_id = $data['year_id'] ?? null;
$year_name = $data['year_name'] ?? null;

if (!$year_id || !$year_name) {
    echo json_encode(["success" => false, "message" => "Year ID and name are required"]);
    exit;
}

try {
    $stmt = $connection->prepare("UPDATE year_tbl SET year_name = :year_name WHERE year_id = :year_id");
    $stmt->bindParam(":year_name", $year_name);
    $stmt->bindParam(":year_id", $year_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Year updated successfully"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
