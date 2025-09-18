<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

    $year_from = $data['year_from'] ?? null;
    $year_to = $data['year_to'] ?? null;   
    $year_id = $data['year_id'] ?? null;
try {
    $stmt = $connection->prepare("INSERT INTO year_tbl (year_id,year_from,year_to )
                                VALUES (:year_id, :year_from, :year_to)");
    $stmt->bindParam(":year_id", $year_id);
   $stmt->bindParam(":year_from", $year_from);
      $stmt->bindParam(":year_to", $year_to);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Year added successfully"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
    