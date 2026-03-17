<?php
require("require/top.php");

if (!isset($_GET['hash'])) {
    redirect('index.php');
    die();
}

authorise($con);
authenticate_seller($_GET['hash']);

$sid = $_SESSION['SELLER_ID'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic server-side validation
    $fullname = get_safe_value($con, $_POST['seller_full_name'] ?? '');
    $email = get_safe_value($con, $_POST['email'] ?? '');
    $mobile = get_safe_value($con, $_POST['mobile'] ?? '');
    $address = get_safe_value($con, $_POST['address'] ?? '');
    $type = get_safe_value($con, $_POST['seller_b_type'] ?? '');
    $bname = get_safe_value($con, $_POST['seller_b_name'] ?? '');
    $cntry = get_safe_value($con, $_POST['fsc'] ?? '');
    $state = get_safe_value($con, $_POST['fscb'] ?? '');
    $city = get_safe_value($con, $_POST['fscb2'] ?? '');
    $pin = get_safe_value($con, $_POST['fscb3'] ?? '');
    $is_gst = get_safe_value($con, $_POST['isgst'] ?? '');
    $gstnum = get_safe_value($con, $_POST['seller_gst_num'] ?? '');
    $acn = get_safe_value($con, $_POST['seller_ac'] ?? '');
    $ach = get_safe_value($con, $_POST['seller_bank_holder'] ?? '');
    $bank = get_safe_value($con, $_POST['seller_bank_name'] ?? '');
    $branch = get_safe_value($con, $_POST['seller_branch_name'] ?? '');
    $ifsc = get_safe_value($con, $_POST['seller_ifsc'] ?? '');

    if ($fullname === '' || $email === '' || $mobile === '' || $address === '') {
        $message = "Please fill in all required fields.";
    } else {
        $q = "UPDATE sellers SET 
            email='$email',
            mobile='$mobile',
            address='$address',
            f_name='$fullname',
            tob='$type',
            b_name='$bname',
            country='$cntry',
            state='$state',
            city='$city',
            pin='$pin',
            is_gst='$is_gst',
            gst_id='$gstnum',
            acc_num='$acn',
            acc_holder='$ach',
            bank='$bank',
            branch='$branch',
            ifsc='$ifsc',
            is_cp='1',
            isapp='0',
            is_new='1'
            WHERE id='$sid'";
        if (mysqli_query($con, $q)) {
            $message = "Profile updated successfully.";
        } else {
            $message = "Something went wrong while saving your profile.";
        }
    }
}

// Reload seller details to show any updated values
$query = "SELECT * FROM sellers WHERE id='$sid'";
$seller_res = mysqli_query($con, $query);
$seller_row = mysqli_fetch_assoc($seller_res);

// Default values
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
?>

<div class="path">
    <div class="container">
        <a href="index.php">Home</a>
        /
        <a href="complete_profile_simple.php?hash=<?php echo hash_code(); ?>">Complete profile</a>
    </div>
</div>

