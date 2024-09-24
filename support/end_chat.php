<?php 
session_start();
include("../database/connection.php");
$id=$_SESSION['id'];
$query="DELETE FROM messages WHERE id_sender=$id OR id_receiver=$id";
$result=mysqli_query($con,$query);
if($result){
header("location:../homepage/homepage.php");
}
?>