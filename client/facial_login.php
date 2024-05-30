<?php
session_start();

include('./conf/config.php');
include('./conf/checklogin.php');
include_once("../utils/utils.php");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = sha1(md5($_POST['password'])); //double encrypt to increase security
    $stmt = $mysqli->prepare("SELECT email, password, client_id  FROM ib_clients   WHERE email=? AND password=?"); //sql to log in user
    $stmt->bind_param('ss', $email, $password); //bind fetched parameters
    $stmt->execute(); //execute bind
    $stmt->bind_result($email, $password, $client_id); //bind result
    $rs = $stmt->fetch();
    // Free the result set
    $stmt->free_result();

    //$uip=$_SERVER['REMOTE_ADDR'];
    //$ldate=date('d/m/Y h:i:s', time());
    if ($rs) { //if its sucessfull
        if (!check_if_face_login_enabled($client_id)) {
            $_SESSION['email'] = $email;
            $_SESSION['client_id'] = $client_id; //assaign session toc lient id
            header("location:pages_dashboard.php");
        }
    } else {
        #echo "<script>alert('Access Denied Please Check Your Credentials');</script>";
        $err = "Access Denied Please Check Your Credentials,you will be redirected back to login page";
    }
} else {
    // header("Location:pages_client_index.php");
}


/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `ib_systemsettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
    ?>
    <!DOCTYPE html>
    <html>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />

    <?php include("dist/_partials/head.php"); ?>

    <body class="hold-transition login-page">
        <link rel="stylesheet" href="dist/css/face_detectation.css">
        <div class="login-box">
            <!-- <div class="login-logo">
                <p><?php echo $auth->sys_name; ?></p>
            </div> -->
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Facial Auth required to proceed!!</p>
                    <form method="post" action="api/login.php">
                        <div id="facial_container">

                        </div>
                    </form>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
        <script src="dist/js/socket.io.min.js"></script>

        <?php if (check_if_face_login_enabled($client_id) && !isset($err)) { ?>
            <script src="dist/js/face_detection.js"></script>
            <script>
                $(function() {
                    var detector = new FaceDetector()

                    $("#facial_sec_form").submit((e) => {
                        // e.preventDefault()
                        console.log(getFormData("facial_sec_form"));
                        var form = document.getElementById("facial_sec_form")
                        if (form.checkValidity() == true) {
                            if ($("[name='token']").val() == null) {
                                e.preventDefault()
                                swal("Failed", "Please Complete Face Configuration", "error");
                                $("#facial_sec_form").addClass("was-validated")
                            }
                        } else {
                            e.preventDefault()
                            $("#facial_sec_form").addClass("was-validated")
                        }
                    })

                })
            </script>
        <?php } ?>
    </body>

    </html>
<?php
} ?>

