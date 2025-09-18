<?php
header("Content-Type: application/json");
require_once('../trial.php');

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data["stud_id"]) || 
    empty($data["first_name"]) || 
    empty($data["middle_name"]) || 
    empty($data["last_name"]) || 
    empty($data["program_id"]) || 
    empty($data["allowance"])
) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

try {
    $query = "INSERT INTO student_tbl 
                (stud_id, first_name, middle_name, last_name, program_id, allowance, birth_date, gender, address, contact_number) 
              VALUES 
                (:stud_id, :first_name, :middle_name, :last_name, :program_id, :allowance, :birth_date, :gender, :address, :contact_number)";

    $stmt = $connection->prepare($query);

    $stmt->execute([
        ":stud_id" => $data["stud_id"],
        ":first_name" => $data["first_name"],
        ":middle_name" => $data["middle_name"],
        ":last_name" => $data["last_name"],
        ":program_id" => $data["program_id"],
        ":allowance" => $data["allowance"],
        ":birth_date" => $data["birth_date"] ?? null,
        ":gender" => $data["gender"] ?? null,
        ":address" => $data["address"] ?? null,
        ":contact_number" => $data["contact_number"] ?? null
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Student inserted successfully"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
