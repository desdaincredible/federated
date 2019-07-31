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
    $content['FORM_ACTION'] = 'claims';
    $content['PAGE_TITLE'] = '<p class="content_header">Claims History</p>';
}
$content['MESSAGE'] = $message;

$footer = file_get_contents('templates/footer.html');
$finished_page = $header . $body_copy . $footer;


foreach ($content as $key => $value) {
    $finished_page = str_replace('{' . strtoupper($key) . '}', $value, $finished_page);
}

echo $finished_page;
