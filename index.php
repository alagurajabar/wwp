<?php
require('utility/utility.php');

// ── DB QUERIES ──────────────────────────────────────────────────────
// Best Sellers
$bsRes = mysqli_query($con, "SELECT p.id, p.product_name, p.price, p.sell_price, p.fa, p.qty, p.img1, c.category
                               FROM product p
                          LEFT JOIN categories c ON c.id = p.cat_id
                              WHERE p.status=1 AND p.bs=1 ORDER BY p.id DESC LIMIT 12");
$bestSellers = [];
if ($bsRes) while ($r = mysqli_fetch_assoc($bsRes)) $bestSellers[] = $r;

// New Releases
$nrRes = mysqli_query($con, "SELECT p.id, p.product_name, p.price, p.sell_price, p.fa, p.qty, p.img1, c.category
                               FROM product p
                          LEFT JOIN categories c ON c.id = p.cat_id
                              WHERE p.status=1 AND p.isnew=1 ORDER BY p.id DESC LIMIT 12");
$newReleases = [];
if ($nrRes) while ($r = mysqli_fetch_assoc($nrRes)) $newReleases[] = $r;

// Books of the Month (latest approved)
$bomRes = mysqli_query($con, "SELECT p.id, p.product_name, p.price, p.sell_price, p.fa, p.qty, p.img1, c.category
                                FROM product p
                           LEFT JOIN categories c ON c.id = p.cat_id
                               WHERE p.status=1 ORDER BY p.id DESC LIMIT 12");
$booksOfMonth = [];
if ($bomRes) while ($r = mysqli_fetch_assoc($bomRes)) $booksOfMonth[] = $r;

// Categories
$catRes = mysqli_query($con, "SELECT * FROM categories WHERE status=1 ORDER BY id ASC LIMIT 6");
$categories = [];
if ($catRes) while ($r = mysqli_fetch_assoc($catRes)) $categories[] = $r;

// New Products (for legacy section)
$newProducts = [];
$res = mysqli_query($con, "SELECT p.id, p.product_name, p.price AS price, p.sell_price, p.fa, p.qty, p.img1
                             FROM product p WHERE p.status=1 ORDER BY p.id DESC LIMIT 12");
if ($res) while ($row = mysqli_fetch_assoc($res)) $newProducts[] = $row;

// Helper
function book_card($p, $showCat = true) {
    $name  = htmlspecialchars($p['product_name']);
    $price = number_format((float)$p['price'], 3);
    $fa    = number_format((float)($p['fa'] ?: $p['sell_price']), 3);
    $qty   = (int)$p['qty'];
    $pid   = (int)$p['id'];
    $cat   = htmlspecialchars($p['category'] ?? '');
    $img   = !empty($p['img1']) ? 'media/product/' . $p['img1'] : 'assets/images/product/big-2.jpg';
    ob_start(); ?>
    <div class="book-card">
      <div class="book-img">
        <a href="product_detail.php?id=<?php echo $pid; ?>">
          <img src="<?php echo $img; ?>" alt="<?php echo $name; ?>"/>
        </a>
        <button class="wish-btn" title="Add to Wishlist"><i class="uil uil-heart-alt"></i></button>
      </div>
      <div class="book-body">
        <?php if ($showCat && $cat): ?>
        <p class="book-cat"><?php echo $cat; ?></p>
        <?php endif; ?>
        <a href="product_detail.php?id=<?php echo $pid; ?>" class="book-title"><?php echo $name; ?></a>
        <div class="book-price">
          <span class="price-now">BD <?php echo $fa; ?></span>
          <?php if ($price != $fa): ?><span class="price-was">BD <?php echo $price; ?></span><?php endif; ?>
        </div>
        <button class="add-cart-btn" onclick="addToCart(<?php echo $pid; ?>)">
          <i class="uil uil-shopping-cart-alt"></i> Add to Cart
        </button>
      </div>
    </div>
    <?php return ob_get_clean();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>World Window Publishing – Bahrain's Premier Bookstore</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
:root {
  --navy:     #0d1b2a;
  --navy2:    #111f30;
  --navy3:    #162233;
  --gold:     #c8a84b;
  --gold2:    #e2c97e;
  --cream:    #f5e6c0;
  --parchment:#ede0b8;
  --sand:     #d4c48a;
  --white:    #ffffff;
  --off-white:#f9f7f2;
  --text:     #1a1a2e;
  --muted:    #6b7280;
  --radius:   4px;
  --font-head:'Playfair Display', Georgia, serif;
  --font-body:'DM Sans', sans-serif;
}
html { scroll-behavior:smooth; }
body { font-family:var(--font-body); background:var(--off-white); color:var(--text); overflow-x:hidden; }
a { text-decoration:none; color:inherit; }
img { display:block; max-width:100%; }

@keyframes fadeUp  { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeIn  { from{opacity:0} to{opacity:1} }
@keyframes pulse   { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }
@keyframes slideL  { from{opacity:0;transform:translateX(30px)} to{opacity:1;transform:translateX(0)} }

.container { width:min(1340px,94%);
 margin:0 auto; }

/* ══ TOP BAR ══ */
.top-bar { background:var(--cream); padding:9px 0; border-bottom:1px solid var(--sand); }
.top-bar-inner { display:flex; align-items:center; justify-content:space-between; }
.top-bar-left  { font-size:.78rem; color:#5a4a20; font-weight:500; display:flex; gap:20px; }
.top-bar-left a { color:#5a4a20; transition:color .2s; }
.top-bar-left a:hover { color:var(--navy); }
.top-bar-right { display:flex; align-items:center; gap:14px; }
.top-bar-right a { color:#5a4a20; font-size:1.05rem; transition:all .2s; }
.top-bar-right a:hover { color:var(--navy); transform:scale(1.15); }

/* ══ HEADER ══ */
.site-header { background:var(--navy); padding:14px 0; position:sticky; top:0; z-index:200; box-shadow:0 2px 20px rgba(0,0,0,.35); }
.header-inner { display:flex; align-items:center; gap:20px; justify-content:space-between; }
.site-logo { display:flex; align-items:center; gap:10px; flex-shrink:0; }
.site-logo img { height:45px; width:auto; }
.logo-text { font-family:var(--font-head); line-height:1.1; }
.logo-text .w  { font-size:1.5rem; font-weight:900; color:var(--white); letter-spacing:.04em; }
.logo-text .sub{ font-size:.62rem; color:var(--gold); letter-spacing:.22em; text-transform:uppercase; }

.search-bar { flex:1; max-width:560px; display:flex; border-radius:4px; overflow:hidden; border:1px solid rgba(255,255,255,.1); }
.search-bar input { flex:1; padding:10px 16px; background:#1a2d40; border:none; outline:none; font-size:.88rem; color:var(--white); font-family:var(--font-body); }
.search-bar input::placeholder { color:rgba(255,255,255,.4); }
.search-bar .cat-sel { padding:10px 14px; background:#162233; border:none; border-left:1px solid rgba(255,255,255,.1); color:rgba(255,255,255,.6); font-size:.82rem; cursor:pointer; outline:none; font-family:var(--font-body); }
.search-bar .cat-sel option { background:var(--navy); }
.search-bar .srch-btn { padding:10px 18px; background:var(--gold); color:var(--navy); border:none; cursor:pointer; font-size:1rem; transition:background .2s; }
.search-bar .srch-btn:hover { background:var(--gold2); }

.header-actions { display:flex; align-items:center; gap:8px; flex-shrink:0; }
.h-action-link { display:flex; align-items:center; gap:7px; color:var(--white); font-size:.82rem; font-weight:500; cursor:pointer; transition:color .2s; background:none; border:none; padding:0; }
.h-action-link:hover { color:var(--gold); }
.h-action-link i { font-size:1.2rem; }
.h-action-btn { position:relative; width:38px; height:38px; display:flex; align-items:center; justify-content:center; color:var(--white); font-size:1.2rem; cursor:pointer; transition:color .2s; background:none; border:none; }
.h-action-btn:hover { color:var(--gold); }
.h-action-btn .hbadge { position:absolute; top:-4px; right:-4px; width:16px; height:16px; background:var(--gold); color:var(--navy); font-size:.58rem; font-weight:700; border-radius:50%; display:flex; align-items:center; justify-content:center; animation:pulse 2s ease infinite; }
.h-divider { width:1px; height:22px; background:rgba(255,255,255,.15); }

/* ══ NAV ══ */
.main-nav { background:var(--navy2); border-top:1px solid rgba(255,255,255,.07); }
.nav-inner { display:flex; align-items:center; justify-content:space-between; height:50px; }
.browse-btn { display:flex; align-items:center; gap:9px; padding:0 20px; height:100%; background:rgba(255,255,255,.06); color:var(--white); font-size:.84rem; font-weight:600; letter-spacing:.04em; text-transform:uppercase; cursor:pointer; border:none; transition:background .2s; gap:8px; }
.browse-btn:hover { background:rgba(255,255,255,.12); }
.browse-btn i { font-size:1rem; }
.nav-links { display:flex; list-style:none; height:100%; }
.nav-links li { height:100%; }
.nav-links a { display:flex; align-items:center; height:100%; padding:0 16px; font-size:.84rem; font-weight:500; letter-spacing:.04em; text-transform:uppercase; color:rgba(255,255,255,.8); transition:all .2s; position:relative; }
.nav-links a::after { content:''; position:absolute; bottom:0; left:16px; right:16px; height:2px; background:var(--gold); transform:scaleX(0); transition:transform .25s; }
.nav-links a:hover, .nav-links a.active { color:var(--white); }
.nav-links a:hover::after, .nav-links a.active::after { transform:scaleX(1); }
.publish-link { margin-left:auto; display:flex; align-items:center; height:100%; padding:0 22px; color:var(--gold); font-size:.84rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; transition:color .2s; }
.publish-link:hover { color:var(--gold2); }
.mob-nav-toggle { display:none; background:none; border:none; color:var(--white); font-size:1.4rem; cursor:pointer; padding:8px; }

/* ══ HERO ══ */
.hero { display:grid;
 grid-template-columns:1fr 0.7fr;

  min-height:460px;
padding: 0 80px; 
 }


.hero-left{
  padding:52px 48px 0;
  position:relative;
  overflow:hidden;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
}
.hero-left::before{
  content:"";
  position:absolute;
  inset:0;
  background:url("assets/images/Hero-Banner-1024x1024.jpg") center/cover no-repeat;
  opacity:0.35;   /* adjust opacity */
  z-index:0;
}.hero-left > *{
  position:relative;
  z-index:1;
}
.hero-brand-tag { font-size:.7rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:#8a6e30; margin-bottom:16px; }
.hero-left h1 { font-family:var(--font-head); font-size:clamp(2.2rem,4.5vw,3.6rem); color:var(--text); line-height:1.1; font-weight:900; margin-bottom:28px; }
.hero-readmore { display:inline-flex; align-items:center; gap:8px; font-size:.82rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--text); border-bottom:2px solid var(--text); padding-bottom:2px; transition:color .2s; }
.hero-readmore:hover { color:var(--gold); border-color:var(--gold); }
.hero-books { display:flex; align-items:flex-end; gap:-10px; margin-top:24px; height:220px; justify-content:center; }
.hero-books img { height:100%; width:auto; object-fit:contain; filter:drop-shadow(4px 6px 12px rgba(0,0,0,.25)); transition:transform .3s; }
.hero-books img:hover { transform:translateY(-8px); }
.hero-books img:nth-child(2) { margin-left:-30px; margin-right:-30px; height:110%; }
.hero-books .fallback-books { display:flex; align-items:flex-end; gap:8px; height:100%; }
.hero-books .fb { width:90px; border-radius:3px; box-shadow:4px 6px 16px rgba(0,0,0,.25); }

.hero-right { display:grid; grid-template-rows:1.6fr 1fr; grid-template-columns:1fr 1fr; }
.hero-panel { position:relative; overflow:hidden; }
.hero-panel.full { grid-column:1/3; }
.hero-panel img { width:100%; height:100%; object-fit:cover; transition:transform .5s; }
.hero-panel:hover img { transform:scale(1.04); }
.hero-panel .panel-overlay { position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.6) 0%, transparent 60%); }
.hero-panel .panel-text { position:absolute; bottom:0; left:0; right:0; padding:20px 22px; z-index:2; }
.hero-panel .panel-tag { font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--gold2); margin-bottom:6px; display:block; }
.hero-panel h2 { font-family:var(--font-head); font-size:1.45rem; color:var(--white); line-height:1.25; margin-bottom:10px; }
.hero-panel h3 { font-family:var(--font-head); font-size:1.1rem; color:var(--white); line-height:1.3; margin-bottom:10px; }
.panel-readmore { display:inline-flex; align-items:center; gap:6px; font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--white); border-bottom:1.5px solid rgba(255,255,255,.6); padding-bottom:1px; transition:color .2s; }
.panel-readmore:hover { color:var(--gold2); border-color:var(--gold2); }
.hero-panel.cream-panel { background:var(--parchment); }
.hero-panel.cream-panel .panel-overlay { background:linear-gradient(to top, rgba(0,0,0,.25) 0%, transparent 70%); }

/* ══ FEATURED BOOKS ══ */
.featured-section { background:var(--white); padding:40px 0; }
.sec-label { text-align:center; font-size:.72rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--gold); margin-bottom:8px; }
.sec-title { font-family:var(--font-head); font-size:clamp(1.8rem,3vw,2.6rem); text-align:center; color:var(--text); font-weight:900; margin-bottom:14px; }
.sec-desc { text-align:center; color:var(--muted); font-size:.9rem; line-height:1.7; max-width:680px; margin:0 auto 36px; }

/* Tabs */
.book-tabs { display:flex; justify-content:center; gap:0; border-bottom:2px solid #e5e7eb; margin-bottom:32px; }
.tab-btn { padding:12px 28px; background:none; border:none; font-family:var(--font-body); font-size:.82rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); cursor:pointer; position:relative; transition:color .2s; }
.tab-btn::after { content:''; position:absolute; bottom:-2px; left:0; right:0; height:2px; background:var(--navy); transform:scaleX(0); transition:transform .25s; }
.tab-btn.active { color:var(--navy); }
.tab-btn.active::after { transform:scaleX(1); }

/* Slider */
.books-slider-wrap { position:relative; }
.books-slider { display:flex; gap:20px; overflow-x:auto; scroll-snap-type:x mandatory; scrollbar-width:none; padding-bottom:8px; }
.books-slider::-webkit-scrollbar { display:none; }
.slider-btn { position:absolute; top:50%; transform:translateY(-50%); width:40px; height:40px; border-radius:50%; background:var(--white); border:1px solid #e5e7eb; color:var(--text); font-size:1.1rem; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:5; box-shadow:0 2px 10px rgba(0,0,0,.1); transition:all .2s; }
.slider-btn:hover { background:var(--navy); color:var(--white); border-color:var(--navy); }
.slider-btn.prev { left:-20px; }
.slider-btn.next { right:-20px; }

/* Book Card */
.book-card { flex:0 0 200px; scroll-snap-align:start; background:var(--white); border:1px solid #e9ecef; border-radius:6px; overflow:hidden; transition:all .3s ease; }
.book-card:hover { transform:translateY(-5px); box-shadow:0 12px 36px rgba(0,0,0,.12); }
.book-img { position:relative; aspect-ratio:3/4; overflow:hidden; background:#f0ebe0; }
.book-img img { width:100%; height:100%; object-fit:cover; transition:transform .4s; }
.book-card:hover .book-img img { transform:scale(1.06); }
.wish-btn { position:absolute; top:8px; right:8px; width:28px; height:28px; border-radius:50%; background:var(--white); border:none; color:#b0452a; font-size:.85rem; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,.12); transition:all .2s; }
.wish-btn:hover { background:#ffeaea; transform:scale(1.15); }
.book-body { padding:12px 14px; }
.book-cat  { font-size:.64rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.1em; margin-bottom:4px; }
.book-title { font-family:var(--font-head); font-size:.88rem; color:var(--text); line-height:1.35; display:block; margin-bottom:8px; transition:color .2s; }
.book-title:hover { color:var(--gold); }
.book-price { display:flex; align-items:center; gap:7px; margin-bottom:10px; }
.price-now  { font-size:.95rem; font-weight:700; color:var(--text); }
.price-was  { font-size:.78rem; color:var(--muted); text-decoration:line-through; }
.add-cart-btn { width:100%; padding:8px; background:var(--navy); color:var(--white); border:none; border-radius:4px; font-size:.75rem; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; transition:all .25s; font-family:var(--font-body); }
.add-cart-btn:hover { background:var(--gold); color:var(--navy); }
.empty-books { text-align:center; padding:40px; color:var(--muted); width:100%; font-size:.9rem; }

/* Tab panels */
.tab-panel { display:none; }
.tab-panel.active { display:block; }

/* ══ ABOUT US ══ */
.about-section { background:var(--off-white); padding:50px 0; }
.about-grid { display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; }
.about-img { border-radius:8px; overflow:hidden; aspect-ratio:4/3; }
.about-img img { width:100%; height:100%; object-fit:cover; }
.about-text .ab-label { font-size:.72rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--muted); margin-bottom:12px; }
.about-text h2 { font-family:var(--font-head); font-size:clamp(1.6rem,2.8vw,2.2rem); font-weight:900; color:var(--text); line-height:1.2; margin-bottom:20px; }
.about-text p  { font-size:.9rem; color:var(--muted); line-height:1.8; margin-bottom:14px; }

/* ══ FEATURED CATEGORIES ══ */
.cats-section { background:var(--white); padding:50px 0; }
.cats-mosaic { display:grid; grid-template-columns:1.4fr 1fr 1fr; grid-template-rows:260px 260px; gap:4px; }
.cat-tile { position:relative; overflow:hidden; cursor:pointer; }
.cat-tile:first-child { grid-row:1/3; }
.cat-tile img { width:100%; height:100%; object-fit:cover; transition:transform .5s; }
.cat-tile:hover img { transform:scale(1.07); }
.cat-tile .cat-overlay { position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.65) 0%, rgba(0,0,0,.1) 60%); transition:background .3s; }
.cat-tile:hover .cat-overlay { background:linear-gradient(to top, rgba(0,0,0,.75) 0%, rgba(0,0,0,.2) 60%); }
.cat-tile .cat-label { position:absolute; bottom:0; left:0; right:0; padding:20px 22px; }
.cat-tile .cat-label span { display:inline-block; background:rgba(13,27,42,.7); color:var(--white); font-size:.78rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; padding:8px 16px; border-left:3px solid var(--gold); }
/* Fallback tiles with gradient bg */
.cat-tile.grad-1 { background:linear-gradient(135deg,#1e3a5f,#2d6a9f); }
.cat-tile.grad-2 { background:linear-gradient(135deg,#2d5a27,#4a9940); }
.cat-tile.grad-3 { background:linear-gradient(135deg,#5a1a2d,#9e3255); }
.cat-tile.grad-4 { background:linear-gradient(135deg,#3b2408,#7a4a18); }
.cat-tile.grad-5 { background:linear-gradient(135deg,#1a2d5f,#4a3a9f); }

/* ══ EXCELLENCE ══ */
.excellence-section { background:var(--off-white); padding:50px 0; }
.excellence-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:24px; }
.ex-card { background:var(--white); border:1px solid #e9ecef; border-radius:6px; padding:32px 24px; text-align:center; transition:all .3s; }
.ex-card:hover { transform:translateY(-5px); box-shadow:0 10px 32px rgba(0,0,0,.09); border-color:var(--gold); }
.ex-icon { width:72px; height:72px; border-radius:50%; background:var(--cream); border:2px solid var(--sand); display:flex; align-items:center; justify-content:center; margin:0 auto 18px; font-size:1.8rem; color:var(--navy); transition:all .3s; }
.ex-card:hover .ex-icon { background:var(--navy); color:var(--gold); border-color:var(--navy); }
.ex-card h3 { font-size:.8rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--text); margin-bottom:10px; }
.ex-card p  { font-size:.84rem; color:var(--muted); line-height:1.7; }

/* ══ FOOTER ══ */
.footer { background:var(--navy); padding:40px 0 0; }
.footer-grid { display:grid; grid-template-columns:1.1fr 1fr 1fr 1fr; gap:40px; padding-bottom:48px; }
.footer-brand .f-logo { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
.footer-brand .f-logo img { height:48px; width:auto; }
.f-logo-text { font-family:var(--font-head); }
.f-logo-text .fw  { font-size:1.2rem; font-weight:900; color:var(--white); }
.f-logo-text .fsub{ font-size:.55rem; color:var(--gold); letter-spacing:.2em; text-transform:uppercase; display:block; }
.footer-brand p { font-size:.82rem; color:rgba(255,255,255,.45); line-height:1.75; }
.f-col h4 { font-size:.78rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--white); margin-bottom:18px; padding-bottom:10px; border-bottom:1px solid rgba(255,255,255,.1); }
.f-col ul { list-style:none; display:flex; flex-direction:column; gap:10px; }
.f-col ul a { font-size:.84rem; color:rgba(255,255,255,.5); transition:color .2s; display:flex; align-items:center; gap:8px; }
.f-col ul a::before { content:'›'; color:var(--gold); font-size:1rem; }
.f-col ul a:hover { color:var(--gold); }
.f-contact-row { display:flex; align-items:flex-start; gap:10px; font-size:.82rem; color:rgba(255,255,255,.5); margin-bottom:10px; line-height:1.6; }
.f-contact-row i { color:var(--gold); font-size:.95rem; margin-top:2px; flex-shrink:0; }
.footer-bottom { border-top:1px solid rgba(255,255,255,.08); padding:18px 0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.footer-bottom p { font-size:.78rem; color:rgba(255,255,255,.3); }
.footer-bottom a { color:var(--gold); font-weight:600; }
.pay-icons { display:flex; align-items:center; gap:8px; }
.pay-icon { width:40px; height:26px; background:rgba(255,255,255,.1); border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:.55rem; font-weight:700; color:rgba(255,255,255,.6); letter-spacing:.04em; }

/* ══ MOBILE MENU ══ */
.mobile-nav { display:none; position:fixed; inset:0; top:0; background:var(--navy); z-index:500; padding:24px; flex-direction:column; gap:0; overflow-y:auto; }
.mobile-nav.open { display:flex; animation:fadeIn .25s ease; }
.mob-nav-close { background:none; border:none; color:var(--white); font-size:1.6rem; cursor:pointer; align-self:flex-end; margin-bottom:20px; }
.mobile-nav a { font-size:1rem; font-weight:600; color:rgba(255,255,255,.85); padding:14px 0; border-bottom:1px solid rgba(255,255,255,.08); letter-spacing:.06em; text-transform:uppercase; }
.mobile-nav a:hover { color:var(--gold); }

/* ══ RESPONSIVE ══ */
@media(max-width:1100px) { .excellence-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:960px) {
  .hero { grid-template-columns:1fr; }
  .hero-right { min-height:300px; }
  .cats-mosaic { grid-template-columns:1fr 1fr; grid-template-rows:200px 200px 200px; }
  .cats-mosaic .cat-tile:first-child { grid-row:auto; }
  .footer-grid { grid-template-columns:1fr 1fr; gap:32px; }
  .about-grid { grid-template-columns:1fr; }
  .nav-links { display:none; }
  .mob-nav-toggle { display:block; }
  .publish-link { display:none; }
}
@media(max-width:680px) {
  .top-bar { display:none; }
  .search-bar { display:none; }
  .hero-left { padding:36px 28px 0; }
  .hero-left h1 { font-size:2rem; }
  .hero-right { grid-template-columns:1fr; grid-template-rows:160px 90px; }
  .hero-panel.full { grid-column:1; }
  .hero-panel:last-child { display:none; }
  .cats-mosaic { grid-template-columns:1fr 1fr; }
  .excellence-grid { grid-template-columns:1fr 1fr; }
  .footer-grid { grid-template-columns:1fr; }
  .footer-bottom { flex-direction:column; text-align:center; }
}
@media(max-width:420px) {
  .excellence-grid { grid-template-columns:1fr; }
}
section {
  scroll-margin-top:80px;
}
</style>
</head>
<body>

<!-- ══ TOP BAR ══ -->
<div class="top-bar">
  <div class="container">
    <div class="top-bar-inner">
      <div class="top-bar-left">
        <span><strong>HOTLINE:</strong> <a href="tel:+97339607724">+973 39607724</a></span>
        <span>|</span>
        <span><strong>EMAIL:</strong> <a href="mailto:contact@wwpublishing.com">contact@wwpublishing.com</a></span>
      </div>
      <div class="top-bar-right">
        <a href="#" title="Facebook"><i class="uil uil-facebook-f"></i></a>
        <a href="#" title="Instagram"><i class="uil uil-instagram"></i></a>
        <a href="#" title="YouTube"><i class="uil uil-youtube"></i></a>
      </div>
    </div>
  </div>
</div>

<!-- ══ HEADER ══ -->
<header class="site-header">
  <div class="container">
    <div class="header-inner">
    <div class="site-logo">
  <img src="assets/images/WWP-Logo-1.02.svg" alt="World Window Publishing Logo">
</div>

      <div class="search-bar">
        <input type="text" placeholder="Search for products" id="searchInput"/>
        <select class="cat-sel" id="searchCat">
          <option value="">SELECT CATEGO...</option>
          <?php foreach($categories as $c): ?>
          <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['category']); ?></option>
          <?php endforeach; ?>
        </select>
        <button class="srch-btn" onclick="doSearch()"><i class="uil uil-search"></i></button>
      </div>

      <div class="header-actions">
        <a href="login.php" class="h-action-link"><i class="uil uil-user-circle"></i> LOGIN / REGISTER</a>
        <div class="h-divider"></div>
        <button class="h-action-btn" title="Wishlist" onclick="location.href='wishlist.php'">
          <i class="uil uil-heart-alt"></i>
          <span class="hbadge">0</span>
        </button>
        <button class="h-action-btn" title="Cart" onclick="location.href='cart.php'">
          <i class="uil uil-shopping-bag"></i>
          <span class="hbadge">0</span>
        </button>
        <button class="mob-nav-toggle" onclick="openMobNav()"><i class="uil uil-bars"></i></button>
      </div>
    </div>
  </div>
</header>

<!-- ══ MAIN NAV ══ -->
<nav class="main-nav">
  <div class="container">
    <div class="nav-inner">
      <button class="browse-btn"><i class="uil uil-apps"></i> BROWSE CATEGORIES <i class="uil uil-angle-down"></i></button>
      <ul class="nav-links">
        <li><a href="index.php" class="active">HOME</a></li>
        <li><a href="bookstore.php">BOOKSTORE</a></li>
        <li><a href="about.php">ABOUT US</a></li>
        <li><a href="authors.php">AUTHORS</a></li>
        <li><a href="contact.php">CONTACT US</a></li>
      </ul>
      <a href="register_seller.php" class="publish-link">PUBLISH WITH US</a>
    </div>
  </div>
</nav>

<!-- ══ MOBILE NAV ══ -->
<div class="mobile-nav" id="mobileNav">
  <button class="mob-nav-close" onclick="closeMobNav()"><i class="uil uil-times"></i></button>
  <a href="index.php">Home</a>
  <a href="bookstore.php">Bookstore</a>
  <a href="about.php">About Us</a>
  <a href="authors.php">Authors</a>
  <a href="contact.php">Contact Us</a>
  <a href="register_seller.php" style="color:var(--gold)">Publish With Us</a>
</div>

<!-- ══ HERO ══ -->
<section class="hero">
  <!-- Left parchment panel -->
  <div class="hero-left">
    <div>
      <div class="hero-brand-tag">WORLD WINDOW PUBLISHING</div>
      <h1>Elevate Your<br>Reading<br>Experience</h1>
      <a href="bookstore.php" class="hero-readmore">READ MORE →</a>
    </div>
    <div class="hero-books">
      <!-- Book covers from DB or fallback -->
      <?php
      $coverBooks = mysqli_query($con, "SELECT img1, product_name FROM product WHERE status=1 AND img1 != '' ORDER BY id DESC LIMIT 3");
      $cbArr = [];
      if ($coverBooks) while ($cb = mysqli_fetch_assoc($coverBooks)) $cbArr[] = $cb;
      if (count($cbArr) >= 2):
          foreach ($cbArr as $cb):
      ?>
        <img src="media/product/<?php echo $cb['img1']; ?>" alt="<?php echo htmlspecialchars($cb['product_name']); ?>"/>
      <?php endforeach; else: ?>
        <div class="fallback-books">
          <div class="fb" style="height:160px;background:linear-gradient(135deg,#4a7c59,#2d5a3c)"></div>
          <div class="fb" style="height:200px;background:linear-gradient(135deg,#e8a020,#c8720a)"></div>
          <div class="fb" style="height:140px;background:linear-gradient(135deg,#5a3a7a,#3d2060)"></div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right photo panels -->
  <div class="hero-right">
    <div class="hero-panel full" style="background:#2a3a2a">
     
    <img src="assets/images/Hero-A-1-1024x576.jpg" alt="Bookstore Banner">

    <div class="panel-overlay"></div>
      <div class="panel-text">
        <span class="panel-tag">In Bahrain</span>
        <h2>Your Ultimate Multivendor Bookstore</h2>
        <a href="bookstore.php" class="panel-readmore">READ MORE →</a>
      </div>
    </div>
    <div class="hero-panel" style="background:#1a2a3a">
      <img src="assets/images/New-Author-Banner-1-1024x576.jpg" alt="Bookstore Banner">
      <div class="panel-overlay"></div>
      <div class="panel-text">
        <span class="panel-tag">For Authors</span>
        <h3>Calling All New Authors: Register Here!</h3>
        <a href="seller.php" class="panel-readmore">REGISTER NOW →</a>
      </div>
    </div>
    <div class="hero-panel cream-panel">
     
     <img src="assets/images/Hero-Book.jpg" alt="Bookstore Banner">
    <div class="panel-overlay"></div>
      <div class="panel-text">
        <span class="panel-tag">Author Dashboard</span>
        <h3>Manage Your Publications</h3>
      </div>
    </div>
  </div>
</section>

<!-- ══ FEATURED BOOKS ══ -->
<section class="featured-section">
  <div class="container">
    <p class="sec-label">World Window Publishing</p>
    <h2 class="sec-title">FEATURED BOOKS</h2>
    <p class="sec-desc">Discover our top picks of the month, including bestsellers and exciting new releases. From captivating tales of mystery and magic to thrilling historical fiction, epic science fiction adventures, and heartwarming romances — something for every reader.</p>

    <div class="book-tabs">
      <button class="tab-btn active" onclick="switchTab('bs', this)">BEST SELLERS</button>
      <button class="tab-btn" onclick="switchTab('nr', this)">NEW RELEASES</button>
      <button class="tab-btn" onclick="switchTab('bom', this)">BOOKS OF THE MONTH</button>
    </div>

    <!-- Best Sellers -->
    <div class="tab-panel active" id="tab-bs">
      <div class="books-slider-wrap">
        <button class="slider-btn prev" onclick="slideBooks('tab-bs', -1)"><i class="uil uil-angle-left"></i></button>
        <div class="books-slider" id="slider-bs">
          <?php if (count($bestSellers)): ?>
            <?php foreach ($bestSellers as $p) echo book_card($p); ?>
          <?php else: ?>
            <?php foreach ($newProducts as $p) echo book_card($p); ?>
            <?php if (!count($newProducts)): ?><div class="empty-books">No books available yet.</div><?php endif; ?>
          <?php endif; ?>
        </div>
        <button class="slider-btn next" onclick="slideBooks('tab-bs', 1)"><i class="uil uil-angle-right"></i></button>
      </div>
    </div>

    <!-- New Releases -->
    <div class="tab-panel" id="tab-nr">
      <div class="books-slider-wrap">
        <button class="slider-btn prev" onclick="slideBooks('tab-nr', -1)"><i class="uil uil-angle-left"></i></button>
        <div class="books-slider" id="slider-nr">
          <?php if (count($newReleases)): ?>
            <?php foreach ($newReleases as $p) echo book_card($p); ?>
          <?php else: ?>
            <div class="empty-books">No new releases yet.</div>
          <?php endif; ?>
        </div>
        <button class="slider-btn next" onclick="slideBooks('tab-nr', 1)"><i class="uil uil-angle-right"></i></button>
      </div>
    </div>

    <!-- Books of the Month -->
    <div class="tab-panel" id="tab-bom">
      <div class="books-slider-wrap">
        <button class="slider-btn prev" onclick="slideBooks('tab-bom', -1)"><i class="uil uil-angle-left"></i></button>
        <div class="books-slider" id="slider-bom">
          <?php if (count($booksOfMonth)): ?>
            <?php foreach ($booksOfMonth as $p) echo book_card($p); ?>
          <?php else: ?>
            <div class="empty-books">No books this month yet.</div>
          <?php endif; ?>
        </div>
        <button class="slider-btn next" onclick="slideBooks('tab-bom', 1)"><i class="uil uil-angle-right"></i></button>
      </div>
    </div>
  </div>
</section>
 

<section class="about-section">
  <div class="container">
    <div class="about-grid">
      
      <div class="about-img">
        <img src="assets/images/aboutus.jpg" alt="About Us">
      </div>

      <div class="about-text">
        <p class="ab-label">About Us</p>
        <h2>WELCOME TO WORLD WINDOW PUBLISHING</h2>
        <p>Bahrain's premier multivendor bookstore. Whether you're into soft copies or hard copies, our eCommerce platform connects you with a multitude of vendors and publishers offering a diverse range of books to suit every reader's taste.</p>
        <p>At World Window Publishing, we are dedicated to providing a seamless and enriching shopping experience, supporting authors and publishers while celebrating the love of reading. Join our community and explore a world of books like never before.</p>
      </div>

    </div>
  </div>
</section>


<section class="cats-section">
  <div class="container">
    <p class="sec-label">Browse By</p>
    <h2 class="sec-title">FEATURED CATEGORIES</h2>
    <p class="sec-desc">
      Explore a diverse selection of genres. Whether you're into riveting thrillers, 
      heartwarming romances, enlightening non-fiction, or imaginative fantasy, 
      we have something to satisfy every reader's taste.
    </p>

    <div class="cats-mosaic">

      <!-- FICTION -->
      <div class="cat-tile" onclick="location.href='bookstore.php?cat=1'">
        <img src="assets/images/Fiction.png" alt="Fiction">
        <div class="cat-overlay"></div>
        <div class="cat-label">
          <span>FICTION</span>
        </div>
      </div>

      <!-- NON-FICTION -->
      <div class="cat-tile" onclick="location.href='bookstore.php?cat=2'">
        <img src="assets/images/Non-Fiction.png" alt="Non Fiction">
        <div class="cat-overlay"></div>
        <div class="cat-label">
          <span>NON-FICTION</span>
        </div>
      </div>

      <!-- RELIGION -->
      <div class="cat-tile" onclick="location.href='bookstore.php?cat=3'">
        <img src="assets/images/Religion.png" alt="Religion">
        <div class="cat-overlay"></div>
        <div class="cat-label">
          <span>RELIGION</span>
        </div>
      </div>

      <!-- ACADEMIC -->
      <div class="cat-tile" onclick="location.href='bookstore.php?cat=4'">
        <img src="assets/images/Academic-819x1024.png" alt="Academic">
        <div class="cat-overlay"></div>
        <div class="cat-label">
          <span>ACADEMIC</span>
        </div>
      </div>

      <!-- TECHNOLOGY -->
      <div class="cat-tile" onclick="location.href='bookstore.php?cat=5'">
        <img src="assets/images/Technology.png" alt="Technology">
        <div class="cat-overlay"></div>
        <div class="cat-label">
          <span>TECHNOLOGY</span>
        </div>
      </div>

      

    </div>
  </div>
</section>






<!-- ══ UNMATCHED EXCELLENCE ══ -->
<section class="excellence-section">
  <div class="container">
    <p class="sec-label">Elevate Today</p>
    <h2 class="sec-title">UNMATCHED EXCELLENCE</h2>
    <p class="sec-desc">At World Window Publishing, we go beyond the ordinary to provide exceptional service and support. Enjoy fast and efficient service, personalized attention, and benefit from the expertise of leading professionals.</p>
    <div class="excellence-grid">
      <div class="ex-card">
        <div class="ex-icon"><i class="uil uil-rocket"></i></div>
        <h3>FAST SERVICE</h3>
        <p>Enjoy quick and efficient service for both buyers and publishers, ensuring a smooth experience.</p>
      </div>
      <div class="ex-card">
        <div class="ex-icon"><i class="uil uil-focus-target"></i></div>
        <h3>PERSONALIZED ATTENTION</h3>
        <p>Receive individualized support to find the perfect book or assist you in publishing your own.</p>
      </div>
      <div class="ex-card">
        <div class="ex-icon"><i class="uil uil-award"></i></div>
        <h3>PROFESSIONAL EXPERTISE</h3>
        <p>Benefit from the knowledge and experience of leading academics behind our platform.</p>
      </div>
      <div class="ex-card">
        <div class="ex-icon"><i class="uil uil-diamond"></i></div>
        <h3>EXCLUSIVE CONTENT</h3>
        <p>Access unique books, special editions, and rare finds that you won't see elsewhere.</p>
      </div>
    </div>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="f-logo">
          <div class="f-logo-text">
            <div class="fw">WORLD WINDOW</div>
            <span class="fsub">Publishing</span>
          </div>
        </div>
        <p>World Window Publishing, Bahrain's premier multivendor bookstore, offers a diverse selection of books and a seamless shopping experience.</p>
      </div>

      <div class="f-col">
        <h4>Contact</h4>
        <div class="f-contact-row"><i class="uil uil-map-marker"></i>House 2610, Road 1042, Block 1210, Hamad town, Kingdom of Bahrain</div>
        <div class="f-contact-row"><i class="uil uil-phone-alt"></i>Phone: +973 39607724 / +973 36802244</div>
        <div class="f-contact-row"><i class="uil uil-envelope-alt"></i>Email: contact@wwpublishing.com</div>
      </div>

      <div class="f-col">
        <h4>Policies</h4>
        <ul>
          <li><a href="#">Shipping Policy</a></li>
          <li><a href="#">Refund and Returns Policy</a></li>
          <li><a href="#">Terms &amp; Conditions</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">New Authors</a></li>
        </ul>
      </div>

      <div class="f-col">
        <h4>Menu</h4>
        <ul>
          <li><a href="index.php">Home WWP</a></li>
          <li><a href="bookstore.php">Bookstore</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="authors.php">Authors' List</a></li>
          <li><a href="contact.php">Contact us</a></li>
          <li><a href="register_seller.php">New Authors</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2024 World Window Publishing | <strong>Developed by <a href="#">Mega Digital Solutions</a>.</strong></p>
      <div class="pay-icons">
        <div class="pay-icon">VISA</div>
        <div class="pay-icon">MC</div>
        <div class="pay-icon">PP</div>
        <div class="pay-icon">AMEX</div>
        <div class="pay-icon">ELECT</div>
        <div class="pay-icon">M/C</div>
      </div>
    </div>
  </div>
</footer>

<script>
/* ── TAB SWITCHING ── */
function switchTab(id, btn) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).classList.add('active');
  btn.classList.add('active');
}

/* ── SLIDER ── */
function slideBooks(panelId, dir) {
  const slider = document.getElementById('slider-' + panelId.replace('tab-', ''));
  if (slider) slider.scrollBy({ left: dir * 440, behavior: 'smooth' });
}

/* ── ADD TO CART ── */
function addToCart(pid) {
  if (typeof add_to_cart === 'function') { add_to_cart(pid); return; }
  window.location.href = 'cart.php?action=add&id=' + pid;
}

/* ── SEARCH ── */
function doSearch() {
  const q   = document.getElementById('searchInput').value.trim();
  const cat = document.getElementById('searchCat').value;
  if (!q && !cat) return;
  let url = 'bookstore.php?';
  if (q) url += 'q=' + encodeURIComponent(q) + '&';
  if (cat) url += 'cat=' + encodeURIComponent(cat);
  window.location.href = url;
}
document.getElementById('searchInput').addEventListener('keypress', e => { if (e.key === 'Enter') doSearch(); });

/* ── MOBILE NAV ── */
function openMobNav()  { document.getElementById('mobileNav').classList.add('open'); document.body.style.overflow='hidden'; }
function closeMobNav() { document.getElementById('mobileNav').classList.remove('open'); document.body.style.overflow=''; }

/* ── SCROLL ANIMATION ── */
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.style.animation='fadeUp .6s ease both'; obs.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.ex-card, .book-card, .cat-tile').forEach(el => { el.style.opacity='0'; obs.observe(el); });
</script>

</body>
</html>
