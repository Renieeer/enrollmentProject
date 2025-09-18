<?php

header("Content-Type: application/json");
require_once('../trial.php');
    
try {
    $query = ("SELECT * FROM subject_tbl ");
    $stmst =$connection->prepare($query);
    $stmst->execute();
    $subject = $stmst->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode( $subject);


} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>