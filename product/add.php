<?php
include "connect.php" ;
$table = "product" ;
$name = filterRequest("name") ;
$desc = filterRequest("desc") ;
$quantity = filterRequest("quantity") ;
$price = filterRequest("price") ;
$image = imageUpload("" , "files") ;
$cateid = filterRequest("cateid") ;

$data = array(
    "product_name" => $name ,
    "product_desc" => $desc ,
    "product_quantity" => $quantity ,
    "product_price" => $price ,
    "product_image" => $image ,
    "product_catid" => $cateid
) ;
insertData($table,$data);

?>