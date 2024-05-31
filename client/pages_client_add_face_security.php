<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
include_once("../utils/utils.php");

check_login();
$client_id = $_SESSION['client_id'];
$email = $_SESSION['email'];

?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <link rel="stylesheet" href="dist/css/face_detectation.css">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Face Security</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="pages_client_add_face_security.php">User</a></li>
                                <li class="breadcrumb-item active">Configure Face Security</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <?php if (check_if_face_login_enabled($client_id)) { ?>
                    <div class="container card ">

                        <div class="card-head mt-3">
                            <div class="card-title">Face security have been set already</div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" id="reconfigure">Re-Configure</button>
                        </div>

                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                Your security is our priority. To enhance the protection of your account, we recommend adding face login, on top of your password.
                                <br>
                                <a href="#" id="toggleInstructions">See Instructions</a>
                                <div id="instructions" style="display: none;">
                                    <br>
                                    To add Face Login:
                                    <ol>
                                        <li>Align your face with the camera</li>
                                        <li>Wait for the camera view to complete its progress (this may take 1-2 minutes)</li>
                                        <li>Logout and when you try login again, the feature will be enabled</li>
                                    </ol>
                                    If you have any questions or need assistance, feel free to reach out to our support team.
                                </div>
                            </div>
                            <div class="container card">
                                <div class="row">
                                    <div class="col-md-6 offset-md-3"> <!-- Use offset-md-3 to center the column -->
                                        <div class="login-box" style="margin: 0 auto;">
                                            <div class="">
                                                <div class="card-body login-card-body">
                                                    <div id="facial_container">
                                                        <!-- Your content goes here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                <?php } ?>
                <!-- Log on to codeastro.com for more projects! -->
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include("dist/_partials/footer.php"); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- page script -->
    <script src="dist/js/socket.io.min.js"></script>

    <script src="dist/js/face_detection.js"></script>
    <?php if (!check_if_face_login_enabled($client_id)) { ?>
        <script>
            $(function() {
                var detector = new FaceDetector()
                setTimeout(() => {
                    detector.register2("https://face-auth.tekon.co.zw/", "<?php echo ($email) ?>", (token) => {
                        send("/client/api/enable_face_register.php", {
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

        <script>
            $(document).ready(function() {
                $("#toggleInstructions").click(function() {
                    $("#instructions").toggle();
                    var buttonText = $(this).text() == "See Instructions" ? "Hide Instructions" : "See Instructions";
                    $(this).text(buttonText);
                    return false;
                });
            });
        </script>
    <?php } else { ?>
        <script>
            $(function() {
                $("#reconfigure").click(function() {
                    send("/client/api/reconfigure_face_login.php").done((data) => {
                        window.location.href = "/client/pages_client_add_face_security.php";
                    }).fail(
                        swal("Failed", "Failed to recofigure Face logging", 'error')
                    )
                })
            })
        </script>
    <?php } ?>
</body>

</html>