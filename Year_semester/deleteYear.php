<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);
$year_id = $data['year_id'] ?? null;

if (!$year_id) {
    echo json_encode(["success" => false, "message" => "Year ID is required"]);
    exit;
}

try {
    // Step 1: Check if semesters exist under this year
    $checkSemesters = $connection->prepare("SELECT COUNT(*) FROM semester_tbl WHERE year_id = :year_id");
    $checkSemesters->bindParam(":year_id", $year_id, PDO::PARAM_INT);
    $checkSemesters->execute();

    if ($checkSemesters->fetchColumn() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "This year cannot be deleted because semesters are still linked to it."
        ]);
        exit;
    }

    // Step 2: Delete year
    $stmt = $connection->prepare("DELETE FROM year_tbl WHERE year_id = :year_id");
    $stmt->bindParam(":year_id", $year_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Year deleted successfully"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
