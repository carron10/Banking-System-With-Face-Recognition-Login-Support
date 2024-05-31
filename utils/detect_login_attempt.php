<?php

include_once(__DIR__ . '/../client/conf/config.php');
include_once(__DIR__ . '/utils.php');

$client_id = null;

function validateLoginCredentials(string $email, string $password): bool
{
    global $mysqli, $client_id; // Assuming a global connection is necessary

    if (!$mysqli instanceof mysqli) {
        throw new InvalidArgumentException('Invalid database connection provided.');
    }

    try {
        $stmt = $mysqli->prepare("SELECT email, password,client_id FROM ib_clients WHERE email=?");
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare statement: ' . $mysqli->error);
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($db_email, $db_password_hash, $client_id);
        $stmt->fetch();

        if (!$db_email) {
            return false; // No matching email found
        }

        $passwordMatches = password_verify($password, $db_password_hash);

        $stmt->close(); // Close the statement
        return $passwordMatches;
    } catch (Exception $e) {
        // Log or handle the exception appropriately
        error_log('Login validation error: ' . $e->getMessage());
        return false;
    }
}



function login($email, $password)
{
    global $err, $client_id;
    $max_attempts = 3;  // Set the maximum allowed login attempts
    $user_exist = check_if_user_exist($email);
    if ($user_exist) {
        $r = get_login_retrials($email);
        if ($r > $max_attempts) {
?>
            <script>
                setTimeout(() => {
                    swal({
                        title: 'Login Failed!!',
                        text: 'You have reached your maximum login attempts!!',
                        buttons: false,
                        icon: 'error',
                        allowOutsideClick:false
                    })
                    // window.location.href = "/client/pages_client_index.php";
                }, 1000)
            </script>
        <?php
            return false;
        }
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
        if ($user_exist) {
            update_login_retrials($email, $r + 1);
            if ($r >= $max_attempts) {
                $uuid = uniqid("", true);
                update_token($email, $uuid);
                $user_email = $email;  // Replace with actual data retrieval
                send_login_retrial_link($user_email, "https://tekon.co.zw/client/api/activate_account.php?token=$uuid");
                // Optionally, lock the account or display a message to the user
                display_error_alert("Account Login Attempt!!","Your account have been temporary banned, you have reached the number of trials, Please check your email, we have sent your the activation code,
                check your email or a reactivation link");
            } else {
                display_error_alert("Permision Denied!!","Access Denied Please Check Your Credentials,you will be redirected back to login page");
            }
        } else {
            display_error_alert("Permision Denied!!","Access Denied Please Check Your Credentials,you will be redirected back to login page");
        }
        ?>
        <script>
            setTimeout(() => {
                window.location.href = "/client/pages_client_index.php";
            }, 2000)
        </script>
<?php

    }
}
