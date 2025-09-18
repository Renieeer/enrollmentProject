<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);
$sem_id = $data['sem_id'] ?? null;

if (!$sem_id) {
    echo json_encode(["success" => false, "message" => "Semester ID is required"]);
    exit;
}

try {
    // Step 1: Check if subjects exist under this semester
    $checkSubjects = $connection->prepare("SELECT COUNT(*) FROM subject_tbl WHERE sem_id = :sem_id");
    $checkSubjects->bindParam(":sem_id", $sem_id, PDO::PARAM_INT);
    $checkSubjects->execute();
    if ($checkSubjects->fetchColumn() > 0) {
        // Step 2: Check if any of those subjects are enrolled
        $checkEnrollments = $connection->prepare("
            SELECT COUNT(*) 
            FROM subject_tbl s
            JOIN student_load e ON s.subject_id = e.subject_id
            WHERE s.sem_id = :sem_id
        ");
        $checkEnrollments->bindParam(":sem_id", $sem_id, PDO::PARAM_INT);
        $checkEnrollments->execute();

        if ($checkEnrollments->fetchColumn() > 0) {
            echo json_encode([
                "success" => false,
                "message" => "This semester cannot be deleted because students are still enrolled in its subjects."
            ]);
            exit;
        } else {
            echo json_encode([
                "success" => false,
                "message" => "This semester cannot be deleted because subjects are still linked to it."
            ]);
            exit;
        }
    }

    // Step 3: Safe to delete
    $stmt = $connection->prepare("DELETE FROM semester_tbl WHERE sem_id = :sem_id");
    $stmt->bindParam(":sem_id", $sem_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Semester deleted successfully"]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "An unexpected error occurred: " . $e->getMessage()
    ]);
}
?>
