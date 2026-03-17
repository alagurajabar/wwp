

<?php require('require/top.php'); ?>

 <section class="hero-banner defaultPadding">
    <div class="container">
        <div class="row hero-grid">

            <!-- LEFT BIG BANNER -->
            <div class="hero-left">
                <img src="assets/images/banner/banner-main.jpg" alt="">
                <div class="hero-content">
                    <span>WORLD WINDOW PUBLISHING</span>
                    <h2>Elevate Your <br> Reading Experience</h2>
                    <a href="#" class="btn-main">Visit Bookstore</a>
                    <a href="contact.php" class="btn-main btn-secondary">Contact Us</a>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="hero-right">

                <div class="hero-small">
                    <img src="assets/images/banner/banner1.jpg" alt="">
                    <div class="hero-text">
                        <span>IN BAHRAIN</span>
                        <h3>Your Ultimate Multivendor Bookstore</h3>
                        <a href="#">Know More</a>
                    </div>
                </div>

                <div class="hero-row">

                    <div class="hero-small">
                        <img src="assets/images/banner/banner2.jpg" alt="">
                        <div class="hero-text">
                            <span>NEW AUTHORS</span>
                            <h4>Calling New Authors</h4>
                        </div>
                    </div>

                    <div class="hero-small">
                        <img src="assets/images/banner/banner3.jpg" alt="">
                        <div class="hero-text">
                            <span>FOR AUTHOR</span>
                            <h4>Dashboard</h4>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>

<section class="defaultPadding mt4 author-values">
    <div class="container mrlAuto">
        <div class="heading">
            <span>CREATE. PUBLISH. GROW.</span>
            <h2>Our Author-First Values</h2>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="icon"><i class="uil uil-rocket"></i></div>
                <h3>Fast & Simple</h3>
                <p class="subtitle">"No Hassle, Just Hustle"</p>
                <p class="desc">Publish your masterpiece in just a few clicks with our streamlined tools and lightning-fast processing.</p>
            </div>
            <div class="value-card">
                <div class="icon"><i class="uil uil-brush-alt"></i></div>
                <h3>Creative Freedom</h3>
                <p class="subtitle">"Your Story, Your Rules"</p>
                <p class="desc">From cover design to pricing, you're in charge. No compromises, no limits—just your vision, your way.</p>
            </div>
            <div class="value-card">
                <div class="icon"><i class="uil uil-globe"></i></div>
                <h3>Global Reach</h3>
                <p class="subtitle">"Go Viral Beyond Socials"</p>
                <p class="desc">Get your book into the hands of readers worldwide and expand your fanbase beyond borders.</p>
            </div>
            <div class="value-card">
                <div class="icon"><i class="uil uil-chart-line"></i></div>
                <h3>Real-Time Insights</h3>
                <p class="subtitle">"Know Your Numbers"</p>
                <p class="desc">Track your sales, audience demographics, and performance with live analytics designed to help you grow.</p>
            </div>
            <div class="value-card">
                <div class="icon"><i class="uil uil-cog"></i></div>
                <h3>Total Control</h3>
                <p class="subtitle">"No Middlemen, No Compromises"</p>
                <p class="desc">You're the boss. Edit, update, and manage your book anytime with full backend access and zero gatekeeping.</p>
            </div>
            <div class="value-card">
                <div class="icon"><i class="uil uil-trophy"></i></div>
                <h3>Exclusive Perks</h3>
                <p class="subtitle">"Be Seen, Be Heard"</p>
                <p class="desc">Get featured in trending sections, spotlight campaigns, and social media promotions that elevate your voice.</p>
            </div>
        </div>
    </div>
</section>

