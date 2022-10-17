<?php
require_once "db_connect.php";

//session_start();

if(isset($_POST['startDate'], $_POST['endDate'])){
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);

    if ($select_stmt = $db->prepare("SELECT * FROM uniqlo_1u WHERE Date>=? AND Date<=?")) {
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
            $groundTotalCount = 0;
            $groundPassingCount = 0;
            $groundInCount = 0;
            $lvl1TotalCount = 0;
            $lvl1PassingCount = 0;
            $lvl1InCount = 0;
            
            while ($row = $result->fetch_assoc()) {
                $message['iduniqlo_1u'] = $row['iduniqlo_1u'];
                $message['Date'] = $row['Date'];
                $message['Count'] = $row['Count'];
                $message['Door'] = $row['Door'];
                $message['Mode'] = $row['Mode'];
                $message['Device'] = $row['Device'];

                if($row['Mode'] == 'Ground'){
                    if($row['Door'] == 'in'){
                        $groundInCount += (int)$row['Count'];
                    }
                    else{
                        $groundPassingCount += (int)$row['Count'];
                    }

                    $groundTotalCount++;
                }
                else{
                    if($row['Door'] == 'in'){
                        $lvl1InCount += (int)$row['Count'];
                    }
                    else{
                        $lvl1PassingCount += (int)$row['Count'];
                    }

                    $lvl1TotalCount++;
                }
            }

            $message['groundTotalCount'] = $groundTotalCount;
            $message['groundInCount'] = $groundInCount;
            $message['groundPassingCount'] = $groundPassingCount;
            $message['lvl1TotalCount'] = $lvl1TotalCount;
            $message['lvl1InCount'] = $lvl1InCount;
            $message['lvl1PassingCount'] = $lvl1PassingCount;
            
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message
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