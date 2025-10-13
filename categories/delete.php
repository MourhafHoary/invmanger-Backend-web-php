<?php
include "../connect.php" ;
$cateid = filterRequest("cateid") ;
$imagename = filterRequest("imagename") ;

deleteFile("../uploads",$imagename) ;

deleteData("categories","categories_id= '$cateid' ");
?>