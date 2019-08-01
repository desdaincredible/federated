<?php
session_start();
require_once(ROOT . './../library/db/config.php');
require_once(ROOT . './../library/db/db.php');
require_once(ROOT . './../library/src/PHPMailer.php');
require_once(ROOT . './../library/src/Exception.php');
require_once('model/site_model.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db = new Database();
$model = new model($db);
$email = new PHPMailer(TRUE);


$valid_claim = '';
/* Check to see if they are already logged in */

if (isset($_SESSION['valid_claim']) && $_SESSION['valid_claim'] == 1) {
    $valid_claim = 1;
}


if (!empty($valid_claim)) {
    // user logged in
    $id = $model->saveAndGetId($db);
    if ($id) {
        header("Location: /fed/claim_by_id?claim_id=$id");

//        $email->setFrom('donotreply@ntwclaims.net', 'NTW Website');
//        $email->addAddress('rasel20062007@gmail.com', 'NTW Claims');
////        $email->addCC('zola@zolaweb.com', 'Zola');
////        $email->addCC('dmcneese@abswarranty.net', 'Daniel McNeese');
////        $email->addCC('gpetty@abswarranty.net', 'Gennica Petty');
//
//
//        $email->Subject = 'New NTW claim';
//        $email->isHTML(TRUE);
//        $email->Body = "<h1>Hello Rasel</h1>";
//        $email->AltBody = "Hello Rasel";
//
//        if (!$email->send()) {
//            header("Location: /fed/claim_by_id?claim_id=$id");
//        } else {
//            echo "Unable to send Email. But Your data is saved on our database.";
//        }

    } else {
        echo 'Unable to save.';
    }
    exit();
} else {
    header("Location: /fed");
    exit();
}


