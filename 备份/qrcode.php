<?php
include 'phpqrcode.php'; 
QRcode::png($_GET['url']);
?>