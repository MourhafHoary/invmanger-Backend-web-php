<?php 
include "../connect.php" ;

$workerid = filterRequest("workerid") ;
$table = "worker" ;
getData($table,"worker_id= '$workerid' ") ;


?>