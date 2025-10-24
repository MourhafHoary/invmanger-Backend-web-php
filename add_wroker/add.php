<?php
include "../connect.php" ;

$name = filterRequest("name") ; 
$email = filterRequest("email");
$password = sha1($_POST['password']);
$users_phone = filterRequest("phone");
$users_verfiycode  = 12345 ;
$users_approve  = 1  ;
$users_role = "worker";




$data = array(
    "users_name" => $name ,
    "users_email" => $email ,
    "users_password" => $password ,
    "users_phone" => $users_phone ,
    "users_verfiycode" => $users_verfiycode ,
    "users_approve" => $users_approve ,
    "users_role" => $users_role ,
);

insertData("users",$data) ;

?>