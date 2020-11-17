<?php 
session_start();
require '../config/config.php';
require '../config/common.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
}

if($_SESSION['role']!=1){
    header('Location:login.php');
}


if($_POST){
    if (!hash_equals($_SESSION['_token'], $_POST['token'])) die();

    if(empty($_POST['title'])|| empty($_POST['content']) || empty($_FILES['image'])){
        echo "Hello error";
        if(empty($_POST['title'])){
            $titleError = "Title can not be null";
        }
        if(empty($_POST['content'])){
            $contentError = "Content can not be null";
        }
        if(empty($_FILES['image']['name'])){ 
            $imageError = "Image can not be null";
        }
    }else{
        $target_dir = "../dist/img/";//creating a target dir
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
            unset($_SESSION['_token']);
            if($result){
                echo "<script>alert('successfully data added')
                    window.location.href = 'index.php';
                </script>";
            }   
        }   
    }
}
?>


<?php include ('header.php');?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="add.php" class="" method="POST" enctype="multipart/form-data">
                            <input name="token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                            <div class="form-group">
                                <label for="">Title</label><p style="color:red"><?php echo empty($titleError)? '':$titleError; ?></p>
                                <input type="text" class="form-control" name="title" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Content</label><p style="color:red"><?php echo empty($contentError)? '':$contentError; ?></p>
                                <textarea name="content" class="form-control" rows="5" columns="80" required></textarea>
                            </div>
                            <div class="form-group"><p style="color:red"><?php echo empty($imageError)? '':$imageError; ?></p>
                                <label for="">Image</label>
                                <input type="file" name="image" value="" required>
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
  