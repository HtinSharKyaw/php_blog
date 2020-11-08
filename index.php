<?php 
require 'config/config.php';
    session_start();
    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
        header('Location:login.php');
    }


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blog Views</title>
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
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0px;">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="col-sm-12">
                        <!-- <h1 style="text-align: center;">Widgets</h1> -->
                    </div>
                </div>
            </section>
            <!-- Content Wrapper. Contains page content -->
            <section class="content">
                <?php 
                    $selectStmt = $connection->prepare("SELECT * FROM posts");
                    $selectStmt->execute();
                    $selectResult = $selectStmt->fetchAll();
                    $totalPosts = count($selectResult);
                    if($_GET){
                        $pageNo = $_GET['pageNo'];
                    }else{
                        $pageNo = 1;
                    }
                
                    $numberOfRecords = 3;
                    $offset = ($pageNo - 1) * $numberOfRecords;
                    $totalPages = ceil(count($selectResult)/$numberOfRecords);
                    $stmt = $connection->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numberOfRecords");
                    $stmt->execute();
                    $finalResult = $stmt->fetchAll();
                ?>
                <div class="row">
                    <div class="card-group">
                        <?php 
                         foreach($finalResult as $value){
                        ?>
                            <div class="card">
                                <div class="card-header" style="text-align: center; float:none;"><?php echo $value['title']?></div>
                                <div class="card-body">
                                    <a href="blogdetail.php?id=<?php echo $value['id']?>">
                                    <img class="img-fluid pad card-img-top" src="dist/img/<?php echo $value['image'] ?>" alt="photo">
                                    </a><div><br></div>
                                    <h5 class="card-title"><?php echo $value['title']?></h5>
                                    <br>
                                    <p class="card-text"><?php echo $value['content']?></p>
                                   
                                </div>
                            </div>
                        <?php  
                         }
                        ?>
                    </div>
                </div>
                <br>
                <br>
                <div style="display: flex; flex-direction:row; justify-content: flex-end; margin-right: 10px;" >
                        <nav aria-label="Page navigation example" >
                            <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="?pageNo=1" >First</a> </li>
                            <li class="page-item <?php if($pageNo <= 1){ echo 'disabled';}?>">
                                <a class="page-link" href="<?php  
                                if($pageNo <= 1){
                                echo "#";
                                }else {
                                echo "?pageNo=".($pageNo-1);
                                }?>" >Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#" ><?php echo $pageNo;?></a></li>
                            <li class="page-item <?php if($pageNo == $totalPages) {
                                echo "disabled";
                                }?>"><a class="page-link" href="<?php 
                                if($pageNo>= $totalPages){
                                echo "#";
                                }else{
                                echo "?pageNo=".($pageNo+1);
                                }
                                ?>" >Next</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="?pageNo=<?php echo $totalPages?>" >Last</a></li>
                            </ul>
                        </nav>
                    </div>
            </section>
            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer" style="margin-left: 0px;">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.0.5
            </div>
            <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
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