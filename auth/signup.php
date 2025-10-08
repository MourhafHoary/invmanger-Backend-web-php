<?php
include "../connect.php";

$username = filterRequest('username');
$password = sha1($_POST['password']);
$email = filterRequest('email');
$phone = filterRequest('phone');
$verfiycode = rand(10000,99999) ;

$stmt = $con->prepare("SELECT * FROM users WHERE users_name = ? AND users_phone = ?");
 
 $stmt->execute(array($username,$phone));

 $count = $stmt->rowCount() ;
 if ($count>0){
   printFailure("EMAIL OR PHONE") ;
 }else{
    $data = array(
      "users_name" => $username ,
      "users_password" => $password ,
      "users_email" => $email ,
      "users_phone" => $phone ,
      "users_verfiycode" => $verfiycode,
    );
    insertData("users",$data);
 } 




?>