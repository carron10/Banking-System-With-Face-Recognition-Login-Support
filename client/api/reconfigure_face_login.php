<?php
session_start();
include('../conf/config.php');
include('../conf/checklogin.php');
$client_id = $_SESSION['client_id'];
$email=$_SESSION['email'];
// Make a GET request to a URL
$url = "http://tekon.eastus2.cloudapp.azure.com:5000/remove/".$email; // Replace with the URL you want to request
$response = file_get_contents($url);

// Check if the request was successful
if ($response === false) {
    // Handle error
    http_response_code(404);
    echo "Failed to delete images";
} else {
    // Prepare the SQL UPDATE statement
    $stmt = $mysqli->prepare("UPDATE ib_clients SET face_login_token=NULL WHERE client_id=?");

    // Bind parameters and execute the statement
    $stmt->bind_param('i', $client_id); // Assuming client_id is an integer, change 'i' to 's' if it's a string
    $stmt->execute();

    // Check the number of affected rows
    $affected_rows = $stmt->affected_rows;

    if ($affected_rows > 0) {
        // If update was successful
        echo "Done";
    } else {
        // If no rows were affected, the client_id provided might not exist in the table
        http_response_code(404);
        echo "Error";
    }
}
