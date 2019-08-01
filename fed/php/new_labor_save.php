<?php
session_start();
require_once(ROOT . './../library/db/config.php');
require_once(ROOT . './../library/db/db.php');
$db = new Database();
require_once('model/site_model.php');
$model = new model($db);

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
    } else {
        echo 'Unable to save.';
    }
    exit();
} else {
    header("Location: /fed");
    exit();
}


