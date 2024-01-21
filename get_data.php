<?php
// // Connect to MySQL
// $conn = mysqli_connect("localhost", "root", "", "coral");
// // $conn = mysqli_connect("10.15.40.161", "coral", "", "coral");


// // Check connection
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// // Fetch all BLOB data
// $sql = "SELECT * FROM detection_log
//         -- MobileNet v1
//         -- WHERE detect_time BETWEEN '2024-01-06 23:48:01' AND '2024-01-07 00:53:50'
//         -- MobileNet v2
//         -- WHERE detect_time BETWEEN '2024-01-07 00:53:50' AND '2024-01-07_01:55:52'
//         -- MobileDet
//         -- WHERE detect_time > '2024-01-07_01:55:52'
//         -- ORDER BY detect_time DESC
//         -- LIMIT 100"; // Modify the query based on your table structure
// $result = mysqli_query($conn, $sql);

// // Close the connection
// mysqli_close($conn);
?>

<?php
// Connect to MySQL
$conn = mysqli_connect("localhost", "root", "", "coral");

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
$columns = array('detect_time', 'count'); // Replace with your actual column names
$orderBy = $columns[$orderColumn];

// Search
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

// Your SQL query to fetch data with pagination and sorting
$sql = "SELECT id, detect_time AS timestamp, count AS `person detected`, img AS image FROM detection_log WHERE detect_time LIKE '%$searchValue%' OR count LIKE '%$searchValue%' ORDER BY $orderBy $orderDir LIMIT $start, $length";
$result = mysqli_query($conn, $sql);

// Fetch data and convert to JSON
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
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

echo json_encode($response);

// Close the connection (moved after JSON response to ensure data is fetched)
mysqli_close($conn);
?>
