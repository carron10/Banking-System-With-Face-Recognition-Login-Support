<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // Passing `true` enables exceptions

// Configure your email settings (e.g., SMTP server, username, password)
// $mail->SMTPDebug = 2; // Enable verbose debugging (optional)
$mail->isSMTP();
$mail->Host = 'mail.tekon.co.zw';
$mail->SMTPAuth = true;
$mail->Username = 'noreply@tekon.co.zw';
$mail->Password = 'unAHqC3fLqGeF4X';
$mail->SMTPSecure = 'tls'; // or 'ssl' depending on your server
$mail->Port = 587; // or 465 for SSL

function get_login_retrials($email)
{
  global $mysqli;

  $stmt = $mysqli->prepare("SELECT last_login_attempts FROM ib_clients WHERE email=?");
  if (!$stmt) {
    // Handle prepare statement error (e.g., log the error)
    return -1; // Or another indicator for error
  }

  // Sanitize email to prevent SQL injection
  $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

  $stmt->bind_param('s', $sanitized_email);
  $stmt->execute();
  $stmt->bind_result($last_login_attempts);
  $rs = $stmt->fetch();

  $stmt->free_result();
  $stmt->close(); // Close the statement

  if (!$rs) {
    return -2; // No attempt history found
  }

  return $last_login_attempts;
}

function update_login_retrials($email, $retrials = 0)
{
  global $mysqli;

  $stmt = $mysqli->prepare("UPDATE ib_clients SET last_login_attempts=?  WHERE email=?");

  // Bind parameters and execute the statement
  $stmt->bind_param('is', $retrials, $email);
  $stmt->execute();

  // Check the number of affected rows
  $affected_rows = $stmt->affected_rows;

 return $affected_rows > 0;
}
function check_if_face_login_enabled($user_id, $mysqli2 = null)
{
  global $mysqli;
  $mysqli_ = $mysqli2 == null ? $mysqli : $mysqli2;
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


function sendNewDeviceLoginEmail($user_email, $ip_address)
{
  global $mail;

  $subject = "New Login Detected on Your Account";
  $message = "We noticed a login to your account from a new device at IP address " . $ip_address . ". If you did not authorize this login, please secure your account immediately.";


  // Set email content
  $mail->setFrom('noreply@tekon.co.zw', 'NMB BANK');
  $mail->addAddress($user_email);
  $mail->Subject = $subject;
  $mail->Body = $message;

  if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
    return  false;
  } else {
    echo "Email sent successfully!";
    return true;
  }
}

function get_last_ip($email)
{
  global $mysqli;

  $stmt = $mysqli->prepare("SELECT last_login_ip FROM ib_clients WHERE email=?");
  if (!$stmt) {
    // Handle prepare statement error (e.g., log the error)
    return -1; // Or another indicator for error
  }

  // Sanitize email to prevent SQL injection
  $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

  $stmt->bind_param('s', $sanitized_email);
  $stmt->execute();
  $stmt->bind_result($last_login_ip);
  $rs = $stmt->fetch();

  $stmt->free_result();
  $stmt->close(); // Close the statement

  if (!$rs) {
    return -2; // No attempt history found
  }

  return $last_login_ip;
}

function update_ip_address($email,$ip)
{
  global $mysqli;

  $stmt = $mysqli->prepare("UPDATE ib_clients SET last_login_ip=?  WHERE email=?");

  // Bind parameters and execute the statement
  $stmt->bind_param('ss', $ip, $email);
  $stmt->execute();

  // Check the number of affected rows
  $affected_rows = $stmt->affected_rows;

 return $affected_rows > 0;
}

function get_last_agent($email)
{
  global $mysqli;

  $stmt = $mysqli->prepare("SELECT last_login_agent FROM ib_clients WHERE email=?");
  if (!$stmt) {
    // Handle prepare statement error (e.g., log the error)
    return -1; // Or another indicator for error
  }

  // Sanitize email to prevent SQL injection
  $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

  $stmt->bind_param('s', $sanitized_email);
  $stmt->execute();
  $stmt->bind_result($last_login_agent);
  $rs = $stmt->fetch();

  $stmt->free_result();
  $stmt->close(); // Close the statement

  if (!$rs) {
    return -2; // No attempt history found
  }

  return $last_login_agent;
}

function update_last_agent($email,$last_login_agent)
{
  global $mysqli;

  $stmt = $mysqli->prepare("UPDATE ib_clients SET last_login_agent=?  WHERE email=?");

  // Bind parameters and execute the statement
  $stmt->bind_param('ss', $last_login_agent, $email);
  $stmt->execute();

  // Check the number of affected rows
  $affected_rows = $stmt->affected_rows;

 return $affected_rows > 0;
}

function update_token($email,$acc_token)
{
  global $mysqli;

  $stmt = $mysqli->prepare("UPDATE ib_clients SET acc_token=?  WHERE email=?");

  // Bind parameters and execute the statement
  $stmt->bind_param('ss', $acc_token, $email);
  $stmt->execute();

  // Check the number of affected rows
  $affected_rows = $stmt->affected_rows;

 return $affected_rows > 0;
}
function get_user_agent() {
  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    return $_SERVER['HTTP_USER_AGENT'];
  } else {
    return "Unknown User Agent";
  }
}
function get_ip_address() {
  // Check for proxies and load balancers (consider order for your needs)
  if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  } else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
  }

  // Validate and sanitize the IP address (optional)
  // You can use filter_var($ip_address, FILTER_VALIDATE_IP) to validate

  return $ip_address;
}

function send_login_retrial_link($user_email, $link)
{
  global $mail;

  $subject = "Account ReActivation!!";
  $message = "You have incorrectly logged in for more many times, to secure your account
  we have temporal stooped logins, to reauth click $link, 
  If you did not authorize this login, please secure your account immediately.";


  // Set email content
  $mail->setFrom('noreply@tekon.co.zw', 'NMB BANK');
  $mail->addAddress($user_email);
  $mail->Subject = $subject;
  $mail->Body = $message;

  if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
    return  false;
  } else {
    echo "Email sent successfully!";
    return true;
  }
}