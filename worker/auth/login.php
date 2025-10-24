<?php
include "../../connect.php";


$email = filterRequest('email');
$password = sha1($_POST['password']);
// $password = filterRequest('password');

getData("users",
"users_email = ? AND
 users_password = ? And 
 users_role = ? And 
 users_approve = ? "
 ,array($email,$password, "worker", 1));


// $stmt = $con->prepare("SELECT * FROM users WHERE `email` = ? AND `password` = ?");
 
//  $stmt->execute(array($email,$pass));

//  $count = $stmt->rowCount() ;
//  if ($count>0){
//     echo json_encode(array("status" => "success")) ;
//  }else{
//     echo json_encode(array("status" => "faild")) ;
//  }




?>