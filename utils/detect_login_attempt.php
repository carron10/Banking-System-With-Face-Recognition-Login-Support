<?php

include_once(__DIR__ . '/../client/conf/config.php');
$client_id = null;

function validateLoginCredentials($email, $password)
{
    global $mysqli, $client_id;
    $stmt = $mysqli->prepare("SELECT email, password, client_id  FROM ib_clients   WHERE email=? AND password=?"); //sql to log in user
    $stmt->bind_param('ss', $email, $password); //bind fetched parameters
    $stmt->execute(); //execute bind
    $stmt->bind_result($email, $password, $client_id); //bind result
    $rs = $stmt->fetch();
    // Free the result set
    $stmt->free_result();


    return $rs;
}
function login($email, $password)
{
    global $err, $client_id;
    $max_attempts = 3;  // Set the maximum allowed login attempts

    $r = get_login_retrials($email);
    if ($r > $max_attempts) {
?>
        <script>
            setTimeout(() => {
                swal("Login Failed!!", "You have reached your maximum login attempts!!", 'error')
                window.location.href = "/client/pages_client_index.php";
            }, 2000)
        </script>
        <?php
        exit();
    }
    // Login validation logic here (replace with your authentication code)
    if (validateLoginCredentials($email, $password)) {
        // Login successful, reset attempts
        update_login_retrials($email, 0);

        $ip = get_ip_address();
        $agent = get_user_agent();
        $last_ip = get_last_ip($email);
        $last_agent = get_last_agent($email);

        if ($last_agent != -1 && $last_agent != -2) {
            if ($last_agent != $agent) {
                sendNewDeviceLoginEmail($email, $ip);
                update_last_agent($email, $agent);
                update_ip_address($email, $ip);
            }
        }
        $_SESSION['email'] = $email;
        if (!check_if_face_login_enabled($client_id)) {
            $_SESSION['email'] = $email;
            $_SESSION['client_id'] = $client_id; //assaign session toc lient id
            header("location:pages_dashboard.php");
        }
    } else {

        update_login_retrials($email, $r);
        if ($r >= $max_attempts) {
            $uuid = uniqid("", true);
            update_token($email,$uuid);
            $user_email = $email;  // Replace with actual data retrieval
            send_login_retrial_link($user_email, $_SERVER['REMOTE_ADDR']."/client/api/activate_account.php?token=$uuid");
            // Optionally, lock the account or display a message to the user
            $err = "Your account have been temporary, you have reached the number of trials, Please check your email, we have sent your the activation code";
        ?>
            <script>
                setTimeout(() => {
                    window.location.href = "/client/pages_client_index.php";
                }, 2000)
            </script>
        <?php
        } else {
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
}
