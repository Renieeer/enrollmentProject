<?php
header("Content-Type: application/json");
require_once('../trial.php');

try {
    $query = "SELECT * FROM enrollment_tbl";
    $stmt = $connection->query($query);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($students);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
