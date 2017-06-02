<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 02/06/2017
 * Time: 13:37
 */

include 'conf.php';

if (!isset($_SESSION["id"])){
    if (isset($_POST["ancien_pass"]) && isset($_POST["nouveau_pass"])){
        $ancien_pass = htmlspecialchars(trim($_POST["ancien_pass"]));
        $nouveau_pass = htmlspecialchars(trim($_POST['nouveau_pass']));

        if (!empty($nouveau_pass) && !empty($ancien_pass)){
            $req = $db->prepare("SELECT * FROM membres WHERE membre_pass = :ancien_pass;");
            $req->bindValue(":ancien_pass", $ancien_pass, PDO::PARAM_STR);
            $req->execute();
            if ($req->rowCount() > 0){
                $req_upd = $db->prepare("UPDATE membres SET membre_pass = :nv_pass;");
                $req_upd->bindValue(":nc_pass", $nouveau_pass, PDO::PARAM_STR);
                $req_upd->execute();
                header("Location: parametres.fr");
            }
        }

    }
}

?>

