<?php
include "connect.php" ;
$proid = filterRequest("proid") ;
$image = filterRequest("image") ;

deleteFile("" , $image) ;
deleteData("products" , "products_id=$proid") ;
?>