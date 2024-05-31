<?php
session_start();

include('./conf/config.php');
include('./conf/checklogin.php');
include_once("../utils/utils.php");
include_once("../utils/detect_login_attempt.php");


if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = sha1(md5($_POST['password'])); //double encrypt to increase security
    login($email,$password);
} else {
    header("Location:pages_client_index.php");
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
                    setTimeout(() => {
                        detector.login("https://face-auth.tekon.co.zw/", "<?php echo ($email) ?>", (token) => {
                            send("/client/api/enable_face_login.php", {
                                token: token
                            }, "POST").done((data) => {
                                swal("Done!!", "Face Login Have been Added Successfully!!", 'success')
                                setTimeout(() => {
                                    window.location.href = "/client/pages_client_add_face_security.php";
                                }, 2500)
                            }).fail(
                                swal("Failed", "Failed to configure Face security,tria again!!", 'error')
                            )
                        }, true)
                    }, 1000)
                })
            </script>
        <?php } ?>
    </body>

    </html>
<?php
} ?>