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
$sql = "DROP TABLE Warehouses;";


if (mysqli_query($conn, $sql)) {
    echo "Table Warehouses dropped successfully ";
} else {
    echo "Error dropping table: " . mysqli_error($conn);
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS Warehouses (
id VARCHAR(36) PRIMARY KEY,
Description VARCHAR(50) NOT NULL,
DescriptionRu VARCHAR(50) NOT NULL,
Warehouse_Number INTEGER NOT NULL,
CityRef VARCHAR(50) NOT NULL,
CityDescription VARCHAR(50) NOT NULL,
CityDescriptionRu VARCHAR(50) NOT NULL)";


if (mysqli_query($conn, $sql)) {
    echo "Table Warehouses created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}


// Find areas
$query_params = array(
    'modelName' => 'AddressGeneral',
    'calledMethod' => 'getWarehouses',
    'apiKey' => '06b1cd30823728efea50e681b485bd2a'
);
// A sample PHP Script to POST data using cURL
// Data in JSON format


$payload = json_encode($query_params);

// Prepare new cURL resource
$ch = curl_init('http://testapi.novaposhta.ua/v2.0/json/AddressGeneral/getWarehouses');
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
$sql_warehouses_insert = '';
$result = $result['data'];
$counter = 0;
$all_counter = 0;
$part = 400;
// Iterate all items in array
foreach ($result as $value){

    $sql_warehouses_insert = "INSERT INTO 
    Warehouses (id, Description, DescriptionRu,
                Warehouse_Number, CityRef, CityDescription,
                CityDescriptionRu)
    VALUES (
    '" . $value['Ref'] ."',
    '" . str_replace("'", "''", $value['Description']) . "',
    '" . str_replace("'", "''", $value['DescriptionRu']) . "',
    " . $value['Number'] . ",
    '" . $value['CityRef'] . "',
    '" . str_replace("'", "''", $value['CityDescription']) . "',
    '" . str_replace("'", "''", $value['CityDescriptionRu']) . "'
    );";
    //var_dump($value);
    //$areas = $areas . "<option value='{$value['DescriptionRu']}'>\n";
    // Execute insert areas query
    if (mysqli_query($conn, $sql_warehouses_insert)) {
        //echo "City inserted successfully";
    } else {
        echo "<br>" . $sql_warehouses_insert . "<br>";
        echo "Error in inserting warehouse: " . mysqli_error($conn);
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
