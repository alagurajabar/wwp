<div class="left-part" id="lft">
    <div class="logo">
        <a href="javascript:void(0)" class="logo-link">
            <span class="logo-text">WWP</span>
        </a>
        <div class="close-left-nav" onclick="close_res_nav()">
            <i class="uil uil-times"></i>
        </div>
    </div>
    <div class="list-nav">
        <ul class="nav-list">
            <?php 
                if(profile_completed($con)==1 && profle_verified($con)==1){
                    $currentPage = basename($_SERVER['PHP_SELF']);
            ?>
            <li class="outer-list<?php echo ($currentPage === 'index.php' ? ' active' : ''); ?>">
                <a href="index.php">
                    <i class="uil uil-estate"></i>
                    <span>Dashboard </span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'product.php' ? ' active' : ''); ?>">
                <a href="product.php">
                    <i class="uil uil-box"></i>
                    <span>Product</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'order_received.php' ? ' active' : ''); ?>">
                <a href="order_received.php">
                    <i class="uil uil-archive"></i>
                    <span>Order Received&nbsp;<span style="color:red;font-size:1.2rem;font-weight:700"></span></span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'order_assigned.php' ? ' active' : ''); ?>">
                <a href="order_assigned.php">
                    <i class="uil uil-parcel"></i>
                    <span>Order Assigned</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'outfordelivery.php' ? ' active' : ''); ?>">
              <a href="outfordelivery.php">
              <i class="uil uil-car-sideview"></i>
                <span>Out For Delivery</span>
              </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'delivery_c.php' ? ' active' : ''); ?>">
                <a href="delivery_c.php">
                    <i class="uil uil-voicemail-rectangle"></i>
                    <span>Delivery Confirmation</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'delivered.php' ? ' active' : ''); ?>">
                <a href="delivered.php">
                    <i class="uil uil-gift"></i>
                    <span>Delivered</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'issue.php' ? ' active' : ''); ?>">
                <a href="issue.php">
                    <i class="uil uil-toilet-paper"></i>
                    <span>Issue</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'order_setteled.php' ? ' active' : ''); ?>">
                <a href="order_setteled.php">
                    <i class="uil uil-bag"></i>
                    <span>Order Settled</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'undelivered_c.php' ? ' active' : ''); ?>">
                <a href="undelivered_c.php">
                    <i class="uil uil-channel"></i>
                    <span>Undelivered Confirmation</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'undelivered.php' ? ' active' : ''); ?>">
                <a href="undelivered.php">
                    <i class="uil uil-cube"></i>
                    <span>Undelivered</span>
                </a>
            </li>
            <li class="outer-list<?php echo ($currentPage === 'promo.php' ? ' active' : ''); ?>">
                <a href="promo.php">
                <i class="uil uil-no-entry"></i>
                    <span>Promo Code</span>
                </a>
            </li>
            <?php }else{  ?>

            <li class="outer-list<?php echo ($currentPage === 'index.php' ? ' active' : ''); ?>">
                <a href="index.php">
                    <i class="uil uil-estate"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php } ?>
        </ul>
    </div>
    <div class="copyright">
        <p>Developed by Dork Company </p>
    </div>
</div>
