<?php
session_start();
require_once(ROOT . './../library/db/config.php');
require_once(ROOT . './../library/db/db.php');
$db = new Database();
require_once('model/site_model.php');
$model = new model($db);

$valid_claim = '';
$show_form = '';
$info_page = '';
$message = '';

/* Check to see if they are already logged in */

if (isset($_SESSION['valid_claim']) && $_SESSION['valid_claim'] == 1) {
    $valid_claim = 1;
}

if (!empty($_POST['dealer_zip']) && !empty($_POST['dealer_phone'])) {
    $valid_claim = $model->claimLogin($db, $site);
    if (empty($valid_claim)) {
        $message = '<p style="text-align: center;color: #990000"><strong>Incorrect login, please check your information and try again, or call:</strong> <br><br><strong>T3 local claims:</strong> 1-866-830-4191<br>
		<strong>Tread Car Dealer claims:</strong> 1-855-429-2790</p>';
    }
}

$content = $model->getHeaderValues($db, $site, $page_id);
$header = file_get_contents('templates/header.html');

if (!empty($valid_claim)) {
    $body_copy = file_get_contents('templates/' . $page_id . '.html');
} else {
    $body_copy = file_get_contents('templates/login.html');
    $content['FORM_ACTION'] = 'claim_by_id';
    $content['PAGE_TITLE'] = '<p class="content_header">Claims History</p>';
}
$content['MESSAGE'] = $message;

if (isset($_GET['claim_id'])) {
    $claim = $model->getClaimById($db, $_GET['claim_id']);
    if ($claim) {
        $body = '<h3>Showing Claim Details For Id: ' . $claim['claim_id'] . '</h3>';
        $body .= "<div>Original Invoice Number: " . $claim['invoice_number'] . "</div>";
        $body .= "<div>Original Repair Date: " . $claim['original_repair_date'] . "</div>";
        $body .= "<div>Sub Repair Date: " . $claim['sub_repair_date'] . "</div>";
        $body .= "<div>Original Repair Mileage Reading: " . $claim['original_repair_mileage'] . "</div>";
        $body .= "<div>Current Mileage Reading: " . $claim['current_mileage'] . "</div>";
        $body .= "<div>Customer First Name: " . $claim['customer_first_name'] . "</div>";
        $body .= "<div>Customer Last Name: " . $claim['customer_last_name'] . "</div>";
        $body .= "<div>Customer Phone: " . $claim['customer_phone'] . "</div>";
        $body .= "<div>Customer Email: " . $claim['customer_email'] . "</div>";
        $body .= "<div>Vehicle Year: " . $claim['vehicle_year'] . "</div>";
        $body .= "<div>Vehicle Make: " . $claim['make_name'] . "</div>";
        $body .= "<div>Vehicle Model: " . $claim['vehicle_model'] . "</div>";
        $body .= "<div>Repair Code:: " . $claim['repair_code'] . "," . $claim['repair_type'] . "," . $claim['component'] . "</div>";
        $body .= "<div>Original Labor Price: " . $claim['original_labor_price'] . "</div>";
        $body .= "<div>Labor Per Hour: " . $claim['labor_price'] . "</div>";
        $body .= "<div>Original Labor Hours: " . $claim['labor_hour'] . "</div>";
        $body .= "<div>Sub Labor Price: " . $claim['sub_labor_price'] . "</div>";
        $body .= "<div>Repair Description: " . $claim['repair_description'] . "</div>";
        $content['BODY'] = $body;
    } else {
        $content['BODY'] = 'No Claim found for this Id.';
    }
} else {
    $content['BODY'] = 'No Id Provided.';
}

$footer = file_get_contents('templates/footer.html');
$finished_page = $header . $body_copy . $footer;


foreach ($content as $key => $value) {
    $finished_page = str_replace('{' . strtoupper($key) . '}', $value, $finished_page);
}

echo $finished_page;
