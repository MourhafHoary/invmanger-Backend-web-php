<?php
include "../connect.php" ;

$table = "products" ;
$name = filterRequest("name") ;
$desc = filterRequest("desc") ;
$quantity = filterRequest("quantity") ;
$price = filterRequest("price") ;
$image = imageUpload("../uploads" , "files") ;
$cateid = filterRequest("cateid") ;

$data = array(
    "products_name" => $name ,
    "products_desc" => $desc ,
    "products_quantity" => $quantity ,
    "products_price" => $price ,
    "products_image" => $image ,
    "products_catid" => $cateid ,
) ;
insertData($table,$data);
?>