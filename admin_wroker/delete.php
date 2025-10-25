<?php  
include "../connect.php" ;
$workerid = filterRequest("workerid") ;
$image    = filterRequest("image") ;

deleteFile("../../uploads" , $image) ;
deleteData("workers" , "workers_id=$workerid") ;


?>