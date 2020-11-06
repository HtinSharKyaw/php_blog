<?php 
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
}


if($_POST){
    $target_dir = "../images/";//creating a target dir
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    $image_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if($image_type!="png" && $image_type!="jpg" && $image_type!="jpeg"){
        echo "<script>alert('we don't support your image type')</script>";
    }else{
        move_uploaded_file($_FILES['image']['tmp_name'],$target_file);
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_FILES['image']['name'];
        $authorId = $_SESSION['user_id'];
        $stmt = $connection->prepare("INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:authorId)");
        $result = $stmt->execute(
            array(
                ':title' => $title,
                ':content' => $content,
                ':image' => $image,
                ':authorId' => $authorId
            )
        );
    if($result){
        echo "<script>alert('successfully data added')
            window.location.href = 'index.php';
        </script>";
    }
    }
}
?>


<?php include ('header.html');?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="add.php" class="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea name="content" class="form-control" rows="5" columns="80"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><br>
                                <input type="file" name="image" value="" required >
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Submit ">
                                <a href="index.php" type="button" class="btn btn-primary">Back</a>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       </div>
<?php include ('footer.html')?>
  