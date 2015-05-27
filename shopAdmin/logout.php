<?php
// DESTROY SESSION AND REDIRECT USER TO THE LOGIN PAGE
session_start();
session_destroy();

header("location: admin_login.php");
?>
