<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "nova_poshta";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$cities = array();
$sql = "SELECT DescriptionRu FROM Warehouses WHERE CityDescriptionRu='" . $_GET['city_name'] . "'";
$result = mysqli_query($conn, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Add cities to array
foreach ($rows as $row){
    array_push($cities, $row['DescriptionRu']);
}
// Close mysql connection
mysqli_close($conn);

// response json with cities
echo json_encode($cities);
?>