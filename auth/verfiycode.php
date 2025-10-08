<?php
include "../connect.php" ;

$email = filterRequest("email") ;
$verfiycode = filterRequest("verfiycode") ;
$stmt = $con -> prepare("SELECT * FROM users WHERE 	users_email = ? AND users_verfiycode = ?") ;
$stmt ->execute(array($email , $verfiycode)) ;
$count = $stmt->rowCount() ;

if($count > 0){
    $data = array(
        "users_approve" => "1"
    );
    updateData("users",$data,"users_email = '$email' ");
}else{
    printFailure("الرمز غير صحيح");
}

?>
