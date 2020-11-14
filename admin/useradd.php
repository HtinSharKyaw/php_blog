<?php 
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
}

if($_SESSION['role']!=1){
    header('Location:login.php');
}


if($_POST){
    // if(empty($_POST['title'])|| empty($_POST['content']) || empty($_POST['image'])){
    //     echo "Hello error";
    //     if(empty($_POST['title'])){
    //         $titleError = "Title can not be null";
    //     }
    //     if(empty($_POST['content'])){
    //         $contentError = "Content can not be null";
    //     }
    //     if(empty($_FILES['image'])){ 
    //         $imageError = "Image can not be null";
    //     }
    // }else{
        // $target_dir = "../dist/img/";//creating a target dir
        // $target_file = $target_dir.basename($_FILES["image"]["name"]);
        // $image_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        // if($image_type!="png" && $image_type!="jpg" && $image_type!="jpeg"){
        //     echo "<script>alert('we don't support your image type')</script>";
        // }else{
            // move_uploaded_file($_FILES['image']['tmp_name'],$target_file);
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = empty($_POST['role'])? 0:1;
            // print_r($role);

            $stmt = $connection->prepare("INSERT INTO users(name,password,email,role) VALUES (:name,:password,:email,:role)");
            $result = $stmt->execute(
                array(
                    ':name' => $name,
                    ':password' => $password,
                    ':email' => $email,
                    ':role' => $role
                )
            );
            if($result){
                echo "<script>alert('successfully data added')
                    window.location.href = 'userview.php';
                </script>";
            }
        }
   // }
// }
?>


<?php include ('header.php');?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="#" class="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" class="form-control" name="email" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" class="form-control" name="password" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="check">Admin</label>
                                <input type="checkbox" name="role" value="1" id="check">
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Submit ">
                                <a href="userview.php" type="button" class="btn btn-primary">Back</a> 
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
       </div>
<?php include ('footer.html')?>
  