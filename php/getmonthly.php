<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['startDate'], $_POST['endDate'])){
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);

    if ($select_stmt = $db->prepare("SELECT * FROM felda_table2 WHERE Rec_time>=? AND Rec_time<=? ORDER BY Rec_time")) {
        $select_stmt->bind_param('ss', $startDate, $endDate);
        
        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => $select_stmt->error 
                )
            ); 
        }
        else{
            $result = $select_stmt->get_result();
            $message = array();
            $dateBar = array();
            $totalE1 = 0;
            $totalE2 = 0;
            $totalE3 = 0;
            $totalE4 = 0;
            $totalE5 = 0;
            $totalC1 = 0;
            $totalC2 = 0;
            $totalC3 = 0;
            $totalCount = 0;
            
            while($row = $result->fetch_assoc()) {
                if(!in_array(substr($row['Rec_time'], 0, 10), $dateBar)){
                    $message[] = array( 
                        'Date' => substr($row['Rec_time'], 0, 10),
                        'E1Count' => 0,
                        'E2Count' => 0,
                        'E3Count' => 0,
                        'E4Count' => 0,
                        'E5Count' => 0,
                        'C1Count' => 0,
                        'C2Count' => 0,
                        'C3Count' => 0
                    );

                    array_push($dateBar, substr($row['Rec_time'], 0, 10));
                }

                $totalCount = $totalCount + (int)$row['Big_car'] + (int)$row['Small_car'];
                $key = array_search(substr($row['Rec_time'], 0, 10), $dateBar);

                if($row['Node_name'] == 'c1'){
                    $message[$key]['C1Count'] += (int)$row['Big_car'];
                    $message[$key]['C1Count'] += (int)$row['Small_car'];
                    $totalC1 = $totalC1 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'c2'){
                    $message[$key]['C2Count'] += (int)$row['Big_car'];
                    $message[$key]['C2Count'] += (int)$row['Small_car'];
                    $totalC2 = $totalC2 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'c3'){
                    $message[$key]['C3Count'] += (int)$row['Big_car'];
                    $message[$key]['C3Count'] += (int)$row['Small_car'];
                    $totalC3 = $totalC3 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e1'){
                    $message[$key]['E1Count'] += (int)$row['Big_car'];
                    $message[$key]['E1Count'] += (int)$row['Small_car'];
                    $totalE1 = $totalE1 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e2'){
                    $message[$key]['E2Count'] += (int)$row['Big_car'];
                    $message[$key]['E2Count'] += (int)$row['Small_car'];
                    $totalE2 = $totalE2 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e3'){
                    $message[$key]['E3Count'] += (int)$row['Big_car'];
                    $message[$key]['E3Count'] += (int)$row['Small_car'];
                    $totalE3 = $totalE3 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e4'){
                    $message[$key]['E4Count'] += (int)$row['Big_car'];
                    $message[$key]['E4Count'] += (int)$row['Small_car'];
                    $totalE4 = $totalE4 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
                else if($row['Node_name'] == 'e5'){
                    $message[$key]['E5Count'] += (int)$row['Big_car'];
                    $message[$key]['E5Count'] += (int)$row['Small_car'];
                    $totalE5 = $totalE5 + (int)$row['Big_car'] + (int)$row['Small_car'];
                }
            }

            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message,
                    "totalE1" => $totalE1,
                    "totalE2" => $totalE2,
                    "totalE3" => $totalE3,
                    "totalE4" => $totalE4,
                    "totalE5" => $totalE5,
                    "totalC1" => $totalC1,
                    "totalC2" => $totalC2,
                    "totalC3" => $totalC3,
                    "totalCount" => $totalCount
                )
            );   
        }
    }
    else{
        echo json_encode(
            array(
                "status" => "failed",
                "message" => "Failed to prepare query for Felda"
            )
        ); 
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Parameter"
        )
    ); 
}