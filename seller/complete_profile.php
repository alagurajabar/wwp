<?php
require("require/top.php");
if (!isset($_GET['hash'])) {
    redirect('index.php');
    die();
}
authorise($con);
authenticate_seller($_GET['hash']);
$rt = 0;
if (isset($_GET['rt'])) {
    $rt = $_GET['rt'];
}
$sid = $_SESSION['SELLER_ID'];
$fullname = '';
$type = '';
$bname = '';
$cntry = '';
$state = '';
$city = '';
$pin = '';
$is_gst = '';
$gstnum = '';
$acn = '';
$ach = '';
$bank = '';
$branch = '';
$ifsc = '';
$b_sc = "../assets/images/product/big-2.jpg";
$g_sc = "../assets/images/product/big-2.jpg";
$a_sc = "../assets/images/product/big-2.jpg";
$p_sc = "../assets/images/product/big-2.jpg";
$query = "select * from sellers where id='$sid'";
$seller_res = mysqli_query($con, $query);
$seller_row = mysqli_fetch_assoc($seller_res);
$is_approve = $seller_row['isapp'];
$cp = $seller_row['is_cp'];
if ($is_approve == 2 && $cp == 2 || isset($_GET['rt'])) {
    $fullname = $seller_row['f_name'];
    $type = $seller_row['tob'];
    $bname = $seller_row['b_name'];
    $cntry = $seller_row['country'];
    $state = $seller_row['state'];
    $city = $seller_row['city'];
    $pin = $seller_row['pin'];
    $is_gst = $seller_row['is_gst'];
    $gstnum = $seller_row['gst_id'];
    $acn = $seller_row['acc_num'];
    $ach = $seller_row['acc_holder'];
    $bank = $seller_row['bank'];
    $branch = $seller_row['branch'];
    $ifsc = $seller_row['ifsc'];
    if (!empty($seller_row['b_crft'])) {
        $b_sc = "../media/seller_profile/" . $seller_row['b_crft'];
    }
    if (!empty($seller_row['gst_crft'])) {
        $g_sc = "../media/seller_profile/" . $seller_row['gst_crft'];
    }
    if (!empty($seller_row['adhar'])) {
        $a_sc = "../media/seller_profile/" . $seller_row['adhar'];
    }
    if (!empty($seller_row['pan'])) {
        $p_sc = "../media/seller_profile/" . $seller_row['pan'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
<style>
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

  *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
  html { scroll-behavior:smooth; }
  body { font-family:var(--font-body); background:var(--cream); color:var(--ink); overflow-x:hidden; min-height:100vh; }
  a    { text-decoration:none; color:inherit; }

  @keyframes fadeUp  { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
  @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
  @keyframes tagPop  { 0%{transform:scale(.85)} 60%{transform:scale(1.05)} 100%{transform:scale(1)} }
  @keyframes floatY  { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
  @keyframes bounceX { 0%,100%{transform:translateX(0)} 50%{transform:translateX(4px)} }

  .container { width:min(900px,92%); margin:0 auto; }

  /* ── BREADCRUMB / PATH ── */
  .path {
    background:var(--parchment);
    border-bottom:1px solid var(--mist);
    padding:12px 0;
    animation:fadeIn .4s ease;
  }
  .path .container { display:flex; align-items:center; gap:6px; font-size:.82rem; color:#7a5c36; }
  .path a { color:var(--espresso); font-weight:500; transition:color .2s; }
  .path a:hover { color:var(--amber); }
  .path span { color:var(--amber); }

  /* ── PAGE HERO ── */
  .page-hero {
    background:linear-gradient(155deg,#1a0d03 0%,#4a2008 45%,#b86208 100%);
    padding:44px 0;
    position:relative; overflow:hidden;
    animation:fadeIn .6s ease;
  }
  .page-hero::before {
    content:''; position:absolute; bottom:-60px; right:-60px;
    width:300px; height:300px; background:rgba(255,255,255,.04); border-radius:50%;
    pointer-events:none;
  }
  .page-hero::after {
    content:''; position:absolute; top:-50px; left:-50px;
    width:200px; height:200px; background:rgba(233,168,50,.07); border-radius:50%;
    pointer-events:none;
  }
  .hero-inner { position:relative; z-index:2; }
  .hero-tag {
    display:inline-flex; align-items:center; gap:8px;
    font-size:.7rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase;
    color:var(--gold); margin-bottom:8px;
  }
  .hero-tag::before { content:''; display:block; width:18px; height:2px; background:var(--gold); border-radius:2px; }
  .page-hero h1 {
    font-family:var(--font-head);
    font-size:clamp(1.6rem,3vw,2.4rem); color:var(--white);
    line-height:1.2;
  }
  .page-hero h1 em { color:var(--gold); font-style:italic; }

  /* ── MAIN WRAPPER ── */
  .cp-section { padding:48px 0 80px; }

  /* ── FORM CARD ── */
  .form-card {
    background:var(--white);
    border:1px solid var(--mist);
    border-radius:var(--radius);
    overflow:hidden;
    box-shadow:var(--shadow);
    animation:fadeUp .7s ease both;
  }
  .form-card-bar {
    height:4px;
    background:linear-gradient(90deg,var(--amber),var(--gold));
  }
  .form-card-inner { padding:40px 44px 48px; }

  /* ── SECTION TITLE ── */
  .section-title {
    display:flex; align-items:center; gap:12px;
    margin:0 0 24px;
    padding-bottom:14px;
    border-bottom:1px solid var(--mist);
  }
  .section-title .s-icon {
    width:40px; height:40px; border-radius:11px; flex-shrink:0;
    background:linear-gradient(135deg,var(--amber),var(--gold));
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; color:var(--white);
    animation:floatY 3s ease infinite;
  }
  .section-title h2 {
    font-family:var(--font-head);
    font-size:1.4rem; font-weight:700;
    color:var(--espresso);
  }
  .section-title h2 em { color:var(--amber); font-style:italic; }
  .section-spacer { margin-top:40px; }

  /* ── FORM ROWS ── */
  .cp-form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px 24px;
  }
  .cp-form-grid .full { grid-column:1 / -1; }

  .formrow {
    display:flex; flex-direction:column; gap:7px;
  }
  .formrow .heading {
    font-size:.72rem; font-weight:600;
    letter-spacing:.12em; text-transform:uppercase;
    color:var(--espresso);
  }
  .formrow input,
  .formrow select,
  .formrow textarea {
    font-family:var(--font-body); font-size:.88rem;
    background:var(--cream); border:1.5px solid var(--mist);
    border-radius:10px; padding:11px 15px;
    color:var(--ink); outline:none; width:100%;
    transition:border-color .25s, background .25s, box-shadow .25s;
    appearance:auto;
    margin:0 !important;  /* override any inline margins */
  }
  .formrow input::placeholder,
  .formrow textarea::placeholder { color:#b09878; }
  .formrow select option { background:var(--white); color:var(--ink); }
  .formrow input:focus,
  .formrow select:focus,
  .formrow textarea:focus {
    border-color:var(--amber); background:var(--white);
    box-shadow:0 0 0 3px rgba(200,114,10,.12);
  }

  /* ── STATUS MESSAGE ── */
  #pdstatus {
    font-size:.88rem !important;
    font-weight:600;
    border-radius:10px;
    padding:10px 16px;
    background:rgba(200,114,10,.07);
    border:1.5px solid rgba(200,114,10,.25);
    display:block;
  }
  #pdstatus:empty { display:none; }

  /* ── SUBMIT BUTTON ── */
  .btn-submit-row { margin-top:8px; }
  .btn-cp {
    display:inline-flex; align-items:center; justify-content:center; gap:9px;
    padding:14px 36px; border-radius:10px;
    background:var(--espresso); color:var(--white);
    font-family:var(--font-body); font-size:.92rem; font-weight:600;
    border:none; cursor:pointer; text-decoration:none;
    transition:all .28s cubic-bezier(.34,1.56,.64,1);
    width:100%;
  }
  .btn-cp:hover {
    background:var(--amber);
    transform:translateY(-3px);
    box-shadow:0 10px 30px rgba(200,114,10,.35);
  }
  .btn-cp i { animation:bounceX 1.8s ease infinite; font-size:1.1rem; }

  /* ── DIVIDER ── */
  .cp-divider {
    border:none; border-top:1px dashed var(--mist);
    margin:36px 0 0;
  }

  @media(max-width:680px){
    .form-card-inner { padding:28px 20px 36px; }
    .cp-form-grid { grid-template-columns:1fr; }
    .cp-form-grid .full { grid-column:1; }
  }
</style>

<!-- ── BREADCRUMB ── -->
<div class="path">
  <div class="container">
    <a href="index.php">Dashboard</a>
    <span>/</span>
    <a href="complete_profile.php?hash=<?php echo hash_code() ?>">Complete Profile</a>
  </div>
</div>

<!-- ── PAGE HERO ── -->
<div class="page-hero">
  <div class="container">
    <div class="hero-inner">
      <div class="hero-tag">Seller Dashboard</div>
      <h1>Complete Your <em>Profile</em></h1>
    </div>
  </div>
</div>

<!-- ── MAIN FORM SECTION ── -->
<section class="cp-section">
  <div class="container">
    <div class="form-card">
      <div class="form-card-bar"></div>
      <div class="form-card-inner">
        <form action="#">

          <!-- ── BASIC DETAILS ── -->
          <div class="section-title">
            <div class="s-icon"><i class="uil uil-user-circle"></i></div>
            <h2>Basic <em>Details</em></h2>
          </div>
          <div class="cp-form-grid">
            <div class="formrow">
              <div class="heading">Full Name</div>
              <input type="text" placeholder="Enter Your Full Name" id="seller_full_name" value="<?php echo $fullname; ?>">
            </div>
            <div class="formrow">
              <div class="heading">Email</div>
              <input type="email" placeholder="Enter Email Id" id="email" value="<?php echo $seller_row['email']; ?>">
            </div>
            <div class="formrow">
              <div class="heading">Mobile</div>
              <input type="text" placeholder="Enter Mobile Number" id="mobile" value="<?php echo $seller_row['mobile']; ?>">
            </div>
            <div class="formrow">
              <div class="heading">Address</div>
              <input type="text" placeholder="Enter Full Address" id="address" value="<?php echo $seller_row['address']; ?>">
            </div>
          </div>

          <hr class="cp-divider">

          <!-- ── BUSINESS DETAILS ── -->
          <div class="section-title section-spacer">
            <div class="s-icon"><i class="uil uil-store-alt"></i></div>
            <h2>Business <em>Details</em></h2>
          </div>
          <div class="cp-form-grid">
            <div class="formrow">
              <div class="heading">Type</div>
              <select class="select" name="addscatname" id="seller_b_type">
                <option value="">Select Business Type</option>
                <?php
                $queryi = "select * from business_type order by id desc";
                $resi = mysqli_query($con, $queryi);
                while ($rowi = mysqli_fetch_assoc($resi)) {
                    if ($rowi['id'] == $type) { ?>
                        <option value="<?php echo $rowi['id']; ?>" selected><?php echo $rowi['type']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $rowi['id']; ?>"><?php echo $rowi['type']; ?></option>
                    <?php }
                } ?>
              </select>
            </div>
            <div class="formrow">
              <div class="heading">Business Name</div>
              <input type="text" placeholder="Enter Your Business Name" id="seller_b_name" value="<?php echo $bname; ?>">
            </div>
            <div class="formrow">
              <div class="heading">Country</div>
              <select class="select" id="fsc" onchange="getstatelist()">
                <option value="">Select Country</option>
                <?php
                $query = "select * from country order by id desc";
                $res = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($res)) {
                    if ($row['id'] == $cntry) { ?>
                        <option value="<?php echo $row['id']; ?>" selected><?php echo $row['cntry_name']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['cntry_name']; ?></option>
                    <?php }
                } ?>
              </select>
            </div>
            <div class="formrow">
              <div class="heading">State</div>
              <?php if ($state == '') { ?>
                <select class="select" id="fscb" onchange="getcitylist()">
                  <option value="">Select State</option>
                </select>
              <?php } else { ?>
                <select class="select" id="fscb" onchange="getcitylist()">
                  <?php
                  $querys = "select * from state where c_id='$cntry' order by id desc";
                  $ress = mysqli_query($con, $querys);
                  while ($rows = mysqli_fetch_assoc($ress)) {
                      if ($rows['id'] == $state) { ?>
                          <option value="<?php echo $rows['id']; ?>" selected><?php echo $rows['state_name']; ?></option>
                      <?php } else { ?>
                          <option value="<?php echo $rows['id']; ?>"><?php echo $rows['state_name']; ?></option>
                      <?php }
                  } ?>
                </select>
              <?php } ?>
            </div>
            <div class="formrow">
              <div class="heading">City</div>
              <?php if ($city == '') { ?>
                <select class="select" id="fscb2" onchange="getpinlist()">
                  <option value="">Select City</option>
                </select>
              <?php } else { ?>
                <select class="select" id="fscb2" onchange="getcitylist()">
                  <?php
                  $querys = "select * from city where s_id='$state' order by id desc";
                  $ress = mysqli_query($con, $querys);
                  while ($rows = mysqli_fetch_assoc($ress)) {
                      if ($rows['id'] == $city) { ?>
                          <option value="<?php echo $rows['id']; ?>" selected><?php echo $rows['city_name']; ?></option>
                      <?php } else { ?>
                          <option value="<?php echo $rows['id']; ?>"><?php echo $rows['city_name']; ?></option>
                      <?php }
                  } ?>
                </select>
              <?php } ?>
            </div>
            <div class="formrow">
              <div class="heading">Pincode</div>
              <?php if ($city == '') { ?>
                <select class="select" id="fscb3">
                  <option value="">Select Pincode</option>
                </select>
              <?php } else { ?>
                <select class="select" id="fscb3" onchange="getcitylist()">
                  <?php
                  $querys = "select * from pin where c_id='$city' order by id desc";
                  $ress = mysqli_query($con, $querys);
                  while ($rows = mysqli_fetch_assoc($ress)) {
                      if ($rows['id'] == $pin) { ?>
                          <option value="<?php echo $rows['id']; ?>" selected><?php echo $rows['pincode']; ?></option>
                      <?php } else { ?>
                          <option value="<?php echo $rows['id']; ?>"><?php echo $rows['pincode']; ?></option>
                      <?php }
                  } ?>
                </select>
              <?php } ?>
            </div>
            <div class="formrow">
              <div class="heading">GST Registered?</div>
              <?php if ($is_gst == '') { ?>
                <select class="select" name="addscatname" id="isgst" onchange="is_gst()">
                  <option value="">Select GST</option>
                  <option value="1">Yes</option>
                  <option value="2">No</option>
                </select>
              <?php } elseif ($is_gst == 1) { ?>
                <select class="select" name="addscatname" id="isgst" onchange="is_gst()">
                  <option value="1" selected>Yes</option>
                  <option value="2">No</option>
                </select>
              <?php } else { ?>
                <select class="select" name="addscatname" id="isgst" onchange="is_gst()">
                  <option value="1">Yes</option>
                  <option value="2" selected>No</option>
                </select>
              <?php } ?>
            </div>
            <?php if ($is_gst == '' || $is_gst == 2) { ?>
              <div class="formrow" id="isgst1" style="display:none;">
                <div class="heading">GST Number</div>
                <input type="text" placeholder="Enter GST Number" id="seller_gst_num" value="<?php echo $gstnum; ?>"/>
              </div>
            <?php } else { ?>
              <div class="formrow" id="isgst1">
                <div class="heading">GST Number</div>
                <input type="text" placeholder="Enter GST Number" id="seller_gst_num" value="<?php echo $gstnum; ?>"/>
              </div>
            <?php } ?>
          </div>

          <hr class="cp-divider">

          <!-- ── BANK DETAILS ── -->
          <div class="section-title section-spacer">
            <div class="s-icon"><i class="uil uil-university"></i></div>
            <h2>Bank <em>Details</em></h2>
          </div>
          <div class="cp-form-grid">
            <div class="formrow">
              <div class="heading">Account Number</div>
              <input type="number" placeholder="Enter Bank Account Number" id="seller_ac" value="<?php echo $acn; ?>"/>
            </div>
            <div class="formrow">
              <div class="heading">Account Holder's Name</div>
              <input type="text" id="seller_bank_holder" placeholder="Enter Account Holder's Name" value="<?php echo $ach; ?>"/>
            </div>
            <div class="formrow">
              <div class="heading">Bank Name</div>
              <input type="text" id="seller_bank_name" placeholder="Enter Bank Name" value="<?php echo $bank; ?>"/>
            </div>
            <div class="formrow">
              <div class="heading">Branch Name</div>
              <input type="text" id="seller_branch_name" placeholder="Enter Branch Name" value="<?php echo $branch; ?>"/>
            </div>
            <div class="formrow">
              <div class="heading">IFSC Code</div>
              <input type="text" id="seller_ifsc" placeholder="Enter IFSC Code" value="<?php echo $ifsc; ?>"/>
            </div>
          </div>

          <!-- ── STATUS & SUBMIT ── -->
          <div class="formrow" style="margin-top:24px;">
            <span id="pdstatus"></span>
          </div>
          <div class="formrow btn-submit-row">
            <a href="javascript:void(0)" class="btn-cp" onclick="completep(<?php echo $rt; ?>)">
              <i class="uil uil-check-circle"></i>
              <span>Save &amp; Complete Profile</span>
            </a>
          </div>

        </form>
      </div><!-- /form-card-inner -->
    </div><!-- /form-card -->
  </div>
</section>
</html>
<?php
require("require/foot.php");
?>
