<?php 
  session_start();
  require 'config/config.php';
  require 'config/common.php';

    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
        header('Location:login.php');
    }

    $blogId = $_GET['id'];
    $stmt = $connection->prepare("SELECT * FROM posts WHERE id=".$blogId);
    $stmt->execute();
    $result = $stmt->fetchAll();
   

    if($_POST){
      if (!hash_equals($_SESSION['_token'], $_POST['token'])) die();
      if(empty($_POST['comment'])){
        $cmtError = "Please enter some comments";
      }else{
        unset($_SESSION['_token']);//That is for token deleting token and refreshing the token
        $stmt = $connection->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:authorId,:postId)");
        $result= $stmt->execute(
          array(
            ':content'=>$_POST['comment'],
            ':authorId'=> $_SESSION['user_id'],
            ':postId'=>$blogId
          )
        ); 

        if($result){
          header('Location: blogdetail.php?id='.$blogId);
        }

      }   

    }
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog Detail View</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
  <!-- Content Wrapper. Contains page content -->
  <div class="wrapper">
    <div class="content-wrapper" style="margin-left:0px">

      <!-- Content Wrapper. Contains page content -->
      <section class="content">
        <div class="content">
          <div class="row">
            <div class="col-md-12">
              <!-- Box Comment -->
              <div class="card card-widget">
                <div class="card-header">
                  <div class="card-header" style="text-align: center; float:none;"><?php echo escape($result[0]['title']) ?>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div style="text-align: center;">
                    <img class="img-fluid pad" src="dist/img/<?php echo $result[0]['image']?>"
                      style="width:450px;height:300px;" alt="Photo">
                  </div>
                  <br>
                  <p style="text-align: center;"><?php echo escape($result[0]['content'])?></p>
                  <div class="float-right d-none d-sm-inline">
                  <a href="index.php" type="button" class="btn btn-primary">Go Back</a>
                  </div>
                  <h4>Comments</h4>
               
                </div>
               
                <!-- /.card-body -->

                <hr>
                <div class="card-footer card-comments">
              
                  <!-- This is php code for looping comments in the comment section -->
                  <?php 
                  //Getting all the post concerned with the blogId
                    $stmtPosts = $connection->prepare("SELECT * FROM comments WHERE post_id=".$blogId);
                    $stmtPosts->execute();
                    $resultPosts = $stmtPosts->fetchAll();
                    if($resultPosts){
                      foreach($resultPosts as $value){
                        //This code is for extracting the related name based on the author_id
                        $stmtUserName = $connection->prepare("SELECT * FROM users WHERE id=".$value['author_id']);
                        $stmtUserName->execute();
                        $result = $stmtUserName->fetch(PDO::FETCH_ASSOC);
                 
               
                  ?>
                  <div class="card-comment">
                    <div class="comment-text" style="margin-left: 0px;">

                      <span class="username">
                        <?php echo $result['name'] ?>
                        <span class="text-muted float-right"><?php echo $value['created_at'] ?></span>
                      </span><!-- /.username -->
                      <?php echo escape($value['content']) ?>
                    </div>
                    <!-- /.comment-text -->
                  </div>
                  <?php 
                     }
                    }?>
                </div>

                <!-- /.card-footer -->
                <div class="card-footer">
                  <form action="" method="post">
                  <input name="token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push"><p style="color:red;"><?php echo empty($cmtError)? '':$cmtError ?></p>
                      <input type="text" class="form-control form-control-sm" placeholder="Press enter to post comment"
                        name="comment">
                    </div>
                  </form>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->

          </div>
      </section>
      <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer" style="margin-left:0px;">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        <a href="logout.php" type="button" class="btn btn-danger">Logout</a>
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2020 <a href="#">Blog app</a>.</strong> All rights reserved.
    </footer>
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
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
</body>

</html>