<?php
include "../connect.php" ;
$cateid = filterRequest("cateid") ;
$imagename = filterRequest("imagename") ;

deleteFile("",$imagename) ;

deleteData("categories","categories_id=$cateid")
?>