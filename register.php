<?php 
session_start();
require 'config/config.php';
require 'config/common.php';
if($_POST){
  if (!hash_equals($_SESSION['_token'], $_POST['token'])) die();
  if(empty($_POST['name'])|| empty($_POST['email']) || empty($_POST['password']) || strlen(($_POST['password']))<=4){
    //     echo "Hello error";
    if(empty($_POST['name'])){
      $nameError = "Name can not be null";
    }
    if(empty($_POST['email'])){
        $emailError = "Email can not be null";
    }
    $passwordError = (empty($_POST['password']))? "Password should not be null ":"password length should be greater than 4"; 
  }else{
    unset($_SESSION['_token']);//That is for token deleting token and refreshing the token
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"],PASSWORD_DEFAULT);

    $stmt = $connection->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $hasAccount = $stmt->fetch(PDO::FETCH_ASSOC);
    if($hasAccount){
          // print_r($hasAccount);
      echo "<script>alert('This email is already used,Please try another one')</script>";
    }else{
      $insertStmt= $connection->prepare("INSERT INTO users(name,password,email,role) VALUES (:name,:password,:email,:role)");
      $insertResult =  $insertStmt->execute(
      array(
        ':name'=> $name,
        ':email'=> $email,
        ':password'=>$password,
        ':role'=>0
        )
      );
      if($insertResult){
        echo "<script>alert('Successfully Registered');
         window.location.href = 'login.php';
          </script>";   
      } 
    }  
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="pluginsfontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="pluginsicheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="../../index2.html"><b>Blog</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Register Account</p>

        <form action="register.php" method="post">
        <input name="token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
          <p style="color: red; font-size:  15px;"><?php echo empty($nameError)? '':$nameError?></p>
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Name" name="name" value="<?php if($_POST) echo empty($nameError)? $_POST['name']:''; ?>" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          
          <p style="color: red; font-size:  15px;"><?php echo empty($emailError)? '':$emailError?></p>
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" value="<?php if($_POST) echo empty($emailError)? $_POST['email']:'';?>" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>

          <p style="color: red; font-size: 15px;"><?php echo empty($passwordError)? '':$passwordError?></p>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" value="<?php if($_POST) echo empty($passwordError)? $_POST['password']:'';?>" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
              <a href="login.php" type="button" class="btn btn-default btn-block">Login</a>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <br>

        <p class="mb-0">
          <!-- <a href="register.html" class="text-center">Register a new membership</a> -->
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="pluginsjquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="pluginsbootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

</body>

</html>