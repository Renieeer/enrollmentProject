<?php

header("Content-Type: application/json");
require_once('../trial.php');
    
try {
    $query = ("SELECT * FROM Student_tbl ");
    $stmst =$connection->prepare($query);
    $stmst->execute();
    $students = $stmst->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $students);


} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>