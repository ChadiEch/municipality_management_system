<?php
session_start();
include("../database/connection.php");
$msg= trim($_POST['message']);
$id_sender=$_SESSION['id'];
$date=date("Y/m/d");

$id_receiver=$_GET['idr'];


 if(isset($msg)&& $msg!="" &&$msg!=" "){
 $query="INSERT INTO messages VALUES (NULL,'$msg','$id_sender','$id_receiver','$date')";
 $result=mysqli_query($con,$query);
 $query1= "SELECT * FROM messages WHERE id_sender=$id_sender";
 $result1=mysqli_query($con,$query1);
 if(mysqli_num_rows($result1)> 0){
    $row=mysqli_fetch_array($result1);
    $id_receiver=$row["id_receiver"];
 header("location:chat.php?receiver-id=$id_receiver");
 }
 else
 header("location:chat.php?receiver-id=$id_receiver");
 }
?>