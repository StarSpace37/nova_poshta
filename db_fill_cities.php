<?php
ini_set('max_execution_time', 0);
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

// sql to drop table
$sql = "DROP TABLE Cities;";


if (mysqli_query($conn, $sql)) {
    echo "Table Cities dropped successfully ";
} else {
    echo "Error dropping table: " . mysqli_error($conn);
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS Cities(
id VARCHAR(36) PRIMARY KEY,
Description VARCHAR(50) NOT NULL,
DescriptionRu VARCHAR(50) NOT NULL,
Area VARCHAR(50) NOT NULL,
AreaDescription VARCHAR(50) NOT NULL,
AreaDescriptionRu VARCHAR(50) NOT NULL)";


if (mysqli_query($conn, $sql)) {
    echo "Table Cities created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// Find cities
// Find areas
$query_params = array(
    'modelName' => 'Address',
    'calledMethod' => 'getCities',
    'apiKey' => '06b1cd30823728efea50e681b485bd2a'
);
// A sample PHP Script to POST data using cURL
// Data in JSON format
$payload = json_encode($query_params);

// Prepare new cURL resource
$ch = curl_init('http://testapi.novaposhta.ua/v2.0/json/Address/getCities');
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
$sql_cities_insert = '';
$result = $result['data'];
$counter = 0;
$all_counter = 0;
$part = 400;

echo "<br><br>";
var_dump($result[0]);
echo "<br><br>";
// Iterate all items in array
foreach ($result as $value){
    $sql_cities_insert = 'INSERT INTO 
    Cities (id, Description, DescriptionRu, 
            Area, AreaDescription, AreaDescriptionRu)
    VALUES (
    "' . $value['Ref'] .'",
    "' . $value['Description'] . '",
    "' . $value['DescriptionRu'] . '",
    "' . $value['Area'] . '",
    "' . $value['AreaDescription'] . '",
    "' . $value['AreaDescriptionRu'] . '"
    );';
    //var_dump($value);
    //$areas = $areas . "<option value='{$value['DescriptionRu']}'>\n";
    // Execute insert areas query
    if (mysqli_query($conn, $sql_cities_insert)) {
        //echo "City inserted successfully";
    } else {
        echo "Error in inserting city: " . mysqli_error($conn);
    }
    if ($counter == $part) {
        echo "<br>Loaded: " . $all_counter * $part . " rows to DB";
        $counter = 0;
    } else {
        $counter += 1;
    }
}


// Close cURL session handle
curl_close($ch);

// Close mysql connection
mysqli_close($conn);
?>