<div class="cartrow" id="catrow">
    <div class="gh">
        <div class="heading">
            <h3>Complete Profile (Simple)</h3>
        </div>

        <?php if ($message !== "") { ?>
            <div style="margin:1.5rem 0; padding:1rem; border-radius:6px; background:#e7f7e7; color:#2a6a2a;">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <div class="maincontainer2">
            <form method="post" action="" autocomplete="off">
                <h1 style="color:#556ee6" class="mt3">Basic Details</h1>

                <div class="formrow">
                    <div class="heading">Full Name</div>
                    <input type="text" placeholder="Enter Your Full Name" name="seller_full_name" value="<?php echo $fullname; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">Email</div>
                    <input type="email" placeholder="Enter Email Id" name="email" value="<?php echo $seller_row['email']; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">Mobile</div>
                    <input type="text" placeholder="Enter Mobile Number" name="mobile" value="<?php echo $seller_row['mobile']; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">Address</div>
                    <input type="text" placeholder="Enter Full Address" name="address" value="<?php echo $seller_row['address']; ?>" />
                </div>

                <h1 style="color:#556ee6" class="mt3">Business Details</h1>

                <div class="formrow">
                    <div class="heading">Type</div>
                    <select class="select" name="seller_b_type">
                        <option value="">Select Business Type</option>
                        <?php
                        $queryi = "select * from business_type order by id desc";
                        $resi = mysqli_query($con, $queryi);
                        while ($rowi = mysqli_fetch_assoc($resi)) {
                            $selected = $rowi['id'] == $type ? 'selected' : '';
                        ?>
                            <option value="<?php echo $rowi['id']; ?>" <?php echo $selected; ?>><?php echo $rowi['type']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="formrow">
                    <div class="heading">Business name</div>
                    <input type="text" placeholder="Enter Your Business Name" name="seller_b_name" value="<?php echo $bname; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">Country</div>
                    <select class="select" name="fsc" onchange="getstatelist()">
                        <option value="">Select Country</option>
                        <?php
                        $query = "select * from country order by id desc";
                        $res = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_assoc($res)) {
                            $selected = $row['id'] == $cntry ? 'selected' : '';
                        ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>><?php echo $row['cntry_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="formrow">
                    <div class="heading">State</div>
                    <select class="select" name="fscb" onchange="getcitylist()">
                        <option value="">Select State</option>
                        <?php
                        if ($cntry != '') {
                            $querys = "select * from state where c_id='$cntry' order by id desc";
                            $ress = mysqli_query($con, $querys);
                            while ($rows = mysqli_fetch_assoc($ress)) {
                                $selected = $rows['id'] == $state ? 'selected' : '';
                        ?>
                                <option value="<?php echo $rows['id']; ?>" <?php echo $selected; ?>><?php echo $rows['state_name']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>

                <div class="formrow">
                    <div class="heading">City</div>
                    <select class="select" name="fscb2" onchange="getpinlist()">
                        <option value="">Select City</option>
                        <?php
                        if ($state != '') {
                            $querys = "select * from city where s_id='$state' order by id desc";
                            $ress = mysqli_query($con, $querys);
                            while ($rows = mysqli_fetch_assoc($ress)) {
                                $selected = $rows['id'] == $city ? 'selected' : '';
                        ?>
                                <option value="<?php echo $rows['id']; ?>" <?php echo $selected; ?>><?php echo $rows['city_name']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>

                <div class="formrow">
                    <div class="heading">Pincode</div>
                    <select class="select" name="fscb3">
                        <option value="">Select Pincode</option>
                        <?php
                        if ($city != '') {
                            $querys = "select * from pin where c_id='$city' order by id desc";
                            $ress = mysqli_query($con, $querys);
                            while ($rows = mysqli_fetch_assoc($ress)) {
                                $selected = $rows['id'] == $pin ? 'selected' : '';
                        ?>
                                <option value="<?php echo $rows['id']; ?>" <?php echo $selected; ?>><?php echo $rows['pincode']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>

                <div class="formrow">
                    <div class="heading">GST</div>
                    <select class="select" name="isgst" id="isgst" onchange="is_gst()">
                        <option value="">Select GST</option>
                        <option value="1" <?php echo $is_gst == 1 ? 'selected' : ''; ?>>Yes</option>
                        <option value="2" <?php echo $is_gst == 2 ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="formrow" id="isgst1" style="display: <?php echo $is_gst == 1 ? 'block' : 'none'; ?>;">
                    <div class="heading">GST Number</div>
                    <input type="text" placeholder="Enter GST number" name="seller_gst_num" value="<?php echo $gstnum; ?>" />
                </div>

                <h1 style="color:#556ee6" class="mt3">Bank Details</h1>

                <div class="formrow">
                    <div class="heading">Account Number</div>
                    <input type="text" placeholder="Enter bank account number" name="seller_ac" value="<?php echo $acn; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">Account Holder's Name</div>
                    <input type="text" name="seller_bank_holder" placeholder="Enter account holder's name" value="<?php echo $ach; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">Bank Name</div>
                    <input type="text" name="seller_bank_name" placeholder="Enter bank name" value="<?php echo $bank; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">Branch Name</div>
                    <input type="text" name="seller_branch_name" placeholder="Enter Branch name" value="<?php echo $branch; ?>" />
                </div>

                <div class="formrow">
                    <div class="heading">IFSC code</div>
                    <input type="text" name="seller_ifsc" placeholder="Enter IFSC code" value="<?php echo $ifsc; ?>" />
                </div>

                <div class="formrow">
                    <button type="submit" class="btn d-flex-center-a-j bg-main br-15">
                        <i class="uil uil-plus"></i>
                        <span>Save Profile</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require("require/foot.php");
?>
