<?php
require_once 'config.php';
require_once 'functions.php';

logoutUser();
header('Location: ../index.php');
exit;
?>