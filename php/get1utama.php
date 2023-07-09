<?php
require_once "db_connect.php";

session_start();

$cars = array (
    array(26,7,24,9,12,22),
    array(22,8,25,10,18,17),
    array(20,9,21,11,17,22),
    array(19,7,25,13,17,19),
    array(17,8,26,13,16,20),
    array(21,8,22,10,17,22),
    array(21,8,22,10,17,22),
    array(21,8,22,10,17,22)
);

if(isset($_POST['startDate'], $_POST['endDate'])){
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);

    if ($select_stmt = $db->prepare("SELECT * FROM uniqlo_1u WHERE Date>=? AND Date<=? ORDER BY Date")) {
        $select_stmt->bind_param('ss', $startDate, $endDate);
        
        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong"
                )); 
        }
        else{
            $result = $select_stmt->get_result();
            $message = array();
            $dateBar = array();
            $groundTotalCount = 0;
            $groundPassingCount = 0;
            $groundInCount = 0;
            $lvl1TotalCount = 0;
            $lvl1PassingCount = 0;
            $lvl1InCount = 0;
            
            while ($row = $result->fetch_assoc()) {
                if(substr($row['Date'], 0, 10) != "2022-11-26" && substr($row['Date'], 0, 10) != "2022-11-27" && substr($row['Date'], 0, 10) != "2022-11-28" && substr($row['Date'], 0, 10) != "2022-11-29"){
                    if(!in_array(substr($row['Date'], 0, 10), $dateBar)){
                        $message[] = array( 
                            'Date' => substr($row['Date'], 0, 10),
                            'TotalGroundCount' => 0,
                            'InStoreGroundCount' => 0,
                            'PassingGroundCount' => 0,
                            'TotalLvl1Count' => 0,
                            'InStoreLvl1Count' => 0,
                            'PassingLvl1Count' => 0
                        );
    
                        array_push($dateBar, substr($row['Date'], 0, 10));
                    }
    
                    $key = array_search(substr($row['Date'], 0, 10), $dateBar);
    
                    if($row['Mode'] == 'Ground'){
                        if($row['Door'] == 'in'){
                            //if(substr($row['Date'], 0, 10) != "2023-01-12" && substr($row['Date'], 0, 10) != "2023-01-13" && substr($row['Date'], 0, 10) != "2023-01-14"){
                            $groundInCount += (int)$row['Count'];
                            $groundTotalCount += (int)$row['Count'];
                            $message[$key]['TotalGroundCount'] += (int)$row['Count'];
                            $message[$key]['InStoreGroundCount'] += (int)$row['Count'];
                            //}
                        }
                        else if($row['Door'] == 'passing by'){
                            $groundPassingCount += (int)$row['Count'];
                            //$groundTotalCount += (int)$row['Count'];
                            //$message[$key]['TotalGroundCount'] += (int)$row['Count'];
                            $message[$key]['PassingGroundCount'] += (int)$row['Count'];
                        }
                    }
                    else if($row['Mode'] == 'Level 1' || $row['Mode'] == 'level-1'){
                        if($row['Door'] == 'in'){
                            $lvl1InCount += (int)$row['Count'];
                            $lvl1TotalCount += (int)$row['Count'];
                            $message[$key]['TotalLvl1Count'] += (int)$row['Count'];
                            $message[$key]['InStoreLvl1Count'] += (int)$row['Count'];

                            /*if(substr($row['Date'], 0, 10) == "2023-01-12" || substr($row['Date'], 0, 10) == "2023-01-13" || substr($row['Date'], 0, 10) == "2023-01-14" || substr($row['Date'], 0, 10) == "2023-01-15" || substr($row['Date'], 0, 10) == "2023-01-16"  || substr($row['Date'], 0, 10) == "2023-01-17"){
                                $randomFloat = rand(18, 21) / 10;
                                $groundInCount += ceil((float)$row['Count'] * $randomFloat);
                                $groundTotalCount += ceil((float)$row['Count'] * $randomFloat);
                                $message[$key]['TotalGroundCount'] += ceil((float)$row['Count'] * $randomFloat);
                                $message[$key]['InStoreGroundCount'] += ceil((float)$row['Count'] * $randomFloat);
                            }*/
                        }
                        else if($row['Door'] == 'passing by'){
                            $lvl1PassingCount += (int)$row['Count'];
                            //$lvl1TotalCount += (int)$row['Count'];
                            //$message[$key]['TotalLvl1Count'] += (int)$row['Count'];
                            $message[$key]['PassingLvl1Count'] += (int)$row['Count'];
                        }
                    }
                }
            }
            
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message,
                    "groundTotalCount" => $groundTotalCount,
                    "groundInCount" => $groundInCount,
                    "groundPassingCount" => $groundPassingCount,
                    "lvl1TotalCount" => $lvl1TotalCount,
                    "lvl1InCount" => $lvl1InCount,
                    "lvl1PassingCount" => $lvl1PassingCount
                ));   
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