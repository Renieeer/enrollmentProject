<?php
header("Content-Type: application/json");
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["program_name"], $data["ins_id"])) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO program_tbl (program_name, ins_id) VALUES (?, ?)");
    $stmt->execute([$data["program_name"], $data["ins_id"]]);

    echo json_encode(["success" => true, "message" => "Program added successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
