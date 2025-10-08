<?php
include "../connect.php";

;
$email = filterRequest('email');
$password = sha1($_POST['password']);

getData("users","users_email = ? AND users_password = ? ",array($email,$password));

// $stmt = $con->prepare("SELECT * FROM users WHERE `email` = ? AND `password` = ?");
 
//  $stmt->execute(array($email,$pass));

//  $count = $stmt->rowCount() ;
//  if ($count>0){
//     echo json_encode(array("status" => "success")) ;
//  }else{
//     echo json_encode(array("status" => "faild")) ;
//  }




?>