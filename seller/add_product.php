<?php
require('require/top.php');
$h_t = get_safe_value($con, $_GET['b']);
if (isset($_GET['idp'])) {
    $productid = get_safe_value($con, $_GET['idp']);
}
$name = '';
$category = '';
$subcategory = '';
$qty = '';
$price = '';
$sellprice = '';
$tax = '';
$fa = '0.00';
$sku = '';
$shipping = '';
$shippingex = '';
$sd = '';
$dc = '';
$bs = '';
$filter = '';
$subfilter = '';
$tc = '';
$rday = '';
$return_p = '';
$repref = '';
$sku = get_code();
$img1 = "../assets/images/product/big-2.jpg";
$img2 = "../assets/images/product/big-2.jpg";
$img3 = "../assets/images/product/big-2.jpg";
$img4 = "../assets/images/product/big-2.jpg";
if ($h_t == '1973') {
    $heading = "Add Product";
    $cb = '<a href="javascript:void(0)" class="btn-cp" onclick="add_product()" id="pbtn">
     <i class="uil uil-plus"></i>
     <span>Add Product</span>
   </a>';
} else if ($h_t == '2846') {
    $heading = "Edit Product";
    $productid = get_safe_value($con, $_GET['idp']);
    $cq = "select * from product where id='$productid'";
    $cr = mysqli_query($con, $cq);
    $nor = mysqli_num_rows($cr);
    if ($nor > 0) {
        $r = mysqli_fetch_assoc($cr);
        $name = $r['product_name'];
        $category = $r['cat_id'];
        $subcategory = $r['scat_id'];
        $qty = $r['qty'];
        $price = $r['price'];
        $sellprice = $r['sell_price'];
        $tax = $r['tax'];
        $fa = $r['fa'];
        $sku = $r['sku'];
        $sd = $r['shrt_desc'];
        $dc = $r['description'];
        $tc = $r['disclaimer'];
        $img1 = "../media/product/" . $r['img1'];
        $img2 = "../media/product/" . $r['img2'];
        if (!empty($r['img3'])) {
            $img3 = "../media/product/" . $r['img3'];
        }
        if (!empty($r['img4'])) {
            $img4 = "../media/product/" . $r['img4'];
        }
        $cb = '<a href="javascript:void(0)" class="btn-cp" onclick="edit_product(' . $_GET['idp'] . ')" id="pbtn">
      <i class="uil uil-edit"></i>
      <span>Update Product</span>
    </a>';
    } else {
        redirect('product.php');
    }
} else {
    redirect('product.php');
}
?>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
  /* ── THEME VARIABLES ── */
  :root {
    --ink:       #1a1208;
    --espresso:  #3b2408;
    --amber:     #c8720a;
    --gold:      #e9a832;
    --cream:     #fdf6eb;
    --parchment: #f5ead6;
    --mist:      #ede4d3;
    --white:     #ffffff;
    --shadow:    0 8px 40px rgba(26,18,8,.12);
    --shadow-lg: 0 20px 60px rgba(26,18,8,.18);
    --radius:    14px;
    --font-head: 'Playfair Display', Georgia, serif;
    --font-body: 'DM Sans', sans-serif;
  }

  @keyframes fadeUp  { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
  @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
  @keyframes floatY  { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
  @keyframes bounceX { 0%,100%{transform:translateX(0)} 50%{transform:translateX(4px)} }

  /* ── OVERRIDE page background ── */
  body { background:var(--cream) !important; font-family:var(--font-body) !important; }

  /* ── BREADCRUMB ── */
  .path {
    background:var(--parchment) !important;
    border-bottom:1px solid var(--mist) !important;
    padding:12px 0 !important;
  }
  .path .container { display:flex; align-items:center; gap:6px; font-size:.82rem; color:#7a5c36; }
  .path a { color:var(--espresso) !important; font-weight:500; transition:color .2s; text-decoration:none; }
  .path a:hover { color:var(--amber) !important; }

  /* ── PAGE HERO ── */
  .ap-hero {
    background:linear-gradient(155deg,#1a0d03 0%,#4a2008 45%,#b86208 100%);
    padding:40px 0;
    position:relative; overflow:hidden;
    animation:fadeIn .6s ease;
  }
  .ap-hero::before {
    content:''; position:absolute; bottom:-60px; right:-60px;
    width:280px; height:280px; background:rgba(255,255,255,.04); border-radius:50%; pointer-events:none;
  }
  .ap-hero::after {
    content:''; position:absolute; top:-50px; left:-50px;
    width:190px; height:190px; background:rgba(233,168,50,.07); border-radius:50%; pointer-events:none;
  }
  .ap-hero-inner { position:relative; z-index:2; max-width:1280px; width:92%; margin:0 auto; }
  .ap-hero-tag {
    display:inline-flex; align-items:center; gap:8px;
    font-size:.7rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase;
    color:#e9a832; margin-bottom:8px;
  }
  .ap-hero-tag::before { content:''; display:block; width:18px; height:2px; background:#e9a832; border-radius:2px; }
  .ap-hero h1 {
    font-family:var(--font-head);
    font-size:clamp(1.6rem,3vw,2.2rem); color:#fff; line-height:1.2;
  }
  .ap-hero h1 em { color:#e9a832; font-style:italic; }

  /* ── HIDE OLD WRAPPERS ── */
  .cartrow, .gh { background:transparent !important; box-shadow:none !important; border:none !important; padding:0 !important; }
  .cartrow > .gh > .heading { display:none !important; }

  /* ── FORM SECTION ── */
  .ap-section { padding:40px 0 80px; }
  .ap-container { max-width:960px; width:92%; margin:0 auto; }

  /* ── CARD ── */
  .ap-card {
    background:var(--white); border:1px solid var(--mist);
    border-radius:var(--radius); overflow:hidden;
    box-shadow:var(--shadow); animation:fadeUp .7s ease both;
    margin-bottom:24px;
  }
  .ap-card-bar { height:4px; background:linear-gradient(90deg,var(--amber),var(--gold)); }
  .ap-card-body { padding:32px 36px 36px; }

  /* ── SECTION TITLE ── */
  .ap-section-title {
    display:flex; align-items:center; gap:12px;
    margin-bottom:22px; padding-bottom:14px;
    border-bottom:1px solid var(--mist);
  }
  .ap-section-title .s-icon {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,var(--amber),var(--gold));
    display:flex; align-items:center; justify-content:center;
    font-size:1rem; color:var(--white);
    animation:floatY 3s ease infinite;
  }
  .ap-section-title h2 {
    font-family:var(--font-head); font-size:1.25rem; font-weight:700; color:var(--espresso);
  }
  .ap-section-title h2 em { color:var(--amber); font-style:italic; }

  /* ── FORM GRID ── */
  .ap-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px 22px; }
  .ap-grid .full { grid-column:1 / -1; }

  /* ── FORM ROW OVERRIDE ── */
  .maincontainer2 .formrow,
  .maincontainer2 .formrow.f,
  .maincontainer2 .formrow.ig {
    display:flex !important; flex-direction:column !important;
    gap:7px !important; margin:0 !important;
    background:transparent !important; border:none !important; padding:0 !important;
  }
  .maincontainer2 .formrow .heading {
    font-size:.72rem !important; font-weight:600 !important;
    letter-spacing:.12em !important; text-transform:uppercase !important;
    color:var(--espresso) !important; font-family:var(--font-body) !important;
    margin:0 !important;
  }
  .maincontainer2 .formrow input,
  .maincontainer2 .formrow select,
  .maincontainer2 .formrow textarea {
    font-family:var(--font-body) !important; font-size:.88rem !important;
    background:var(--cream) !important; border:1.5px solid var(--mist) !important;
    border-radius:10px !important; padding:11px 15px !important;
    color:var(--ink) !important; outline:none !important; width:100% !important;
    transition:border-color .25s, background .25s, box-shadow .25s !important;
    margin:0 !important; box-shadow:none !important;
  }
  .maincontainer2 .formrow input::placeholder,
  .maincontainer2 .formrow textarea::placeholder { color:#b09878 !important; }
  .maincontainer2 .formrow input:focus,
  .maincontainer2 .formrow select:focus,
  .maincontainer2 .formrow textarea:focus {
    border-color:var(--amber) !important; background:var(--white) !important;
    box-shadow:0 0 0 3px rgba(200,114,10,.12) !important;
  }
  .maincontainer2 .formrow input[readonly] {
    background:var(--parchment) !important; color:#7a5c36 !important; cursor:default !important;
  }
  .maincontainer2 .formrow textarea { resize:vertical; min-height:110px; }

  /* ── SUB-FILTER CHECKBOXES ── */
  #filters .formrow #subfilters {
    background:var(--cream) !important;
    border:1.5px solid var(--mist) !important;
    border-radius:10px !important; padding:14px !important;
    display:flex; flex-wrap:wrap; gap:10px;
  }
  #filters .formrow #subfilters span {
    display:inline-flex !important; align-items:center; gap:6px;
    font-size:.85rem !important; font-weight:500; color:var(--espresso);
    float:none !important; margin:0 !important;
  }
  #filters .formrow #subfilters input[type="checkbox"] {
    width:16px !important; height:16px !important;
    border-radius:4px !important; padding:0 !important;
    accent-color:var(--amber);
    float:none !important; display:inline-block !important;
  }

  /* ── STATUS ── */
  #pdstatus {
    font-size:.88rem !important; font-weight:600 !important;
    border-radius:10px; padding:10px 16px;
    background:rgba(200,114,10,.06);
    border:1.5px solid rgba(200,114,10,.22);
    display:block; text-transform:capitalize !important;
  }
  #pdstatus:empty { display:none !important; }

  /* ── IMAGE UPLOAD SECTION ── */
  .img-upload-grid {
    display:grid; grid-template-columns:repeat(4,1fr); gap:16px;
  }
  .img-upload-card {
    border:2px dashed var(--mist); border-radius:var(--radius);
    background:var(--cream); overflow:hidden;
    transition:border-color .25s, box-shadow .25s;
    cursor:pointer; position:relative;
  }
  .img-upload-card:hover { border-color:var(--amber); box-shadow:var(--shadow); }
  .img-upload-card .img-preview {
    width:100%; aspect-ratio:1/1; overflow:hidden; position:relative;
    background:var(--parchment);
    display:flex; align-items:center; justify-content:center;
  }
  .img-upload-card .img-preview img {
    width:100%; height:100%; object-fit:cover; display:block;
    transition:transform .4s ease;
  }
  .img-upload-card:hover .img-preview img { transform:scale(1.06); }

  /* The actual hidden file input */
  .img-upload-card input[type="file"] {
    position:absolute; inset:0; opacity:0;
    cursor:pointer; width:100%; height:100%;
    margin:0 !important; border:none !important;
    background:transparent !important; padding:0 !important;
    z-index:10;
  }

  .img-upload-card .img-label {
    position:absolute; bottom:0; left:0; right:0;
    background:linear-gradient(to top, rgba(59,36,8,.85), transparent);
    padding:18px 10px 10px;
    display:flex; align-items:center; justify-content:center; gap:5px;
    font-size:.75rem; font-weight:600; color:#fff; letter-spacing:.06em;
    text-transform:uppercase; pointer-events:none;
    transition:opacity .25s;
  }
  .img-upload-card .img-label i { font-size:.95rem; }
  .img-badge {
    position:absolute; top:8px; left:8px;
    background:var(--amber); color:#fff;
    font-size:.62rem; font-weight:700; padding:2px 8px; border-radius:6px;
    letter-spacing:.06em; text-transform:uppercase; pointer-events:none; z-index:5;
  }
  .img-required { border-color:rgba(200,114,10,.4); }
  .img-optional { border-color:var(--mist); }

  /* ── SUBMIT BUTTON ── */
  .btn-cp {
    display:inline-flex !important; align-items:center !important;
    justify-content:center !important; gap:9px !important;
    padding:14px 36px !important; border-radius:10px !important;
    background:var(--espresso) !important; color:var(--white) !important;
    font-family:var(--font-body) !important; font-size:.92rem !important;
    font-weight:600 !important; border:none !important; cursor:pointer !important;
    text-decoration:none !important; width:100% !important;
    transition:all .28s cubic-bezier(.34,1.56,.64,1) !important;
  }
  .btn-cp:hover {
    background:var(--amber) !important;
    transform:translateY(-3px) !important;
    box-shadow:0 10px 30px rgba(200,114,10,.35) !important;
  }
  .btn-cp i { animation:bounceX 1.8s ease infinite; font-size:1.1rem !important; }

  /* ── HIDE OLD SUBMIT BTN WRAPPERS ── */
  .maincontainer2 > form > .formrow:last-child { margin-top:8px !important; }

  /* ── DIVIDER ── */
  .ap-divider { border:none; border-top:1px dashed var(--mist); margin:28px 0; }

  @media(max-width:700px){
    .ap-card-body { padding:22px 18px 28px; }
    .ap-grid { grid-template-columns:1fr; }
    .ap-grid .full { grid-column:1; }
    .img-upload-grid { grid-template-columns:1fr 1fr; }
  }
  @media(max-width:400px){
    .img-upload-grid { grid-template-columns:1fr 1fr; }
  }
</style>

<!-- ── BREADCRUMB ── -->
<div class="path">
    <div class="container">
        <a href="index.php">Dashboard</a>
        <span>/</span>
        <a href="product.php">Products</a>
        <span>/</span>
        <a href="#"><?php echo $heading; ?></a>
    </div>
</div>

<!-- ── PAGE HERO ── -->
<div class="ap-hero">
    <div class="ap-hero-inner">
        <div class="ap-hero-tag">Seller Dashboard</div>
        <h1><?php echo ($h_t == '1973') ? 'Add <em>Product</em>' : 'Edit <em>Product</em>'; ?></h1>
    </div>
</div>

<!-- ── MAIN FORM ── -->
<div class="ap-section">
  <div class="ap-container">

    <!-- FIX: enctype="multipart/form-data" is REQUIRED for file uploads to work -->
    <form action="#" enctype="multipart/form-data" id="productForm">

      <!-- ══ PRODUCT DETAILS CARD ══ -->
      <div class="ap-card">
        <div class="ap-card-bar"></div>
        <div class="ap-card-body">
          <div class="ap-section-title">
            <div class="s-icon"><i class="uil uil-book-alt"></i></div>
            <h2>Product <em>Details</em></h2>
          </div>

          <div class="ap-grid">

            <!-- Category -->
            <div class="formrow">
              <div class="heading">Category</div>
              <select name="addcatname" id="addcatname" onchange="get_subcatfa()">
                <option value="#">Select Category</option>
                <?php
                $query = "select * from categories order by id desc";
                $resi = mysqli_query($con, $query);
                while ($ropw = mysqli_fetch_assoc($resi)) {
                    $cname = $ropw['category'];
                    $cnamei = $ropw['id'];
                    if ($cnamei == $category) {
                        echo "<option value='$cnamei' selected>$cname</option>";
                    } else {
                        echo "<option value='$cnamei'>$cname</option>";
                    }
                }
                ?>
              </select>
            </div>

            <!-- Sub-category -->
            <div class="formrow">
              <div class="heading">Sub-Category</div>
              <select name="addscatname" id="addscatname" onchange="get_filterfa()">
                <option value="#">Select Sub-Category</option>
                <?php
                if ($h_t == 2846) {
                    $query2 = "select * from subcategories where cat_id='$category' order by id desc";
                    $resi2 = mysqli_query($con, $query2);
                    while ($ropw2 = mysqli_fetch_assoc($resi2)) {
                        $cname2 = $ropw2['subcat'];
                        $cname2i = $ropw2['id'];
                        if ($cname2i == $subcategory) {
                            echo "<option value='$cname2i' selected>$cname2</option>";
                        } else {
                            echo "<option value='$cname2i'>$cname2</option>";
                        }
                    }
                }
                ?>
              </select>
            </div>

            <!-- Filters (dynamic) -->
            <div class="full" id="filters">
              <?php
              if ($h_t == 2846) {
                  $productSubFilters = array();
                  $subfilterRes = mysqli_query($con, "select * from p_sfilter where pid='$productid'");
                  while ($subfilterRow = mysqli_fetch_assoc($subfilterRes)) {
                      $productSubFilters[] = $subfilterRow['sfid'];
                  }
                  $qn = "select * from filter where subcat_id='$subcategory'";
                  $resn = mysqli_query($con, $qn);
                  $template = '';
                  while ($rown = mysqli_fetch_assoc($resn)) {
                      $template .= '<div class="formrow"><div class="heading">Filter</div>
                          <select class="select" name="productFiltersName" id="addfiltername">
                              <option value="' . $rown['id'] . '">' . $rown['filter'] . '</option>
                          </select></div>
                          <div class="formrow"><div class="heading">Sub Filter</div>
                          <div id="subfilters">';
                      $filtername = $rown['id'];
                      $q2 = "select * from sub_filter where filter_id='$filtername'";
                      $res2 = mysqli_query($con, $q2);
                      while ($row2 = mysqli_fetch_assoc($res2)) {
                          $checked = in_array($row2['id'], $productSubFilters) ? 'checked' : '';
                          $template .= '<span>
                              <input type="checkbox" name="productSubFiltersName" value="' . $row2['id'] . '" ' . $checked . '>
                              ' . $row2['subfilter'] . '
                          </span>';
                      }
                      $template .= '</div></div>';
                  }
                  echo $template;
                  unset($productSubFilters);
              }
              ?>
            </div>

            <!-- Name -->
            <div class="formrow full">
              <div class="heading">Product Name</div>
              <input type="text" placeholder="Enter Product Name *" id="pname" value="<?php echo $name; ?>"/>
            </div>

            <!-- Price -->
            <div class="formrow">
              <div class="heading">Price (MRP)</div>
              <input type="number" placeholder="Enter Product Price *" id="pprice" value="<?php echo $price; ?>" oninput="putacp()"/>
            </div>

            <!-- Selling Price -->
            <div class="formrow">
              <div class="heading">Selling Price</div>
              <input type="number" placeholder="Enter Selling Price *" id="psprice" value="<?php echo $sellprice; ?>" oninput="checkprice()" valueAsNumber/>
            </div>

            <!-- Tax -->
            <div class="formrow">
              <div class="heading">Tax (%)</div>
              <input type="number" placeholder="Enter Tax % *" id="tax" oninput="t_ax()" value="<?php echo $tax; ?>"/>
            </div>

            <!-- Final Price -->
            <div class="formrow">
              <div class="heading">Final Price (Auto)</div>
              <input type="number" placeholder="0.00" id="fa" value="<?php echo $fa; ?>" readonly/>
            </div>

            <!-- SKU -->
            <div class="formrow">
              <div class="heading">SKU (Auto)</div>
              <input type="text" id="sku" value="<?php echo $sku; ?>" readonly/>
            </div>

            <!-- Quantity -->
            <div class="formrow">
              <div class="heading">Quantity</div>
              <input type="number" placeholder="Enter Product Quantity *" id="pqty" value="<?php echo $qty; ?>"/>
            </div>

            <!-- Terms & Conditions -->
            <div class="formrow full">
              <div class="heading">Terms &amp; Conditions</div>
              <textarea name="shrtdsc" id="tc" placeholder="Terms & Conditions *"><?php echo $tc; ?></textarea>
            </div>

            <!-- Short Description -->
            <div class="formrow full">
              <div class="heading">Short Description</div>
              <textarea name="shrtdsc" id="shrtdsc" placeholder="Short Description *"><?php echo $sd; ?></textarea>
            </div>

            <!-- Description -->
            <div class="formrow full">
              <div class="heading">Description</div>
              <textarea class="desc" name="dsc" id="dsc" placeholder="Full Description *" style="min-height:160px"><?php echo $dc; ?></textarea>
            </div>

          </div><!-- /ap-grid -->
        </div><!-- /ap-card-body -->
      </div><!-- /ap-card -->

      <!-- ══ IMAGE UPLOAD CARD ══ -->
      <div class="ap-card">
        <div class="ap-card-bar"></div>
        <div class="ap-card-body">
          <div class="ap-section-title">
            <div class="s-icon"><i class="uil uil-image-upload"></i></div>
            <h2>Product <em>Images</em></h2>
          </div>
          <p style="font-size:.82rem;color:#7a5c36;margin-bottom:20px;line-height:1.6;">
            <i class="uil uil-info-circle" style="color:var(--amber)"></i>
            Images 1 &amp; 2 are <strong>required</strong>. Images 3 &amp; 4 are optional.
            Click or tap a card to choose an image file.
          </p>

          <div class="img-upload-grid">

            <!-- Image 1 -->
            <div class="img-upload-card img-required" onclick="document.getElementById('uploadimage1').click()">
              <div class="img-preview">
                <img src="<?php echo $img1; ?>" id="preview1" alt="Product Image 1"/>
              </div>
              <!-- FIX: input is inside the card but pointer-events handled by JS click above -->
              <input type="file" name="productimage1" id="uploadimage1" accept="image/*"
                     onchange="show_preview('preview1','uploadimage1'); event.stopPropagation();" style="display:none"/>
              <div class="img-badge">Image 1 *</div>
              <div class="img-label"><i class="uil uil-camera"></i> Change</div>
            </div>

            <!-- Image 2 -->
            <div class="img-upload-card img-required" onclick="document.getElementById('uploadimage2').click()">
              <div class="img-preview">
                <img src="<?php echo $img2; ?>" id="preview2" alt="Product Image 2"/>
              </div>
              <input type="file" name="productimage2" id="uploadimage2" accept="image/*"
                     onchange="show_preview('preview2','uploadimage2'); event.stopPropagation();" style="display:none"/>
              <div class="img-badge">Image 2 *</div>
              <div class="img-label"><i class="uil uil-camera"></i> Change</div>
            </div>

            <!-- Image 3 -->
            <div class="img-upload-card img-optional" onclick="document.getElementById('uploadimage3').click()">
              <div class="img-preview">
                <img src="<?php echo $img3; ?>" id="preview3" alt="Product Image 3"/>
              </div>
              <input type="file" name="productimage3" id="uploadimage3" accept="image/*"
                     onchange="show_preview('preview3','uploadimage3'); event.stopPropagation();" style="display:none"/>
              <div class="img-badge" style="background:#7a5c36">Image 3</div>
              <div class="img-label"><i class="uil uil-camera"></i> Change</div>
            </div>

            <!-- Image 4 -->
            <div class="img-upload-card img-optional" onclick="document.getElementById('uploadimage4').click()">
              <div class="img-preview">
                <img src="<?php echo $img4; ?>" id="preview4" alt="Product Image 4"/>
              </div>
              <input type="file" name="productimage4" id="uploadimage4" accept="image/*"
                     onchange="show_preview('preview4','uploadimage4'); event.stopPropagation();" style="display:none"/>
              <div class="img-badge" style="background:#7a5c36">Image 4</div>
              <div class="img-label"><i class="uil uil-camera"></i> Change</div>
            </div>

          </div><!-- /img-upload-grid -->
        </div>
      </div><!-- /ap-card -->

      <!-- ══ SUBMIT CARD ══ -->
      <div class="ap-card">
        <div class="ap-card-bar"></div>
        <div class="ap-card-body">
          <div class="formrow" style="margin-bottom:14px">
            <span id="pdstatus"></span>
          </div>
          <div class="formrow">
            <?php echo $cb; ?>
          </div>
        </div>
      </div>

    </form><!-- /productForm -->
  </div>
</div>

<script>
/* ── IMAGE PREVIEW: keep original function name used by JS files ── */
function show_preview(previewId, inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (input && input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            // Highlight card border on success
            const card = input.closest('.img-upload-card') || input.parentElement.closest('.img-upload-card');
            if (card) { card.style.borderColor = '#c8720a'; card.style.borderStyle = 'solid'; }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php
require("require/foot.php");
?>
