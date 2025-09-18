<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data["stud_id"])) {
    echo json_encode(["success" => false, "message" => "Missing student ID"]);
    exit;
}

try {
    $query = "UPDATE student_tbl 
              SET first_name = :first_name, 
                  middle_name = :middle_name, 
                  last_name = :last_name, 
                  program_id = :program_id,
                  allowance = :allowance,
                  birth_date = :birth_date,
                  gender = :gender,
                  address = :address,
                  contact_number = :contact_number
              WHERE stud_id = :stud_id";

    $stmt = $connection->prepare($query);

    $stmt->bindParam(":first_name", $data["first_name"]);
    $stmt->bindParam(":middle_name", $data["middle_name"]);
    $stmt->bindParam(":last_name", $data["last_name"]);
    $stmt->bindParam(":program_id", $data["program_id"]);
    $stmt->bindParam(":allowance", $data["allowance"]);
    $stmt->bindParam(":birth_date", $data["birth_date"]);
    $stmt->bindParam(":gender", $data["gender"]);
    $stmt->bindParam(":address", $data["address"]);
    $stmt->bindParam(":contact_number", $data["contact_number"]);
    $stmt->bindParam(":stud_id", $data["stud_id"]);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Student updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update student"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
