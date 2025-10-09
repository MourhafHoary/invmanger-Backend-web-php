<?php
include "../connect.php" ;
$table = "categories" ;

$catid = filterRequest("cateid") ;

$name = filterRequest("name") ;

$oldimage = filterRequest("oldimage") ;

$newimage = imageUpload("" ,"files") ;

if($newimage == "empty") {
    array(
        "categories_name" => $name
    );
}
    else {
        deleteFile("",$oldimage) ;
        $data = array(
            "categories_name" => $name ,
            "categories_image" => $newimage
        );
    }
updateData($table,$data,"categories_id=$catid") ;
