<?php
session_start();
include('conf/config.php');

//register new account
if (!isset($_POST['create_account'])) {
  header("Location: /client/pages_client_signup.php");
  exit;
} else {
  try {
    $name = $_POST['name'];
    $national_id = $_POST['national_id'];
    $client_number = $_POST['client_number'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address  = $_POST['address'];

    #Check if email already in DB
    $stmt = $mysqli->prepare("SELECT email, client_id FROM iB_clients WHERE email=?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $rs = $stmt->get_result(); // Use get_result() instead of fetch()

    if ($rs->num_rows > 0) {
      $err = "Email Provided Already Exists, you will be redirected back!!"
?>
      <script>
        setTimeout(() => {
          window.location.href = "/client/pages_client_signup.php";
        }, 2000)
      </script>
  <?php
    }
  } catch (\Throwable $th) {
    $err = "Error Please Try Again Or Try Later";
  }
}
if (isset($_POST['create_account_step2'])) {
  $password = $_POST['password'];
  $confirmpassword = $_POST['confirm_password'];
  if ($confirmpassword != $password) {
    $err = "Password Doesn't match,comfirm passord!!";
  } else {

    $password = sha1(md5($_POST['password']));
    //$profile_pic  = $_FILES["profile_pic"]["name"];
    //move_uploaded_file($_FILES["profile_pic"]["tmp_name"],"dist/img/".$_FILES["profile_pic"]["name"]);

    //Insert Captured information to a database table
    $query = "INSERT INTO iB_clients (name, national_id, client_number, phone, email, password, address) VALUES (?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    //bind paramaters
    $rc = $stmt->bind_param('sssssss', $name, $national_id, $client_number, $phone, $email, $password, $address);
    $stmt->execute();

    //declare a varible which will be passed to alert function
    if ($stmt) {
      $success = "Account Created";
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}

/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `ib_systemsettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
  ?>
  <!DOCTYPE html>
  <html><!-- Log on to codeastro.com for more projects! -->
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <?php include("dist/_partials/head.php"); ?>

  <body class="hold-transition login-page" style="height: 90vh;">

    <link rel="stylesheet" href="dist/css/face_detectation.css">

    <div class="login-box">
      <div class="login-logo">
        <p>Step 2:Add Security</p>
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <form id="facial_sec_form" method="post">

            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>" />
            <input type="hidden" name="national_id" value="<?php echo htmlspecialchars($national_id); ?>" />
            <input type="hidden" name="client_number" value="<?php echo htmlspecialchars($client_number); ?>" />
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>" />
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>" />
            <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>" />



            <p class="login-box-msg">Secure your account with Facial Recognition and password</p>
            <div id="facial_container">
              <video id="video" style="display: none;" autoplay></video>
              <canvas id="videocanvas"></canvas>
              <canvas id="canvas"></canvas>
              <canvas id="canvas34" width="250" height="250"></canvas>
            </div>
            <div class="input-group mb-2 mt-2">
              <input type="password" name="password" required class="form-control rounded-0" placeholder="Password">
              <div class="input-group-append rounded-0">
                <div class="input-group-text rounded-0">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-2 mt-2">
              <input type="password" name="confirm_password" required class="form-control rounded-0" placeholder="Confirm Password">
              <div class="input-group-append rounded-0">
                <div class="input-group-text rounded-0">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>

            <div class="row  mt-3">
              <div class="col-8">
                <button type="button" class="btn btn-primary btn-sm">Back</button>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" name="create_account_step2" class="btn btn-success btn-block">Continue</button>
              </div>
              <!-- /.col -->
            </div>
            <div class=" dropdown-divider my-2"></div>
            <p class="mb-0">
              If you already have an account you can <a href="pages_client_index.php" class="text-center">Login</a>
            </p>
          </form>
        </div>
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
    <script src="dist/js/face_detection.js"></script>
    <script>
      $(function() {
        var detector = new FaceDetector()
        setTimeout(() => {
          detector.register2("//127.0.0.1:5000", "<?php echo ($email) ?>", (token) => {
            var m = $(document.createElement("input"))
            m.attr("name", 'token')
            m.val(token)
            $("#facial_sec_form").children().first().append(m)
          }, true)
        }, 1000)

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
  </body>

  </html>
<?php
} ?>