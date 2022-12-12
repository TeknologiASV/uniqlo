<?php
## Database configuration
require_once 'db_connect.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";

if($_POST['startDate'] != null && $_POST['endDate'] != '' && $_POST['branch']){
  $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
  $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);
  $branch = filter_input(INPUT_POST, 'branch', FILTER_SANITIZE_STRING);

  $searchQuery = " WHERE Date>='".$startDate."' AND Date<='".$endDate."' and Outlet = '".$branch."'";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from transaction");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from transaction".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * from transaction".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
  $count = 0;
  $trans = 0;

  if($row['Outlet'] == "OU"){
    $empQuery2 = "SELECT * FROM uniqlo_1u WHERE Date>='".substr($row['Date'], 0, 10)." 00:00:00' AND Date<='".substr($row['Date'], 0, 10)." 23:59:59'";
    $empRecords2 = mysqli_query($db, $empQuery2);

    while($row2 = mysqli_fetch_assoc($empRecords2)) {
      if($row2['Door'] == 'in'){
        $count += (int)$row['Count'];
      }
    }
  }
  else if($row['Outlet'] == "DAS"){
    $empQuery2 = "SELECT * FROM uniqlo_DA WHERE Date>='".substr($row['Date'], 0, 10)." 00:00:00' AND Date<='".substr($row['Date'], 0, 10)." 23:59:59'";
    $empRecords2 = mysqli_query($db, $empQuery2);

    while($row2 = mysqli_fetch_assoc($empRecords2)) {
      if($row['Door'] == 'in'){
        $count += (int)$row['Count'];
      }
    }
  }

  if((int)$count != 0){
    $trans = number_format((float)(((int)$row['Transaction'] / $count) * 100), 2);
  }
  else{
    $trans = 0.00;
  }

  $data[] = array( 
    "Date"=>substr($row['Date'], 0, 10),
    "Transaction"=>$row['Transaction'],
    "Outlet"=>$row['Outlet'],
    "Visitors"=>$count,
    "Conversion"=>$trans
  );
}

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);

echo json_encode($response);

?>