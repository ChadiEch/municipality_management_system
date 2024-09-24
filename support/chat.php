<?php
session_start();
include("../database/connection.php");
$id=$_SESSION['id'];
$query="SELECT * FROM messages WHERE id_sender=$id AND id_receiver IN (SELECT role FROM users WHERE role='4') ";
$result=mysqli_query($con,$query);
if(mysqli_num_rows($result)> 0){
    $row=mysqli_fetch_array($result);
    $idR=$row["id_receiver"];
header("location:chat.php?receiver-id=$idR");
}



if($_SESSION['islogedin'] != "1"){
      $id_receiver=$_GET['receiver-id'];
     header("location:../authantication/login.php?receiver='$id_receiver'");
    }


$id=$_SESSION['id'];
include("../database/connection.php");
if(isset($_GET['receiver-id'])){
    $id_receiver=$_GET['receiver-id'];
  
    }
    else{
        header("location:../homepage/homepage.php");
    }
if($id_receiver==0)    
$uname="Support";
else{
$query=" SELECT * FROM users WHERE id=$id_receiver";
$result=mysqli_query($con,$query);
$row=mysqli_fetch_array($result);
$uname=$row['fname']." ".$row['lname'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="chat.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
</head>

<script>
    //for auto refresh chat div
     $(document).ready(function(){
        setInterval(function(){
            $("#chat").load(window.location.href+" #chat");
        }, 3000);
     });

     
    // Scroll to the bottom of the chat container
    function scrollToBottom() {
        var chatContainer = document.getElementById("chat");
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Call the scrollToBottom function when the page is fully loaded
    window.addEventListener("load", scrollToBottom);
</script>

    
   

<body  onload="href='#bottom'">

  <nav class=navbar>
  <a href="../homepage/homepage.php"><img src="back.png" alt="back" srcset=""></a>
  <?php
  echo "<h2>$uname</h2>
  <a href='end_chat.php' class='end'>End Chat</a>"
  ?>

  </nav>
 

   
   
    <section>
    <div class="chat-container">
      <ul class="chat-messages">
        <div id="chat">
        <?php
$query="SELECT * FROM messages WHERE (id_sender=$id AND id_receiver=$id_receiver ) OR (id_sender=$id_receiver AND id_receiver=$id ) ";
$result=mysqli_query($con,$query);
$row=mysqli_fetch_array($result);
$num=mysqli_num_rows($result);
if($num>0){
do{
    
    if($id!=$row['id_sender']){
        
        echo "<li class='chat-message'><div class='r-message'> <p class='message-text'>".$row['content']."</p></div></li><br>";
    }
    else{
        echo "<li class='chat-message'><div class='s-message'><p class='message-text'>".$row['content']."</p></div></li><br>";
    }
}while($row=mysqli_fetch_array($result));
}
       echo" </div>";
        
       echo ' <form action="send.php?idr='.$id_receiver.'" method="post">';
       echo ' <input type="text" class="chat-input" placeholder="Type your message..." name="message" autocomplete="off">';
        echo '<button class="send-button" id="button">Send</button>';
        echo'</form>';
       
        ?>
        <p class='bottom' id='bottom'>
            
        </p>
     
    </section>
    <div class="footer">
  <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>

  </div>
</body>
</html>