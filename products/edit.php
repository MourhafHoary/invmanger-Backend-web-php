<?php
include "../connect.php" ;
$table  = "products" ;
$proid = filterRequest("proid") ;
$name = filterRequest("name") ;
$desc = filterRequest("desc") ;
$quantity = filterRequest("quantity") ;
$price = filterRequest("price") ;
$catid = filterRequest("cateid") ;
$imagename = filterRequest("imagename") ;
$newimage = imageUpload("../uploads" , "files") ;

if($newimage == "empty") {
    $data = array(
        "products_name" => $name ,
        "products_desc" => $desc ,
        "products_quantity" => $quantity ,
        "products_price" => $price ,
        "products_catid" => $catid
    );
}
    else {
        deleteFile("../uploads" , $imagename) ;
        $data = array(
            "products_name" => $name ,
            "products_desc" => $desc ,
            "products_quantity" => $quantity ,
            "products_price" => $price ,
            "products_image" => $newimage ,
            "products_catid" => $catid
        );
    }
    updateData($table,$data,"products_id=$proid") ;

?>