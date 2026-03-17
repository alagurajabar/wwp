<?php
 echo "START\n";
 $c=mysqli_connect('localhost','root','','grocerry');
 if(!$c){ echo "connfail\n"; exit(1); }
 $r=mysqli_query($c,'SELECT id,product_name,status,img1,price,sell_price,qty FROM product LIMIT 5');
 if(!$r){ echo "err:" . mysqli_error($c) . "\n"; exit(1);} 
 while($row=mysqli_fetch_assoc($r)){
  echo json_encode($row) . "\n";
 }
 echo "END\n";
?>
