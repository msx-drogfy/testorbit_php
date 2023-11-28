<?php
global $conn;

$host = 'localhost'; // Host name
$username = 'u512781398_testorbit'; // MySQL username
$password = '@AllowMe21'; // MySQL password
$dbname = 'u512781398_testorbit'; // Database name

$conn = mysqli_connect($host, $username, $password, $dbname);
$nairobi = 10800;
$time = date_timestamp_get(date_create()) + $nairobi;


$mysqli = new mysqli($host, $username, $password, $dbname);

// $query = "SELECT * FROM exam_registration"; // Replace 'your_table_name' with your actual table name
// $result = mysqli_query($conn, $query);

// if (mysqli_num_rows($result) > 0) {
//     // Output data of each row
//     while($row = mysqli_fetch_assoc($result)) {
//         foreach($row as $columnName => $value) {
//             echo $columnName . ": " . $value . "<br>";
//         }
//         echo "<hr>"; // Just to separate each row for clarity
//     }
// } else {
//     echo "0 results";
// }
