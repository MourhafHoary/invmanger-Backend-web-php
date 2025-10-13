<?php
include "../connect.php" ;

$name = filterRequest("name") ; 
$section = filterRequest("section");

// Handle image upload with proper error checking
$imagename = imageUpload("../uploads" , "image") ;

$data = array(
    "categories_name" => $name ,
    "categories_image" => $imagename,
    "categories_section" => $section,
);

insertData("categories",$data) ;

?>