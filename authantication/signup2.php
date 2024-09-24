<?php
include("../database/connection.php");


if (isset($_POST["fname"],$_POST['lname'],$_POST['dob'],$_POST['email'],$_POST['password'],$_POST['p_number'],$_POST['address'])) {
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$dob=$_POST['dob'];
$email=$_POST['email'];
$password=$_POST['password'];
$p_number=$_POST['p_number'];
$address=$_POST['address']; 
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if(isset($_POST['role']) &&$_POST['role']=="employee"){  
            $query = "INSERT INTO users (fname, lname, phone_number, email, password, date_of_birth, address, role) 
            VALUES ('$fname', '$lname', '$p_number', '$email', '$password', '$dob', '$address', 2)";
                                   }
    elseif (isset($_POST['role']) &&$_POST['role']=="support"){  
            $query = "INSERT INTO users (fname, lname, phone_number, email, password, date_of_birth, address, role) 
            VALUES ('$fname', '$lname', '$p_number', '$email', '$password', '$dob', '$address', 4)";
                                   }
    else{

            $query = "INSERT INTO users (fname, lname, phone_number, email, password, date_of_birth, address, role) 
                        VALUES ('$fname', '$lname', '$p_number', '$email', '$password', '$dob', '$address', 3)";
                                   }
$result=mysqli_query($con,$query);
if($result){
    header("location: login.php?succ=1");
}
else{
    header("location:signup.php?err=1");
}

}
else{
    header("location: signup.php?err:2") ;
}

?>