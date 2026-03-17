<?php
require('require/top.php');
$cart = get_cart_products($con);
$total_subtotal = 0;
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
<style>
  *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
  :root {
    --ink:      #1a1208;
    --espresso: #3b2408;
    --amber:    #c8720a;
    --gold:     #e9a832;
    --cream:    #fdf6eb;
    --parchment:#f5ead6;
    --mist:     #ede4d3;
    --white:    #ffffff;
    --shadow:   0 8px 40px rgba(26,18,8,.12);
    --shadow-lg:0 20px 60px rgba(26,18,8,.18);
    --radius:   14px;
    --font-head:'Playfair Display', Georgia, serif;
    --font-body:'DM Sans', sans-serif;
  }
  body { font-family:var(--font-body); background:var(--cream); color:var(--ink); }
  a    { text-decoration:none; color:inherit; }

  @keyframes fadeUp  { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
  @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
  @keyframes pulse   { 0%,100%{transform:scale(1)} 50%{transform:scale(1.12)} }
  @keyframes bounceX { 0%,100%{transform:translateX(0)} 50%{transform:translateX(5px)} }

  /* ── NAVBAR ── */
  .navbar { position:sticky; top:0; z-index:100; background:rgba(253,246,235,.93); backdrop-filter:blur(14px); border-bottom:1px solid var(--mist); padding:15px 0; animation:fadeIn .6s ease; }
  .nav-inner { display:flex; align-items:center; justify-content:space-between; gap:20px; width:min(1280px,92%); margin:0 auto; }
  .nav-logo  { font-family:var(--font-head); font-size:1.35rem; font-weight:900; color:var(--espresso); }
  .nav-logo span { color:var(--amber); }
  .nav-links { display:flex; gap:28px; list-style:none; }
  .nav-links a { font-size:.88rem; font-weight:500; color:var(--espresso); position:relative; transition:color .25s; }
  .nav-links a::after { content:''; position:absolute; bottom:-3px; left:0; width:0; height:2px; background:var(--amber); border-radius:2px; transition:width .3s; }
  .nav-links a:hover { color:var(--amber); }
  .nav-links a:hover::after { width:100%; }
  .nav-actions { display:flex; align-items:center; gap:10px; }
  .nav-icon { width:38px; height:38px; display:flex; align-items:center; justify-content:center; border-radius:10px; background:var(--parchment); color:var(--espresso); font-size:1.1rem; cursor:pointer; transition:all .25s; position:relative; border:none; }
  .nav-icon:hover { background:var(--amber); color:var(--white); transform:translateY(-2px); }
  .nav-icon .badge { position:absolute; top:-5px; right:-5px; width:17px; height:17px; background:var(--amber); color:var(--white); font-size:.6rem; font-weight:700; border-radius:50%; display:flex; align-items:center; justify-content:center; animation:pulse 2s ease infinite; }
  .hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:4px; background:none; border:none; }
  .hamburger span { display:block; width:22px; height:2px; background:var(--espresso); border-radius:2px; transition:all .3s; }
  .mobile-menu { display:none; position:fixed; inset:0; top:68px; background:rgba(253,246,235,.97); backdrop-filter:blur(10px); z-index:99; padding:32px 24px; flex-direction:column; gap:18px; }
  .mobile-menu.open { display:flex; animation:fadeIn .25s ease; }
  .mobile-menu a { font-size:1.05rem; font-weight:600; color:var(--espresso); padding:8px 0; border-bottom:1px solid var(--mist); }

  /* ── BREADCRUMB ── */
  .path { background:var(--parchment); border-bottom:1px solid var(--mist); padding:12px 0; }
  .path .inner { width:min(1280px,92%); margin:0 auto; display:flex; align-items:center; gap:6px; font-size:.82rem; color:#7a5c36; }
  .path a { color:var(--espresso); font-weight:500; transition:color .2s; }
  .path a:hover { color:var(--amber); }
  .path .sep { color:var(--mist); }
  .path .cur { color:var(--amber); font-weight:600; }

  /* ── HERO ── */
  .cart-hero { background:linear-gradient(155deg,#1a0d03 0%,#4a2008 45%,#b86208 100%); padding:36px 0; position:relative; overflow:hidden; }
  .cart-hero::before { content:''; position:absolute; bottom:-60px; right:-60px; width:260px; height:260px; background:rgba(255,255,255,.04); border-radius:50%; pointer-events:none; }
  .cart-hero::after  { content:''; position:absolute; top:-50px; left:-50px; width:180px; height:180px; background:rgba(233,168,50,.07); border-radius:50%; pointer-events:none; }
  .cart-hero .inner { width:min(1280px,92%); margin:0 auto; position:relative; z-index:2; }
  .cart-hero-tag { display:inline-flex; align-items:center; gap:8px; font-size:.7rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--gold); margin-bottom:8px; }
  .cart-hero-tag::before { content:''; display:block; width:18px; height:2px; background:var(--gold); border-radius:2px; }
  .cart-hero h1 { font-family:var(--font-head); font-size:clamp(1.6rem,3vw,2.2rem); color:#fff; }
  .cart-hero h1 em { color:var(--gold); font-style:italic; }

  /* ── PAGE LAYOUT ── */
  .cart-section { padding:48px 0 80px; }
  .cart-container { width:min(1280px,92%); margin:0 auto; }
  .cart-layout { display:grid; grid-template-columns:1fr 340px; gap:28px; align-items:start; }

  /* ── CART TABLE CARD ── */
  .cart-card { background:var(--white); border:1px solid var(--mist); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); animation:fadeUp .7s ease both; }
  .cart-card-bar { height:4px; background:linear-gradient(90deg,var(--amber),var(--gold)); }
  .cart-card-head { display:flex; align-items:center; gap:10px; padding:20px 24px 16px; border-bottom:1px solid var(--mist); }
  .cart-card-head .s-icon { width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,var(--amber),var(--gold)); display:flex; align-items:center; justify-content:center; font-size:.95rem; color:#fff; flex-shrink:0; }
  .cart-card-head h2 { font-family:var(--font-head); font-size:1.15rem; color:var(--espresso); }
  .cart-card-head h2 em { color:var(--amber); font-style:italic; }

  /* ── TABLE ── */
  .cart-table-wrap { overflow-x:auto; }
  .cart-table { width:100%; border-collapse:collapse; }
  .cart-table thead th { background:var(--parchment); font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--espresso); padding:12px 16px; text-align:left; border-bottom:1px solid var(--mist); white-space:nowrap; }
  .cart-table tbody tr { border-bottom:1px solid var(--mist); transition:background .2s; }
  .cart-table tbody tr:last-child { border-bottom:none; }
  .cart-table tbody tr:hover { background:rgba(253,246,235,.6); }
  .cart-table td { padding:16px; vertical-align:middle; }

  /* Product cell */
  .ct-product { display:flex; align-items:center; gap:14px; }
  .ct-product img { width:60px; height:72px; object-fit:cover; border-radius:8px; border:1px solid var(--mist); flex-shrink:0; }
  .ct-product h6 { font-family:var(--font-head); font-size:.9rem; color:var(--espresso); line-height:1.35; }

  /* Price cell */
  .ct-price { font-size:.95rem; font-weight:700; color:var(--amber); }

  /* Qty cell */
  .ct-qty { display:flex; align-items:center; }
  .qty-box { display:inline-flex; align-items:center; border:1.5px solid var(--mist); border-radius:8px; overflow:hidden; background:var(--cream); }
  .qty-box input { width:38px; height:32px; border:none; border-left:1px solid var(--mist); border-right:1px solid var(--mist); text-align:center; font-size:.85rem; font-weight:600; color:var(--espresso); background:var(--white); outline:none; }
  .qty-box button { width:28px; height:32px; background:var(--parchment); border:none; cursor:pointer; font-size:.9rem; color:var(--espresso); transition:background .2s; font-weight:600; }
  .qty-box button:hover { background:var(--amber); color:#fff; }

  /* Delete cell */
  .ct-del { display:flex; align-items:center; justify-content:center; }
  .ct-del .del-btn { width:34px; height:34px; border-radius:8px; background:rgba(200,114,10,.08); border:1.5px solid rgba(200,114,10,.2); color:var(--amber); display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1rem; transition:all .25s; }
  .ct-del .del-btn:hover { background:#ffeaea; border-color:#e05050; color:#c0392b; transform:scale(1.1); }

  /* Empty cart */
  .cart-empty { text-align:center; padding:56px 24px; }
  .cart-empty i { font-size:3.5rem; color:var(--mist); display:block; margin-bottom:14px; }
  .cart-empty p { font-size:.9rem; color:#7a5c36; margin-bottom:20px; }
  .cart-empty a { display:inline-flex; align-items:center; gap:7px; padding:11px 24px; background:var(--espresso); color:#fff; border-radius:10px; font-size:.85rem; font-weight:600; transition:all .25s; }
  .cart-empty a:hover { background:var(--amber); transform:translateY(-2px); }
  .cart-empty a i { animation:bounceX 1.8s ease infinite; }

  /* ── TOTALS CARD ── */
  .totals-card { background:var(--white); border:1px solid var(--mist); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); animation:fadeUp .7s ease both .15s; position:sticky; top:90px; }
  .totals-card-bar { height:4px; background:linear-gradient(90deg,var(--amber),var(--gold)); }
  .totals-card-head { display:flex; align-items:center; gap:10px; padding:20px 22px 16px; border-bottom:1px solid var(--mist); }
  .totals-card-head .s-icon { width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,var(--amber),var(--gold)); display:flex; align-items:center; justify-content:center; font-size:.95rem; color:#fff; }
  .totals-card-head h2 { font-family:var(--font-head); font-size:1.1rem; color:var(--espresso); }
  .totals-card-head h2 em { color:var(--amber); font-style:italic; }
  .totals-body { padding:20px 22px; }
  .totals-row { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px dashed var(--mist); font-size:.88rem; }
  .totals-row:last-child { border-bottom:none; }
  .totals-row .label { color:#7a5c36; font-weight:500; display:flex; align-items:center; gap:6px; }
  .totals-row .label i { color:var(--amber); font-size:.95rem; }
  .totals-row .value { font-weight:700; color:var(--espresso); }
  .totals-row.promo .value { color:#4a9940; }
  .totals-row.wallet .value { color:#2d6a9f; }
  .totals-row.grand { padding-top:14px; margin-top:4px; border-top:2px solid var(--mist); border-bottom:none; }
  .totals-row.grand .label { font-size:1rem; font-weight:700; color:var(--espresso); }
  .totals-row.grand .value { font-size:1.15rem; color:var(--amber); }
  .checkout-btn { display:flex; align-items:center; justify-content:center; gap:9px; width:100%; padding:14px; margin-top:18px; background:var(--espresso); color:#fff; border:none; border-radius:10px; font-family:var(--font-body); font-size:.92rem; font-weight:600; cursor:pointer; transition:all .28s cubic-bezier(.34,1.56,.64,1); }
  .checkout-btn:hover { background:var(--amber); transform:translateY(-3px); box-shadow:0 10px 30px rgba(200,114,10,.35); }
  .checkout-btn i { animation:bounceX 1.8s ease infinite; }
  .empty-totals { padding:28px 22px; text-align:center; color:#7a5c36; font-size:.85rem; }
  .empty-totals i { font-size:2rem; color:var(--mist); display:block; margin-bottom:10px; }

  /* ── FOOTER ── */
  .footer { background:var(--ink); padding:56px 0 0; margin-top:0; }
  .footer-grid { display:grid; grid-template-columns:1.2fr 1fr 1fr; gap:48px; padding-bottom:48px; width:min(1280px,92%); margin:0 auto; }
  .footer-logo { font-family:var(--font-head); font-size:1.3rem; font-weight:900; color:#fff; margin-bottom:12px; }
  .footer-logo span { color:var(--gold); }
  .footer-brand p { font-size:.84rem; color:rgba(255,255,255,.45); line-height:1.7; margin-bottom:18px; }
  .footer-contact-row { display:flex; align-items:flex-start; gap:10px; font-size:.82rem; color:rgba(255,255,255,.5); margin-bottom:10px; line-height:1.6; }
  .footer-contact-row i { color:var(--gold); font-size:1rem; margin-top:2px; flex-shrink:0; }
  .footer-col h3 { font-family:var(--font-head); font-size:.92rem; color:#fff; margin-bottom:18px; padding-bottom:10px; border-bottom:1px solid rgba(255,255,255,.08); }
  .footer-col ul { list-style:none; display:flex; flex-direction:column; gap:10px; }
  .footer-col ul a { font-size:.84rem; color:rgba(255,255,255,.45); transition:color .2s; display:flex; align-items:center; gap:7px; }
  .footer-col ul a::before { content:'›'; color:var(--amber); font-size:1rem; }
  .footer-col ul a:hover { color:var(--gold); }
  .footer-bottom { border-top:1px solid rgba(255,255,255,.08); padding:20px 0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; width:min(1280px,92%); margin:0 auto; }
  .footer-bottom p { font-size:.8rem; color:rgba(255,255,255,.3); }
  .footer-bottom a { color:var(--gold); font-weight:600; }

  /* ── RESPONSIVE ── */
  @media(max-width:900px){
    .cart-layout { grid-template-columns:1fr; }
    .totals-card  { position:static; }
    .nav-links    { display:none; }
    .hamburger    { display:flex; }
    .footer-grid  { grid-template-columns:1fr 1fr; }
  }
  @media(max-width:600px){
    .cart-section { padding:28px 0 60px; }
    .cart-table thead th:nth-child(2) { display:none; }
    .cart-table td:nth-child(2) { display:none; }
    .footer-grid  { grid-template-columns:1fr; gap:28px; }
    .footer-bottom{ flex-direction:column; text-align:center; }
  }
</style>

<!-- ── NAVBAR ── -->
<header class="navbar">
  <div class="nav-inner">
    <div class="nav-logo">World<span>Window</span></div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="#">Bookstore</a></li>
      <li><a href="#">Authors</a></li>
      <li><a href="#">Categories</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <button class="nav-icon" title="Search"><i class="uil uil-search"></i></button>
      <button class="nav-icon" title="Wishlist"><i class="uil uil-heart"></i></button>
      <button class="nav-icon" title="Cart">
        <i class="uil uil-shopping-cart-alt"></i>
        <span class="badge"><?php echo count($cart); ?></span>
      </button>
      <button class="nav-icon" title="Account"><i class="uil uil-user-circle"></i></button>
      <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">
        <span id="hb1"></span><span id="hb2"></span><span id="hb3"></span>
      </button>
    </div>
  </div>
</header>

<div class="mobile-menu" id="mobileMenu">
  <a href="index.php">Home</a>
  <a href="#">Bookstore</a>
  <a href="#">Authors</a>
  <a href="#">Categories</a>
  <a href="contact.php">Contact</a>
</div>

<!-- ── BREADCRUMB ── -->
<div class="path">
  <div class="inner">
    <a href="index.php">Home</a>
    <span class="sep">/</span>
    <span class="cur">My Cart</span>
  </div>
</div>

<!-- ── HERO ── -->
<div class="cart-hero">
  <div class="inner">
    <div class="cart-hero-tag">Shopping</div>
    <h1>My <em>Cart</em></h1>
  </div>
</div>

<!-- ── CART BODY ── -->
<section class="cart-section">
  <div class="cart-container">
    <div class="cart-layout">

      <!-- LEFT: Product Table -->
      <div class="cart-card">
        <div class="cart-card-bar"></div>
        <div class="cart-card-head">
          <div class="s-icon"><i class="uil uil-shopping-cart-alt"></i></div>
          <h2>Cart <em>Items</em></h2>
        </div>

        <?php if (count($cart) > 0): ?>
        <div class="cart-table-wrap">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart as $product):
                $p_qy = (!isset($_SESSION['USER_LOGIN'])) ? $product['product_qty'] : $product['qty'];
                $sftp = $p_qy * $product['fa'];
                $total_subtotal += $sftp;
              ?>
              <tr>
                <td>
                  <div class="ct-product">
                    <img src="media/product/<?php echo $product['img1']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>"/>
                    <h6><?php echo htmlspecialchars($product['product_name']); ?></h6>
                  </div>
                </td>
                <td><span class="ct-price">BD <?php echo number_format($product['fa'],2); ?></span></td>
                <td>
                  <div class="ct-qty">
                    <div class="qty-box">
                      <button onclick="de_sc(this,<?php echo $product['id']; ?>)">−</button>
                      <input type="text" value="<?php echo $p_qy; ?>"/>
                      <button onclick="inc(this,<?php echo $product['id']; ?>)">+</button>
                    </div>
                  </div>
                </td>
                <td><span class="ct-price">BD <?php echo number_format($sftp,2); ?></span></td>
                <td>
                  <div class="ct-del">
                    <div class="del-btn" onclick="del_cart(<?php echo $product['id']; ?>)" title="Remove">
                      <i class="uil uil-trash-alt"></i>
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php else: ?>
        <div class="cart-empty">
          <i class="uil uil-shopping-cart-alt"></i>
          <p>Your cart is empty. Start adding some books!</p>
          <a href="index.php"><i class="uil uil-store-alt"></i> Browse Bookstore</a>
        </div>
        <?php endif; ?>
      </div>

      <!-- RIGHT: Totals -->
      <div class="totals-card">
        <div class="totals-card-bar"></div>
        <div class="totals-card-head">
          <div class="s-icon"><i class="uil uil-receipt-alt"></i></div>
          <h2>Cart <em>Totals</em></h2>
        </div>

        <?php if (!isset($_SESSION['USER_LOGIN'])): ?>
          <?php if ($total_subtotal > 0): ?>
          <div class="totals-body">
            <div class="totals-row">
              <span class="label"><i class="uil uil-tag-alt"></i> Subtotal</span>
              <span class="value">BD <?php echo number_format($total_subtotal,2); ?></span>
            </div>
            <div class="totals-row grand">
              <span class="label">Grand Total</span>
              <span class="value">BD <?php echo number_format($total_subtotal,2); ?></span>
            </div>
            <button class="checkout-btn" onclick="control.redirect('checkout.php')">
              <i class="uil uil-lock-alt"></i> Proceed to Checkout
            </button>
          </div>
          <?php else: ?>
          <div class="empty-totals"><i class="uil uil-receipt-alt"></i><p>Add items to see totals.</p></div>
          <?php endif; ?>

        <?php else:
          $utm = $_SESSION['utm_source'];
          $uid = $_SESSION['USER_ID'];
          $rs  = mysqli_query($con, "SELECT * FROM cart WHERE u_id='$uid' AND belonging_city='$utm'");
          if (mysqli_num_rows($rs) > 0):
            $ct = mysqli_fetch_assoc($rs);
            if ($ct['total'] > 0): ?>
            <div class="totals-body">
              <div class="totals-row">
                <span class="label"><i class="uil uil-tag-alt"></i> Subtotal</span>
                <span class="value">BD <?php echo number_format($ct['total'],2); ?></span>
              </div>
              <div class="totals-row">
                <span class="label"><i class="uil uil-truck"></i> Shipping</span>
                <span class="value">BD <?php echo number_format($ct['ship_fee'],2); ?></span>
              </div>
              <?php if ($ct['is_applied']): ?>
              <div class="totals-row promo">
                <span class="label"><i class="uil uil-percentage"></i> Promo</span>
                <span class="value">− BD <?php echo number_format($ct['promo'],2); ?></span>
              </div>
              <?php endif; ?>
              <?php if ($ct['is_add_w']): ?>
              <div class="totals-row wallet">
                <span class="label"><i class="uil uil-wallet"></i> Wallet</span>
                <span class="value">− BD <?php echo number_format($ct['wl_amt'],2); ?></span>
              </div>
              <?php endif; ?>
              <div class="totals-row grand">
                <span class="label">Grand Total</span>
                <span class="value">BD <?php echo number_format($ct['final_amt'],2); ?></span>
              </div>
              <button class="checkout-btn" onclick="control.redirect('checkout.php')">
                <i class="uil uil-lock-alt"></i> Proceed to Checkout
              </button>
            </div>
            <?php else: ?>
            <div class="empty-totals"><i class="uil uil-receipt-alt"></i><p>Add items to see totals.</p></div>
            <?php endif; ?>
          <?php else: ?>
          <div class="empty-totals"><i class="uil uil-receipt-alt"></i><p>Add items to see totals.</p></div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<!-- ══ FULL FOOTER ══ -->
<footer class="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="footer-logo">World<span>Window</span> Publishing</div>
      <p>Bahrain's premier multivendor bookstore — connecting authors and readers across the Kingdom.</p>
      <div class="footer-contact-row"><i class="uil uil-map-marker"></i>House 2610, Road 1042, Block 1210, Hamad Town, Kingdom of Bahrain</div>
      <div class="footer-contact-row"><i class="uil uil-phone-alt"></i>+973 39607724 / +973 36802244</div>
      <div class="footer-contact-row"><i class="uil uil-envelope-alt"></i>contact@wwpublishing.com</div>
    </div>
    <div class="footer-col">
      <h3>Policies</h3>
      <ul>
        <li><a href="#">Terms &amp; Conditions</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Refund and Returns Policy</a></li>
        <li><a href="#">Shipping Policy</a></li>
        <li><a href="#">Author Policy</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h3>Important Links</h3>
      <ul>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Bookstore</a></li>
        <li><a href="#">Author Information</a></li>
        <li><a href="#">Publish with Us</a></li>
        <li><a href="contact.php">Contact Us</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>World Window Publishing &copy; 2025</p>
    <p>Developed by <a href="#">Mega Digital Solutions</a></p>
  </div>
</footer>

<script>
let open = false;
function toggleMenu() {
  open = !open;
  document.getElementById('mobileMenu').classList.toggle('open', open);
  const [h1,h2,h3] = ['hb1','hb2','hb3'].map(id => document.getElementById(id));
  if (open) {
    h1.style.cssText = 'transform:rotate(45deg) translate(5px,5px)';
    h2.style.cssText = 'opacity:0';
    h3.style.cssText = 'transform:rotate(-45deg) translate(5px,-5px)';
  } else { [h1,h2,h3].forEach(h => h.style.cssText = ''); }
}
</script>

<?php require('require/foot.php'); ?>
<?php require('require/last.php'); ?>
