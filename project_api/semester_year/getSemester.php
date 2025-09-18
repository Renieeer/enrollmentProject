<?php

header("Content-Type: application/json");
require_once('../trial.php');
    
try {
    $query = ("SELECT * FROM semester_tbl ");
    $stmst =$connection->prepare($query);
    $stmst->execute();
    $semester = $stmst->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($semester);


} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>  