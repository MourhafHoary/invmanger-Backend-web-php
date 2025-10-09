<?php
include "../connect.php" ;
$email = filterRequest("email") ;
$verfiycode = rand(10000,99999) ;
$stmt = $con -> prepare("SELECT * FROM users WHERE users_email = ? And users_approve=1") ;
$stmt -> execute(array($email)) ;
$count = $stmt -> rowCount() ;

if($count>0) {
    $data = array("users_verfiycode"=> $verfiycode) ;
    updateData("users",$data," users_email = '$email' ",false) ;
}
result($count) ;
?>