<section class="defaultPadding mt4">
    <div class="container mrlAuto">
        <div class="heading">
            <span>Shop By</span>
            <h2>Categories</h2>
        </div>
        <div class="row mt3 ct-row">
            <div class="owl-carousel owl-theme cate-slider">
                <?php
                $res = mysqli_query($con, "select * from categories");
                while ($row = mysqli_fetch_assoc($res)) {
                ?>
                    <div class="item">
                        <a class="category-Item" href="view.php?n=<?php echo $row['id'] ?>&k=">
                            <div class="cate-img">
                                <img src="assets/images/svg/icon-7.svg" alt="" />
                            </div>
                            <h4><?php
                                echo $row['category'];
                                ?></h4>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php
if (isset($_GET['utm_source']) || isset($_SESSION['utm_source'])) {
    $s = '';
    if (isset($_SESSION['utm_source']) && !isset($_GET['utm_source'])) {
        $s = $_SESSION['utm_source'];
    } else {
        $s = $_GET['utm_source'];
    }
    verify_source($con, $s);
    $featured = get_featured_products($con);
    //  prx($featured);
?>
    <section class="defaultPadding mt4">
        <div class="container mrlAuto">
            <div class="heading">
                <span>For You</span>
                <h2>Top Featured Products</h2>
            </div>
            <div class="row mt3 ct-row">
                <div class="owl-carousel owl-theme product-slider">
                    <?php
                    if (count($featured) > 0) {
                        foreach ($featured as $product) {
                    ?>
                            <div class="item">
                                <div class="productBox">
                                    <a href="javascript:void(0)" class="product-image">
                                        <img src="media/product/<?php echo $product['img1']; ?>" alt="product" />
                                        <div class="topOption">
                                            <span class="offer"><?php
                                                                $offn = ($product['fa'] * 100) / $product['price'];
                                                                $off = round(100 - $offn);
                                                                echo $off . '%';
                                                                ?></span>
                                            <?php
                                            if (!isset($_SESSION['USER_LOGIN'])) {
                                            ?>
                                                <span class="wishlist" onclick="addwish(<?php echo $product['id']; ?>)">
                                                    <i class="uil uil-heart"></i>
                                                </span>
                                                <?php
                                            } else {
                                                $pid = $product['id'];
                                                $uid = $_SESSION['USER_ID'];
                                                $n = mysqli_num_rows(mysqli_query($con, "select * from wishlist where u_id='$uid' and p_id='$pid'"));
                                                if ($n > 0) {
                                                ?>
                                                    <span class="wishlist" onclick="gowish()">
                                                        <i class="uil uil-heart"></i>
                                                    </span>
                                                <?php
                                                } else {
                                                ?>
                                                    <span class="wishlist" onclick="addwish(<?php echo $product['id']; ?>)">
                                                        <i class="uil uil-heart"></i>
                                                    </span>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </a>
                                    <div class="product-detail">
                                        <p><?php
                                            if ($product['qty'] > 0) {
                                                echo "Available(In Stock)";
                                            } else {
                                                echo "Unavailable(Out of Stock)";
                                            } ?></p>
                                        <h4 style="cursor:pointer" onclick="control.redirect('product_detail.php?pid=<?php echo $product['id'] ?>')"><?php echo $product['product_name']; ?></h4>
                                        <div class="price">&#8377;<?php echo $product['fa']; ?>
                                            <span>&#8377;<?php echo $product['price']; ?></span>
                                        </div>
                                        <div class="cartqt">
                                            <?php

                                            if (!isset($_SESSION['USER_LOGIN'])) {
                                                if (isset($_SESSION['USER_CART'])) {
                                                    if (in_array($product['id'], $_SESSION['USER_CART'])) {
                                                        $index = array_search($product['id'], $_SESSION['USER_CART']);
                                            ?>
                                                        <div class="quantity buttons_added">
                                                            <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                            <input type="number" name="quantity" value="<?php echo $_SESSION['CART_QTY'][$index]; ?>" class="qty-text" />
                                                            <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                        </div>
                                                        <div class="ct-icon" onclick="go_to_cart()">
                                                            <i class="uil uil-shopping-cart-alt"></i>
                                                        </div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="quantity buttons_added">
                                                            <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                            <input type="number" name="quantity" value="1" class="qty-text" />
                                                            <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                        </div>
                                                        <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                            <i class="uil uil-shopping-cart-alt"></i>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="1" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>
                                                <?php
                                                }
                                            } else {
                                                $p_idd = $product['id'];
                                                $u_id = $_SESSION['USER_ID'];
                                                $query = "select cart.u_id,cart_detail.qty from cart,cart_detail where cart.u_id='$u_id' and cart_detail.p_id='$p_idd' and cart_detail.cart_id=cart.id";
                                                $rs = mysqli_query($con, $query);
                                                $i = mysqli_num_rows($rs);
                                                if ($i > 0) {
                                                    $g = mysqli_fetch_assoc($rs);
                                                ?>

                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="<?php echo $g['qty'] ?>" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="go_to_cart()">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>

                                                <?php
                                                } else {
                                                ?>
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="1" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="defaultPadding mt4">
        <div class="container mrlAuto">
            <div class="heading">
                <span>Offers</span>
                <h2>Best Values</h2>
            </div>
            <div class="row mt3 ct-row banner-row">
                <div class="row1">
                    <div class="ban">
                        <a href="#">
                            <img src="assets/images/banner/offer-1.jpg" alt="banner1" />
                        </a>
                    </div>
                    <div class="ban">
                        <a href="#">
                            <img src="assets/images/banner/offer-2.jpg" alt="banner1" />
                        </a>
                    </div>
                    <div class="ban">
                        <a href="#">
                            <img src="assets/images/banner/offer-3.jpg" alt="banner1" />
                        </a>
                    </div>
                </div>
                <div class="row1">
                    <a href="#" class="long-banner">
                        <img src="assets/images/banner/offer-4.jpg" alt="banner1" />
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="defaultPadding mt4">
        <div class="container mrlAuto">
            <div class="heading">
                <span>For You</span>
                <h2> Fresh Products</h2>
            </div>
            <div class="row mt3 ct-row">
                <div class="owl-carousel owl-theme product-slider">
                    <?php
                    if (count($featured) > 0) {
                        foreach ($featured as $product) {
                    ?>
                            <div class="item">
                                <div class="productBox">
                                    <a href="javascript:void(0)" class="product-image">
                                        <img src="media/product/<?php echo $product['img1']; ?>" alt="product" />
                                        <div class="topOption">
                                            <span class="offer"><?php
                                                                $offn = ($product['fa'] * 100) / $product['price'];
                                                                $off = round(100 - $offn);
                                                                echo $off . '%';
                                                                ?></span>
                                            <?php
                                            if (!isset($_SESSION['USER_LOGIN'])) {
                                            ?>
                                                <span class="wishlist" onclick="addwish(<?php echo $product['id']; ?>)">
                                                    <i class="uil uil-heart"></i>
                                                </span>
                                                <?php
                                            } else {
                                                $pid = $product['id'];
                                                $uid = $_SESSION['USER_ID'];
                                                $n = mysqli_num_rows(mysqli_query($con, "select * from wishlist where u_id='$uid' and p_id='$pid'"));
                                                if ($n > 0) {
                                                ?>
                                                    <span class="wishlist" onclick="gowish()">
                                                        <i class="uil uil-heart"></i>
                                                    </span>
                                                <?php
                                                } else {
                                                ?>
                                                    <span class="wishlist" onclick="addwish(<?php echo $product['id']; ?>)">
                                                        <i class="uil uil-heart"></i>
                                                    </span>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </a>
                                    <div class="product-detail">
                                        <p><?php
                                            if ($product['qty'] > 0) {
                                                echo "Available(In Stock)";
                                            } else {
                                                echo "Unavailable(Out of Stock)";
                                            } ?></p>
                                        <h4 style="cursor:pointer" onclick="control.redirect('product_detail.php?pid=<?php echo $product['id'] ?>')"><?php echo $product['product_name']; ?></h4>
                                        <div class="price">&#8377;<?php echo $product['fa']; ?>
                                            <span>&#8377;<?php echo $product['price']; ?></span>
                                        </div>
                                        <div class="cartqt">
                                            <?php

                                            if (!isset($_SESSION['USER_LOGIN'])) {
                                                if (isset($_SESSION['USER_CART'])) {
                                                    if (in_array($product['id'], $_SESSION['USER_CART'])) {
                                                        $index = array_search($product['id'], $_SESSION['USER_CART']);
                                            ?>
                                                        <div class="quantity buttons_added">
                                                            <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                            <input type="number" name="quantity" value="<?php echo $_SESSION['CART_QTY'][$index]; ?>" class="qty-text" />
                                                            <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                        </div>
                                                        <div class="ct-icon" onclick="go_to_cart()">
                                                            <i class="uil uil-shopping-cart-alt"></i>
                                                        </div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="quantity buttons_added">
                                                            <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                            <input type="number" name="quantity" value="1" class="qty-text" />
                                                            <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                        </div>
                                                        <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                            <i class="uil uil-shopping-cart-alt"></i>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="1" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>
                                                <?php
                                                }
                                            } else {
                                                $p_idd = $product['id'];
                                                $u_id = $_SESSION['USER_ID'];
                                                $query = "select cart.u_id,cart_detail.qty from cart,cart_detail where cart.u_id='$u_id' and cart_detail.p_id='$p_idd' and cart_detail.cart_id=cart.id";
                                                $rs = mysqli_query($con, $query);
                                                $i = mysqli_num_rows($rs);
                                                if ($i > 0) {
                                                    $g = mysqli_fetch_assoc($rs);
                                                ?>

                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="<?php echo $g['qty'] ?>" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="go_to_cart()">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>

                                                <?php
                                                } else {
                                                ?>
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="1" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="defaultPadding mt4">
        <div class="container mrlAuto">
            <div class="heading">
                <span>For You</span>
                <h2>New Products</h2>
            </div>
            <div class="row mt3 ct-row">
                <div class="owl-carousel owl-theme product-slider">
                    <?php
                    if (count($featured) > 0) {
                        foreach ($featured as $product) {
                    ?>
                            <div class="item">
                                <div class="productBox">
                                    <a href="javascript:void(0)" class="product-image">
                                        <img src="media/product/<?php echo $product['img1']; ?>" alt="product" />
                                        <div class="topOption">
                                            <span class="offer"><?php
                                                                $offn = ($product['fa'] * 100) / $product['price'];
                                                                $off = round(100 - $offn);
                                                                echo $off . '%';
                                                                ?></span>
                                            <?php
                                            if (!isset($_SESSION['USER_LOGIN'])) {
                                            ?>
                                                <span class="wishlist" onclick="addwish(<?php echo $product['id']; ?>)">
                                                    <i class="uil uil-heart"></i>
                                                </span>
                                                <?php
                                            } else {
                                                $pid = $product['id'];
                                                $uid = $_SESSION['USER_ID'];
                                                $n = mysqli_num_rows(mysqli_query($con, "select * from wishlist where u_id='$uid' and p_id='$pid'"));
                                                if ($n > 0) {
                                                ?>
                                                    <span class="wishlist" onclick="gowish()">
                                                        <i class="uil uil-heart"></i>
                                                    </span>
                                                <?php
                                                } else {
                                                ?>
                                                    <span class="wishlist" onclick="addwish(<?php echo $product['id']; ?>)">
                                                        <i class="uil uil-heart"></i>
                                                    </span>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </a>
                                    <div class="product-detail">
                                        <p><?php
                                            if ($product['qty'] > 0) {
                                                echo "Available(In Stock)";
                                            } else {
                                                echo "Unavailable(Out of Stock)";
                                            } ?></p>
                                        <h4 style="cursor:pointer" onclick="control.redirect('product_detail.php?pid=<?php echo $product['id'] ?>')"><?php echo $product['product_name']; ?></h4>
                                        <div class="price">&#8377;<?php echo $product['fa']; ?>
                                            <span>&#8377;<?php echo $product['price']; ?></span>
                                        </div>
                                        <div class="cartqt">
                                            <?php

                                            if (!isset($_SESSION['USER_LOGIN'])) {
                                                if (isset($_SESSION['USER_CART'])) {
                                                    if (in_array($product['id'], $_SESSION['USER_CART'])) {
                                                        $index = array_search($product['id'], $_SESSION['USER_CART']);
                                            ?>
                                                        <div class="quantity buttons_added">
                                                            <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                            <input type="number" name="quantity" value="<?php echo $_SESSION['CART_QTY'][$index]; ?>" class="qty-text" />
                                                            <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                        </div>
                                                        <div class="ct-icon" onclick="go_to_cart()">
                                                            <i class="uil uil-shopping-cart-alt"></i>
                                                        </div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="quantity buttons_added">
                                                            <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                            <input type="number" name="quantity" value="1" class="qty-text" />
                                                            <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                        </div>
                                                        <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                            <i class="uil uil-shopping-cart-alt"></i>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="1" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>
                                                <?php
                                                }
                                            } else {
                                                $p_idd = $product['id'];
                                                $u_id = $_SESSION['USER_ID'];
                                                $query = "select cart.u_id,cart_detail.qty from cart,cart_detail where cart.u_id='$u_id' and cart_detail.p_id='$p_idd' and cart_detail.cart_id=cart.id";
                                                $rs = mysqli_query($con, $query);
                                                $i = mysqli_num_rows($rs);
                                                if ($i > 0) {
                                                    $g = mysqli_fetch_assoc($rs);
                                                ?>

                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="<?php echo $g['qty'] ?>" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="go_to_cart()">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>

                                                <?php
                                                } else {
                                                ?>
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus minus-btn" onclick="decrement(this)" />
                                                        <input type="number" name="quantity" value="1" class="qty-text" />
                                                        <input type="button" value="+" class="plus plus-btn" onclick="increment(this)" />
                                                    </div>
                                                    <div class="ct-icon" onclick="add_cart(<?php echo $product['id']; ?>,this)">
                                                        <i class="uil uil-shopping-cart-alt"></i>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </section>
<?php
} else {

?>

    <section class="defaultPadding mt4">
        <div class="container mrlAuto">
            <div class="heading">
                <h2>Please select a location to browse products</h2>
            </div>
        </div>
    </section>
<?php

}
?>


<?php require('require/foot.php'); ?>
<?php require('require/csOwl.php'); ?>
<?php require('require/last.php'); ?>
