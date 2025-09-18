<?php
header("Content-Type: application/json");
require_once('trial.php');

$data = json_decode(file_get_contents("php://input"), true);

// Validate input
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
    $query = "INSERT INTO student_tbl (stud_id, first_name, middle_name, last_name, program_id, allowance)
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmst = $connection->prepare($query);
    $stmst->execute([
        $data["stud_id"],
        $data["first_name"],
        $data["middle_name"],
        $data["last_name"],
        $data["program_id"],
        $data["allowance"]
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Student inserted successfully"
    ]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
