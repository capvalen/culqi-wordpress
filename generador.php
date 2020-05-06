<?php 
require_once('phpqrcode/qrlib.php');

QRcode::png("http://ademperu.com/certificados/index.php?cliente={$_GET['cliente']}&curso={$_GET['curso']}");

?>