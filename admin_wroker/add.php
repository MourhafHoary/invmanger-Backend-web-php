<?php
include "../connect.php" ;

$table = "worker" ;

$name = filterRequest("name") ; 
$email = filterRequest("email");
$password = sha1($_POST['password']);
$status = filterRequest("status");
$image = imageUpload("../uploads" , "files") ;
$phone = filterRequest("phone") ;



$data = array(
    "worker_name" => $name ,
    "worker_email" => $email,
    "worker_password" => $password,
    "worker_status" => $status,
    "worker_image" => $image ,
    "worker_phone" => $phone ,
) ;

insertData($table, $data) ;

?>