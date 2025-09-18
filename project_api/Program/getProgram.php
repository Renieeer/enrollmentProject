<?php

header("Content-Type: application/json");
require_once('../trial.php');
    
try {
    $query = ("SELECT * FROM program_tbl ");
    $stmst =$connection->prepare($query);
    $stmst->execute();
    $Program = $stmst->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($Program);


} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>