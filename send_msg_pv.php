<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 29/05/2017
 * Time: 18:48
 */

include 'conf.php';

if (isset($_POST['id']) && isset($_POST["msg"])){
    $id = htmlspecialchars(trim($_POST["id"]));
    $msg = htmlspecialchars(trim($_POST["msg"]));
    if (!empty($msg) && !empty($id)){

        $req = $db->prepare("INSERT INTO messages_prives (pv_expediteur_id, pv_destinataire_id, pv_date, pv_message, pv_vu) VALUES (:id_exp, :id_dest, NOW(), :msg, 0);");
        $req->bindValue(":id_exp", $_SESSION["id"], PDO::PARAM_INT);
        $req->bindValue(":id_dest", $id, PDO::PARAM_INT);
        $req->bindValue(":msg", $msg, PDO::PARAM_STR);
        $req->execute();
    }
}elseif(isset($_POST["pseudo"]) && isset($_POST["msg"])){
    $msg = htmlspecialchars(trim($_POST["msg"]));
    $pseudo = htmlspecialchars(trim($_POST["pseudo"]));

    $req_pseudo = $db->prepare("SELECT * FROM membres WHERE membre_pseudo = :pseudo");
    $req_pseudo->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
    $req_pseudo->execute();
    $dest_id = $req_pseudo->fetchColumn();

    $req = $db->prepare("INSERT INTO messages_prives (pv_expediteur_id, pv_destinataire_id, pv_date, pv_message, pv_vu) VALUES (:id_exp, :id_dest, NOW(), :msg, 0);");
    $req->bindValue(":id_exp", $_SESSION["id"], PDO::PARAM_INT);
    $req->bindValue(":id_dest", $dest_id, PDO::PARAM_INT);
    $req->bindValue(":msg", $msg, PDO::PARAM_STR);
    $req->execute();

}else{
    die;
}

echo '<div class="un-msg msg-me">
    '. $msg .'
    <span class="right" style="margin-top: 10px;font-size: 12px;">
        '. date("d-m-Y H:i:s") .'
    </span>
</div>';


