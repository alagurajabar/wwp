<?php
 echo "START\n";
 $c=mysqli_connect('localhost','root','','grocerry');
 $r=mysqli_query($c,'SELECT id,product_name,status,img1 FROM product');
 if(!$r){ echo "err:" . mysqli_error($c) . "\n"; exit(1);} 
 while($row=mysqli_fetch_assoc($r)){
  echo json_encode($row) . "\n";
 }
 echo "END\n";
?>
