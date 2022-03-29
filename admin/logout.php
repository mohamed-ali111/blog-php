<?php

session_start();

unset($_SESSION['Admin']);//or destroy

header('Location:login.php');

exit();

?>