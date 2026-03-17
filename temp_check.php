<?php
$c = mysqli_connect('localhost','root','','grocerry');
if (!$c) { echo "conn fail"; exit(1); }
$r = mysqli_query($c, 'SELECT COUNT(*) as c FROM product');
if (!$r) { echo 'qry fail: '.mysqli_error($c); exit(1); }
$row = mysqli_fetch_assoc($r);
echo 'count='.$row['c'];
?>
