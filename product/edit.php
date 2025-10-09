<?php
include "connect.php" ;
$table  = "product" ;
$proid = filterRequest("proid") ;
$name = filterRequest("name") ;
$desc = filterRequest("desc") ;
$quantity = filterRequest("quantity") ;
$price = filterRequest("price") ;
$catid = filterRequest("cateid") ;
$oldimage = filterRequest("oldimage") ;
$newimage = imageUpload("" , "files") ;

if($newimage == "empty") {
    $data = array(
        "product_name" => $name ,
        "product_desc" => $desc ,
        "product_quantity" => $quantity ,
        "product_price" => $price ,
        "product_catid" => $catid
    );
}
    else {
        deleteFile("" , $oldimage) ;
        $data = array(
            "product_name" => $name ,
            "product_desc" => $desc ,
            "product_quantity" => $quantity ,
            "product_price" => $price ,
            "product_image" => $newimage ,
            "product_catid" => $catid
        );
    }
    updateData($table,$data,"product_id=$proid") ;

?>