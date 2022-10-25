<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['startDate'], $_POST['endDate'])){
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);

    if ($select_stmt = $db->prepare("SELECT COUNT(*) AS oneUCount FROM uniqlo_1u WHERE Date>=? AND Date<=? ORDER BY Date")) {
        $select_stmt->bind_param('ss', $startDate, $endDate);
        
        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong when getting 1 Utama count"
                )); 
        }
        else{
            if ($select_stmt2 = $db->prepare("SELECT COUNT(*) AS damansaraCount FROM uniqlo_DA WHERE Date>=? AND Date<=? ORDER BY Date")) {
                $select_stmt2->bind_param('ss', $startDate, $endDate);
                
                // Execute the prepared query.
                if (! $select_stmt2->execute()) {
                    echo json_encode(
                        array(
                            "status" => "failed",
                            "message" => "Something went wrong when getting Damansara count"
                        )); 
                }
                else{
                    $result = $select_stmt->get_result();
                    $result2 = $select_stmt2->get_result();
                    $message = array();
                    //$dateBar = array();
                    $oneUtamaCount = 0;
                    $damansaraCount = 0;

                    if ($row = $result->fetch_assoc()) {
                        $oneUtamaCount = $row['oneUCount'];
                    }

                    if ($row2 = $result2->fetch_assoc()) {
                        $damansaraCount = $row['damansaraCount'];
                    }

                    /*while ($row = $result->fetch_assoc()) {
                        if(!in_array(substr($row['Date'], 0, 10), $dateBar)){
                            $message[] = array( 
                                'Date' => substr($row['Date'], 0, 10),
                                'oneUtamaCount' => 0,
                                'damansaraCount' => 0
                            );
        
                            array_push($dateBar, substr($row['Date'], 0, 10));
                        }
        
                        $key = array_search(substr($row['Date'], 0, 10), $dateBar);
        
                        if($row['Mode'] == 'Ground'){
                            if($row['Door'] == 'in'){
                                $groundInCount += (int)$row['Count'];
                                $groundTotalCount += (int)$row['Count'];
                                $message[$key]['TotalGroundCount'] += (int)$row['Count'];
                                $message[$key]['InStoreGroundCount'] += (int)$row['Count'];
                            }
                            else if($row['Door'] == 'passing by'){
                                $groundPassingCount += (int)$row['Count'];
                                $groundTotalCount += (int)$row['Count'];
                                $message[$key]['TotalGroundCount'] += (int)$row['Count'];
                                $message[$key]['PassingGroundCount'] += (int)$row['Count'];
                            }
                        }
                        else if($row['Mode'] == 'Level 1'){
                            if($row['Door'] == 'in'){
                                $lvl1InCount += (int)$row['Count'];
                                $lvl1TotalCount += (int)$row['Count'];
                                $message[$key]['TotalLvl1Count'] += (int)$row['Count'];
                                $message[$key]['InStoreLvl1Count'] += (int)$row['Count'];
                            }
                            else if($row['Door'] == 'passing by'){
                                $lvl1PassingCount += (int)$row['Count'];
                                $lvl1TotalCount += (int)$row['Count'];
                                $message[$key]['TotalLvl1Count'] += (int)$row['Count'];
                                $message[$key]['PassingLvl1Count'] += (int)$row['Count'];
                            }
                        }
                    }*/
                    
                    echo json_encode(
                        array(
                            "status" => "success",
                            //"message" => $message,
                            "oneUtamaCount" => $oneUtamaCount,
                            "damansaraCount" => $damansaraCount
                        ));   
                }
            }
        }
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