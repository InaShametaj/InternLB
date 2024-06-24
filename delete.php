<?php
global $conn;
include 'pdf/config.php';

if(isset ($_GET['id'])){
    $id=$_GET["id"];

    $sql="DELETE FROM persons WHERE id=$id";
    $conn->query($sql);
}

header("location: dashboard.php");
exit;
?>