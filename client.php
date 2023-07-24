<?php
// database config
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "client_contacts";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// create_client
function generateClientCode($name) {
    $name = strtoupper($name);
    $name = str_pad($name, 3, 'A', STR_PAD_RIGHT);
    $query = "SELECT MAX(SUBSTRING(client_code, 4)) as max_code FROM clients WHERE client_code LIKE '$name%'";
    $result = mysqli_query($GLOBALS['conn'], $query);
    $row = mysqli_fetch_assoc($result);
    $numeric_part = $row['max_code'] ? (int)$row['max_code'] + 1 : 1;
    return $name . str_pad($numeric_part, 3, '0', STR_PAD_LEFT);
}

if (isset($_POST['client_name'])) {
    $client_name = $_POST['client_name'];
    $client_code = generateClientCode($client_name);

    $sql = "INSERT INTO clients (name, client_code) VALUES ('$client_name', '$client_code')";
    if (mysqli_query($conn, $sql)) {
        echo "Client created successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// create_contact
if (isset($_POST['contact_name']) && isset($_POST['contact_surname']) && isset($_POST['contact_email'])) {
    $contact_name = $_POST['contact_name'];
    $contact_surname = $_POST['contact_surname'];
    $contact_email = $_POST['contact_email'];

    $sql = "INSERT INTO contacts (name, surname, email) VALUES ('$contact_name', '$contact_surname', '$contact_email')";
    if (mysqli_query($conn, $sql)) {
        echo "Contact created successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
