<?php
define('HOST_NAME','localhost');
define('USER_NAME','root');
define('PASSWORD','');
define('DB_NAME','designers');

$conn=new mysqli(HOST_NAME,USER_NAME,PASSWORD,DB_NAME);

//check connection
if($conn -> connect_errno){
    echo 'Failed to connect to MYSQL =>'.$conn->connect_errno;
    exit();
}


?>