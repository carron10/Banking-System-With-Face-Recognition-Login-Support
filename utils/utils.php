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
