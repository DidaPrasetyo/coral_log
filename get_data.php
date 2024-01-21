<?php
// Connect to MySQL
// $conn = mysqli_connect("localhost", "root", "", "coral");
$conn = mysqli_connect("10.15.40.161", "coral", "", "coral");


// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Default values
$start = isset($_GET['start']) ? $_GET['start'] : 0; // Starting row number
$length = isset($_GET['length']) ? $_GET['length'] : 10; // Number of records per page

// Sorting
$orderColumn = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 0;
$orderDir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc';
$columns = array('id', 'detect_time', 'count', 'img');
$orderBy = $columns[$orderColumn];

// Search
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

// Your SQL query to fetch data with pagination and sorting
$sql = "SELECT id, detect_time AS timestamp, count AS `person detected`, img AS image FROM detection_log WHERE detect_time LIKE '%$searchValue%' OR count LIKE '%$searchValue%' ORDER BY $orderBy $orderDir LIMIT $start, $length";
$result = mysqli_query($conn, $sql);

// Fetch data and convert to JSON
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $imageData = base64_encode($row["image"]);
    $data[] = array(
        "timestamp" => $row["timestamp"],
        "person detected" => $row["person detected"],
        "image" => $imageData
    );
}

// Total records without filtering
$sqlTotal = "SELECT COUNT(*) as total FROM detection_log";
$resultTotal = mysqli_query($conn, $sqlTotal);
$totalRecords = mysqli_fetch_assoc($resultTotal)['total'];

// Total records with filtering
$sqlFiltered = "SELECT COUNT(*) as total FROM detection_log WHERE detect_time LIKE '%$searchValue%' OR count LIKE '%$searchValue%'";
$resultFiltered = mysqli_query($conn, $sqlFiltered);
$totalFilteredRecords = mysqli_fetch_assoc($resultFiltered)['total'];

$response = array(
    "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
);

header('Content-Type: application/json');
echo json_encode($response);

// Close the connection (moved after JSON response to ensure data is fetched)
mysqli_close($conn);
?>
