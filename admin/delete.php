<?php
require '../config/config.php';
$connection->prepare("DELETE FROM posts WHERE id=".$_GET['id'])->execute();
echo "<script>alert('successfully deleted');window.location.href='index.php'</script>"
?>