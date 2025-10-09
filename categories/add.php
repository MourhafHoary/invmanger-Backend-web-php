<?php
include "../connect.php" ;
$name = filterRequest("name") ; 
$imagename = imageUpload("" , "files") ;
$section = filterRequest("section");

$data = array(
    "categories_name" => $name ,
    "categoties_image" => $imagename,
    "categories_section" => $section
);
insertData("categories",$data) ;

?>