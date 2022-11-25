<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['startDate'], $_POST['endDate'])){
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $door = 'in';

    if ($select_stmt = $db->prepare("SELECT * FROM uniqlo_1u WHERE Door=? AND Date>=? AND Date<=? ORDER BY Date")) {
        $select_stmt->bind_param('sss', $door, $startDate, $endDate);
        
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
            $oneUtamaCount = 0;
            $damansaraCount = 0;
            $result = $select_stmt->get_result();
            $device = 'L1';
            $message = array();
            $dateBar = array();
            
            while($row = $result->fetch_assoc()) {
                if(!in_array(substr($row['Date'], 0, 10), $dateBar)){
                    $message[] = array( 
                        'Date' => substr($row['Date'], 0, 10),
                        'uniqloOU' => 0,
                        'uniqloDAS' => 0
                    );

                    array_push($dateBar, substr($row['Date'], 0, 10));
                }

                $key = array_search(substr($row['Date'], 0, 10), $dateBar);
                
                if($row['Door'] == 'in'){
                    $oneUtamaCount += (int)$row['Count'];
                    $message[$key]['uniqloOU'] += (int)$row['Count'];
                }
            }
            
            if ($select_stmt2 = $db->prepare("SELECT * FROM uniqlo_DA WHERE Door=? AND Device=? AND Date>=? AND Date<=? ORDER BY Date")) {
                $select_stmt2->bind_param('ssss', $door, $device, $startDate, $endDate);
                
                // Execute the prepared query.
                if (! $select_stmt2->execute()) {
                    echo json_encode(
                        array(
                            "status" => "failed",
                            "message" => $select_stmt2->error 
                        )); 
                }
                else{
                    $result2 = $select_stmt2->get_result();

                    while($row2 = $result2->fetch_assoc()) {
                        $damansaraCount += $row2['Count'];
                        $key = array_search(substr($row2['Date'], 0, 10), $dateBar);
                        $message[$key]['uniqloDAS'] += (int)$row2['Count'];
                        $oneUtamaCount += $row2['Count'];
                    }
                    
                    echo json_encode(
                        array(
                            "status" => "success",
                            "message" => $message,
                            "oneUtamaCount" => $oneUtamaCount,
                            "damansaraCount" => $damansaraCount
                        ));   
                }
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => "Failed to prepare query for DA"
                    )
                ); 
            }
        }
    }
    else{
        echo json_encode(
            array(
                "status" => "failed",
                "message" => "Failed to prepare query for 1U"
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