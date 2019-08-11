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

        $message = '<p style="text-align: center;color: #990000"><strong>Incorrect login, please check your information and try again, or call 1-888-450-2816.</p>';

    }

}



$content = $model->getHeaderValues($db, $site, $page_id);

$header = file_get_contents('templates/header.html');



if (!empty($valid_claim)) {

    $body_copy = file_get_contents('templates/' . $page_id . '.html');

} else {

    $body_copy = file_get_contents('templates/login.html');

    $content['FORM_ACTION'] = 'claim_by_id';

    $content['PAGE_TITLE'] = 'Claim Submitted';

}

$content['MESSAGE'] = $message;



if (isset($_GET['claim_id'])) {

    $claim = $model->getClaimById($db, $_GET['claim_id']);

    if ($claim) {

        $body = '';

        if (isset($_GET['success'])) {

            $body .= '<p class="content_header">The following claim has been sent.</p>
            
            <p>If there is a problem, please call 1-888-450-2816</p>';

        }
        
        $body .= "<div class='claim-confirm'>";

        $body .= '<h2>Claim Id: ' . $claim['claim_id'] . '</h2>';

        $body .= "<div class='claim-field'><div class='claim-label'>Original Invoice Number: </div><div class='claim-value'>" . $claim['invoice_number'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Original Repair Date: </div><div class='claim-value'>" . $claim['original_repair_date'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Sub Invoice Number: </div><div class='claim-value'>" . $claim['sub_invoice_number'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Sub Repair Date: </div><div class='claim-value'>" . $claim['sub_repair_date'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Original Repair Mileage Reading: </div><div class='claim-value'>" . $claim['original_repair_mileage'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Current Mileage Reading: </div><div class='claim-value'>" . $claim['current_mileage'] . "</div></div>";
        
        $body .= "<h3>Customer Information</h3>";

        $body .= "<div class='claim-field'><div class='claim-label'>Customer First Name: </div><div class='claim-value'>" . $claim['customer_first_name'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Customer Last Name: </div><div class='claim-value'>" . $claim['customer_last_name'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Customer Phone: </div><div class='claim-value'>" . $claim['customer_phone'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Customer Email: </div><div class='claim-value'>" . $claim['customer_email'] . "</div></div>";
        
        $body .= "<h3>Vehicle Information</h3>";

        $body .= "<div class='claim-field'><div class='claim-label'>Vehicle Year: </div><div class='claim-value'>" . $claim['vehicle_year'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Vehicle Make: </div><div class='claim-value'>" . $claim['make_name'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Vehicle Model: </div><div class='claim-value'>" . $claim['vehicle_model'] . "</div></div>";
        
        $body .= "<h3>Repair Information</h3>";

        $body .= "<div class='claim-field'><div class='claim-label'>Repair Code:: </div><div class='claim-value'>" . $claim['repair_code'] . "," . $claim['repair_type'] . "," . $claim['component'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Original Labor Price: </div><div class='claim-value'>" . $claim['original_labor_price'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Labor Per Hour: </div><div class='claim-value'>" . $claim['labor_price'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Original Labor Hours: </div><div class='claim-value'>" . $claim['labor_hour'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Sub Labor Price: </div><div class='claim-value'>" . $claim['sub_labor_price'] . "</div></div>";

        $body .= "<div class='claim-field'><div class='claim-label'>Repair Description: </div><div class='claim-value'>" . $claim['repair_description'] . "</div></div>";
        
        $body .= "</div>";

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

