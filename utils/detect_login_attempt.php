<?php

include_once(__DIR__.'/../client/conf/config.php');
$client_id=null;

function validateLoginCredentials($email, $password)
{
    global $mysqli,$client_id;
    $stmt = $mysqli->prepare("SELECT email, password, client_id  FROM ib_clients   WHERE email=? AND password=?"); //sql to log in user
    $stmt->bind_param('ss', $email, $password); //bind fetched parameters
    $stmt->execute(); //execute bind
    $stmt->bind_result($email, $password,$client_id); //bind result
    $rs = $stmt->fetch();
    // Free the result set
    $stmt->free_result();

    
    return $rs;
}
function login($email, $password)
{
    global $err,$client_id;
    $max_attempts = 3;  // Set the maximum allowed login attempts

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;  // Initialize attempts on first visit
    }

    // Login validation logic here (replace with your authentication code)
    if (validateLoginCredentials($email, $password)) {
        // Login successful, reset attempts
        $_SESSION['login_attempts'] = 0;
        if (!check_if_face_login_enabled($client_id)) {
            $_SESSION['email'] = $email;
            $_SESSION['client_id'] = $client_id; //assaign session toc lient id
            header("location:pages_dashboard.php");
        }
    } else {
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $user_email = $email;  // Replace with actual data retrieval
            sendNewDeviceLoginEmail($user_email, $_SERVER['REMOTE_ADDR']);
            // Optionally, lock the account or display a message to the user
        }
        #echo "<script>alert('Access Denied Please Check Your Credentials');</script>";
        $err = "Access Denied Please Check Your Credentials,you will be redirected back to login page";

?>
        <script>
            setTimeout(() => {
                window.location.href = "/client/pages_client_index.php";
            }, 2000)
        </script>
<?php

    }
}
