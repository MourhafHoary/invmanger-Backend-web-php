<?php 
include "../connect.php" ;

$table = "worker" ;

$name = filterRequest("name") ;
$email = filterRequest("email");
$image = filterRequest("image") ;
$newimage = imageUpload("../uploads" , "files") ;
$phone = filterRequest("phone") ;

if ($newimage == "fail") {
    $data = array(
        "worker_name" => $name ,
        "worker_email" => $email,
        "worker_image" => $image ,
        "worker_phone" => $phone ,
    );
} else {
    deleteFile("../uploads" , $image) ;
    $data = array(
        "worker_name" => $name ,
        "worker_email" => $email,
        "worker_image" => $newimage ,
        "worker_phone" => $phone ,
    );
}







?>