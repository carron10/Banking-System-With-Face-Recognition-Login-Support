<?php
session_start();
include('../conf/config.php');
include('../conf/checklogin.php');
$email = $_SESSION['email'];
if (isset($email)) {
    $_SESSION['client_id'] = get_user_id($email);
} else {
    http_response_code(404);
    echo "Error";
}

// if (isset($_POST['token'])) {
//     $token = $_POST['token'];

//     // Prepare the SQL UPDATE statement
//     $stmt = $mysqli->prepare("UPDATE ib_clients SET face_login_token=? WHERE client_id=?");

//     // Bind parameters and execute the statement
//     $stmt->bind_param('ss', $token, $client_id);
//     $stmt->execute();

//     // Check the number of affected rows
//     $affected_rows = $stmt->affected_rows;

//     if ($affected_rows > 0) {
//         // If update was successful
//         echo "Done";
//     } else {
//         // If no rows were affected, the email provided might not exist in the table
//         http_response_code(404);
//         echo "Error";
//     }
// } else {
//     http_response_code(404);
//     echo "No token provided";
// }
