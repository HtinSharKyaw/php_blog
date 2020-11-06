<?php 
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
}

if($_POST){
    $id = $_POST["hiddenId"];
    $title = $_POST['title'];
    $content = $_POST['content'];
    print "<pre>";
    print $id . $title.$content;

    if($_FILES['image']['name']){
        $target_dir = "../images/";//creating target dir
        $target_file = $target_dir.basename($_FILES['image']['name']);
        $image_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        if($image_type!="png" && $image_type!="jpg" && $image_type!="jpeg"){
            echo "<script>alert('we don't support your image type')</script>";
        }else{
           move_uploaded_file($_FILES['image']['tmp_name'],$target_file);
           $image = $_FILES['image']['name'];
           $stmt = $connection-> prepare("UPDATE posts SET title ='$title',content='$content',image='$image' WHERE id = '$id'");
           $result = $stmt->execute();
           if($result){
               echo "<script>alert('successfully updated');window.location.href = 'index.php'</script> ";
           }
        }
    }else{
        $stmt = $connection-> prepare("UPDATE posts SET title='$title',content='$content' WHERE id = $id");
        $result = $stmt->execute();
        if($result){
            echo "<script>alert('successfully updated');window.location.href = 'index.php'</script> ";    
        }
    }
}

    $stmt = $connection-> prepare("SELECT * FROM posts WHERE id = ".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    //    print "<pre>";
    //    print_r($result[0]['image']);

?>
<?php include ('header.html');?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" class="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="hiddenId" value="<?php echo $result[0]['id'] ?>">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea name="content" class="form-control" rows="5" columns="80"><?php echo $result[0]['content']?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><br>
                                <img src="../images/<?php echo $result[0]['image']?>" style="display:block;" width=100 height=150 alt=""><br>
                                <input type="file" name="image" value="" >
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
  