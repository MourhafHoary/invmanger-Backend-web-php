<?php
include "../connect.php" ;

$table = "worker" ;

$workerid = filterRequest("workerid") ;
$name = filterRequest("name") ;
$email = filterRequest("email");
$image = filterRequest("imagename") ;
$newimage = imageUpload("../uploads" , "files") ;
$phone = filterRequest("phone") ;

if($newimage == "fail") {
    $data = array(
        "worker_name" => $name ,
        "worker_email" => $email,
        "worker_image" => $image ,
        "worker_phone" => $phone ,
    );
}
    else {
        deleteFile("../uploads" , $image) ;
        $data = array(
            "worker_name" => $name ,
            "worker_email" => $email,
            "worker_image" => $newimage ,
            "worker_phone" => $phone ,
        );
    }
    updateData($table,$data,"worker_id=$workerid") ;



?>