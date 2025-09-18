<?php

header("Content-Type: application/json");
require_once('../trial.php');
    
try {

    $query = ("SELECT * FROM year_tbl");
    $stmst =$connection->prepare($query);
    $stmst->execute();
    $year = $stmst->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($year);


} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>