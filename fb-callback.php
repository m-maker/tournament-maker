<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2017
 * Time: 11:40
 */

include 'conf.php';
include 'connect_api_fb.php';

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (! isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
//echo '<h3>Access Token</h3>';
//var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
//echo '<h3>Metadata</h3>';
//var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($app_id); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
        exit;
    }

    //echo '<h3>Long-lived</h3>';
    //var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;
$accessToken = $_SESSION["fb_access_token"];

try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,name,email', $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
$user = $response->getGraphUser();

$img_url = 'http://graph.facebook.com/'.$user["id"].'/picture';

$req_user_exist = $db->prepare("SELECT * FROM membres WHERE membre_mail = :mail OR membre_fb_id = :fb_id");
$req_user_exist->bindValue(":mail", $user["email"], PDO::PARAM_STR);
$req_user_exist->bindValue(":fb_id", $user["id"], PDO::PARAM_STR);
$req_user_exist->execute();

if ($req_user_exist->rowCount() == 0) {

    $pseudo = $user["name"];
    $pass = "fb_connect_".chaineRandom(5);
    $tel = "no";
    $mail = $user["email"];
    $code = chaineRandom(20);

    // AJOUT DU NOUVEL AVATAR (photo de profil)
    $req_id_av = $db->query("SELECT MAX(avatar_id) FROM avatars;");
    $req_id_av->execute();
    $av_id = $req_id_av->fetchColumn() + 1;
    $req_av = $db->prepare("INSERT INTO avatars (avatar_id, avatar_url, avatar_statut) VALUES (:av_id, :av_url, 0)");
    $req_av->bindValue(":av_id", $av_id, PDO::PARAM_INT);
    $req_av->bindValue(":av_url", $img_url, PDO::PARAM_STR);
    $req_av->execute();

    // AJOUT DU NOUVEAU MEMBRE
    $req_id = $db->query("SELECT MAX(id) FROM membres;");
    $req_id->execute();
    $id = $req_id->fetchColumn() + 1;
    $req = $db->prepare("INSERT INTO membres (id, membre_pseudo, membre_pass, membre_tel, membre_mail, membre_orga, membre_date_inscription, membre_derniere_connexion, membre_ip_inscription, membre_ip_derniere_connexion, membre_code_validation, membre_validation, membre_dpt_code, membre_avatar, membre_fb_id) VALUES (:id, :pseudo, :pass, :tel, :mail, 0, NOW(), NOW(), :ip, :ip, :code, 0, null, :av_id, :fb_id)");
    $req->bindParam(":id", $id, PDO::PARAM_INT);
    $req->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $req->bindParam(":pass", $pass, PDO::PARAM_STR);
    $req->bindParam(":tel", $tel, PDO::PARAM_STR);
    $req->bindParam(":mail", $mail, PDO::PARAM_STR);
    $req->bindParam(":code", $code, PDO::PARAM_STR);
    $req->bindParam(":ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $req->bindParam(":av_id", $av_id, PDO::PARAM_INT);
    $req->bindValue(":fb_id", $user["id"], PDO::PARAM_STR);
    $req->execute();
}else{
    $utilisateur = $req_user_exist->fetch();
    $req = $db->prepare("UPDATE membres SET membre_derniere_connexion = NOW(), membre_ip_derniere_connexion = :ip WHERE membre_id = :id ");
    $req->bindParam(":ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $req->bindParam(":id", $utilisateur["membre_id"], PDO::PARAM_INT);
    $req->execute();
    $id = $utilisateur["membre_id"];
    $pseudo = $utilisateur['membre_pseudo'];
    $mail = $utilisateur["membre_mail"];
}

$_SESSION["id"] = $id;
$_SESSION["pseudo"] = $pseudo;
$_SESSION["membre_mail"] = $mail;
$req_orga = $db->prepare('SELECT * FROM membres WHERE id = :membre_id');
$req_orga->execute(array(
    'membre_id' => $id
    ));
$res_orga = $req_orga->fetch();
if (isset($res_orga['membre_orga'])){
    $orga = $res_orga['membre_orga'];
}
else{
    $orga = 0;
}
$_SESSION["membre_orga"] = $orga;
$_SESSION["membre_avatar"] = $img_url;

//echo '<br><br>';
//var_dump($user);

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
header('Location: index.php');