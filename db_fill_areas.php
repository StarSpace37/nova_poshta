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

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS Areas (
id VARCHAR(36) PRIMARY KEY,
Description VARCHAR(50) NOT NULL,
DescriptionRu VARCHAR(50) NOT NULL,
AreasCenter VARCHAR(50) NOT NULL)";


if (mysqli_query($conn, $sql)) {
    echo "Table Areas created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// Find areas
$query_params = array(
    'modelName' => 'Address',
    'calledMethod' => 'getAreas',
    'apiKey' => '06b1cd30823728efea50e681b485bd2a'
);
// A sample PHP Script to POST data using cURL
// Data in JSON format


$payload = json_encode($query_params);

// Prepare new cURL resource
$ch = curl_init('http://testapi.novaposhta.ua/v2.0/json/Address/getAreas');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Set HTTP Header for POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);

// Submit the POST request
$result = curl_exec($ch);
// Decode json file
$result = json_decode($result, $associative=true);

while (array_key_exists("success", $result) != true){
    // Submit the POST request
    $result = curl_exec($ch);
    // Decode json file
    $result = json_decode($result, $associative=true);
}
$sql_areas_insert = '';
$result = $result['data'];
// Iterate all items in array
foreach ($result as $value){

    $sql_areas_insert = $sql_areas_insert . "INSERT INTO 
    Areas (id, Description, DescriptionRu, AreasCenter)
    VALUES (
    '{$value['Ref']}',
    '{$value['Description']}',
    '{$value['DescriptionRu']}',
    '{$value['AreasCenter']}');";
    //var_dump($value);
    //$areas = $areas . "<option value='{$value['DescriptionRu']}'>\n";
}
// Execute insert areas query
if (mysqli_multi_query($conn, $sql_areas_insert)) {
    echo "Areas inserted successfully";
} else {
    echo "Error in inserting areas: " . mysqli_error($conn);
}
// Close cURL session handle
curl_close($ch);

// Close mysql connection
mysqli_close($conn);
?>
