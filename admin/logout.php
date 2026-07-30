<?php

require_once '../classes/auth.php';

Auth::logout();

//redirect the logged out user
header("Location: login.php");
exit;

?>