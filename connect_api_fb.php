<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2017
 * Time: 11:42
 */

include 'php-graph-sdk/src/Facebook/autoload.php';

$app_id = "295291614252621";
$secret = "46f90e32478fe292c2d77dcd06cc8d14";

$fb = new Facebook\Facebook([
    'app_id' => $app_id, // Replace {app-id} with your app id
    'app_secret' => $secret,
    'default_graph_version' => 'v2.2',
]);
$helper = $fb->getRedirectLoginHelper();