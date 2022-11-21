<?php

require_once 'db_connect.php';
// // Load the database configuration file 
 
// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
 
// Excel file name for download 
$fileName = "Summary_report" . date('Y-m-d') . ".xls";
$output = '';
//$itemType = $_GET['itemType'];
## Search 
$searchQuery = "";
$type = "passedinlvl1daily";
$message = array();
$dateBar = array();

if($_GET['fromDate'] != null && $_GET['fromDate'] != ''){
    $start = (string)$_GET['fromDate'];
    $searchQuery .= "Date >= '".$start."'";
}

if($_GET['toDate'] != null && $_GET['toDate'] != ''){
    $end = (string)$_GET['toDate'];
    
    if($_GET['fromDate'] != null && $_GET['fromDate'] != ''){
        $searchQuery .= " AND Date <= '".$end."'";
    }
    else{
        $searchQuery .= "Date <= '".$end."'";
    }
}

if($_GET['type'] != null && $_GET['type'] != '' && $_GET['type'] != '-'){
    $type = $_GET['type'];

    if($type == 'passedingroundmonthly'){
        $fields = array('TIMESTAMP', 'MODE', 'COUNT');
        $excelData = implode("\t", array_values($fields)) . "\n";
    }
    else if($type == 'passedinlvl1monthly'){
        $fields = array('TIMESTAMP', 'MODE', 'COUNT');
        $excelData = implode("\t", array_values($fields)) . "\n";
    }
    else if($type == 'passedingroundmonthly'){
        $fields = array('TIMESTAMP', 'MODE', 'COUNT');
        $excelData = implode("\t", array_values($fields)) . "\n";
    }
}
 
// Fetch records from database
$query = $db->query("SELECT * FROM uniqlo_1u WHERE ".$searchQuery);

if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){
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
                $groundInCount += (int)$row['Count'];
                $groundTotalCount += (int)$row['Count'];
                $message[$key]['TotalGroundCount'] += (int)$row['Count'];
                $message[$key]['InStoreGroundCount'] += (int)$row['Count'];
            }
            else if($row['Door'] == 'passing by'){
                $groundPassingCount += (int)$row['Count'];
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
                $message[$key]['PassingLvl1Count'] += (int)$row['Count'];
            }
        }
    }


    for($i=0; $i<count($message); $i++){
        if($type == 'passedingroundmonthly' || $type == 'passedinlvl1monthly'){
            $lineData = array($message[$i]['Date'], $message[$i]['Date'], $message[$i]['InStoreLvl1Count']);
            array_walk($lineData, 'filterData'); 
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        }
        else if($type == 'passedingrounddaily' || $type == 'passedinlvl1daily'){
            $parsedDate = date("Y-m-d H:i", strtotime('+8 hours',strtotime($message[$i]['timestamp'])));
            $lineData = array($parsedDate, $row['aircond_1'], $row['aircond_2']);   
            array_walk($lineData, 'filterData'); 
            $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        }
    }
}
else{ 
    $excelData .= 'No records found...'. "\n"; 
}
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;
?>
