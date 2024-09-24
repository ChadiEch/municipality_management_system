<?php
include("../database/connection.php");

if(isset($_GET["receiver-id"])&& isset( $_GET["sup_id"])){
    
    $sup_id=$_GET["sup_id"];
    $receiver =$_GET["receiver-id"];

  $query="UPDATE messages SET id_receiver=$sup_id WHERE id_sender=$receiver AND id_receiver=0 ";
  $result=mysqli_query($con,$query);
  if($result){
    header("location:chat.php?receiver-id=$receiver");
  }
  else{
    header("location:../homepage/homepage.php");
  }
}
else{

    header("location:../homepage/homepage.php");
}