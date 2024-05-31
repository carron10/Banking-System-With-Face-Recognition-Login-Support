<?php

function check_if_face_login_enabled($user_id,$mysqli2=null)
{
    global $mysqli;
    $mysqli_=$mysqli2==null?$mysqli:$mysqli2;
    // Prepare SQL query with a placeholder for user_id
    $ret = "SELECT face_login_token FROM `ib_clients` WHERE client_id=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($ret);
    
    // Bind the user_id parameter to the statement
    $stmt->bind_param('s', $user_id);
    
    // Execute the statement
    $stmt->execute();
    
    // Get the result set
    $res = $stmt->get_result();
    
    // Fetch the first row from the result set
    $auth = $res->fetch_object();
    
    // Check if face_login_token is not null
    if ($auth && $auth->face_login_token !== null) {
        // If face_login_token is not null, return true
        return true;
    } else {
        // If face_login_token is null or no rows are returned, return false
        return false;
    }
}


function sendNewDeviceLoginEmail($user_email, $ip_address) {
  $subject = "New Login Detected on Your Account";
  $message = "We noticed a login to your account from a new device at IP address " . $ip_address . ". If you did not authorize this login, please secure your account immediately.";
  $headers = "From: Your Application Name <your_email@example.com>";

  // Replace with your actual email server details if necessary
  $sent = mail($user_email, $subject, $message, $headers);

  if ($sent) {
    echo "Email sent successfully!";
  } else {
    echo "Failed to send email!";
  }
}

