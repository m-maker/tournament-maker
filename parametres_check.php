<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 02/06/2017
 * Time: 12:59
 */

include "conf.php";

//var_dump($_POST);

if (!isset($_SESSION["id"]))
    header("Location: index.php");

if (isset($_POST["pseudo"]) && isset($_POST["mail"])){
    $pseudo = htmlspecialchars(trim($_POST["pseudo"]));
    $mail = htmlspecialchars(trim($_POST["mail"]));

    if (!empty($mail) && !empty($pseudo)){
        if (membre_existe("membre_pseudo", $pseudo) && $pseudo != $_SESSION["pseudo"]) {
            alert("Ce pseudo est déja pris, <a href='parametres.php'>< Retour</a>");
        }else{
            if (membre_existe("membre_mail", $mail) && $mail != $_SESSION["membre_mail"]) {
                alert("Cette adresse mail est déja prise, <a href='parametres.php'>< Retour</a>");
            }else {
                $req = $db->prepare("UPDATE membres SET membre_mail = :mail, membre_pseudo = :pseudo WHERE membres.id = :id ");
                $req->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
                $req->bindValue(":mail", $mail, PDO::PARAM_STR);
                $req->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
                $req->execute();

                $_SESSION["membre_mail"] = $mail;
                $_SESSION["pseudo"] = $pseudo;
                header('Location: parametres.php');
            }
        }

    }else{
        alert("Votre pseudo / mail ne peut pas être vide <a href='parametres.php'>< Retour</a>");
    }

}elseif (isset($_POST["ancien_pass"]) && isset($_POST["nouveau_pass"])){
        $ancien_pass = htmlspecialchars(trim($_POST["ancien_pass"]));
        $nouveau_pass = htmlspecialchars(trim($_POST['nouveau_pass']));

        if (!empty($nouveau_pass) && !empty($ancien_pass)){
            $req = $db->prepare("SELECT * FROM membres WHERE membres.id = :id");
            $req->bindValue(':id', $_SESSION["id"], PDO::PARAM_INT);
            $req->execute();
            $infos_membre = $req->fetch();
            if ($infos_membre['membre_pass'] == md5($ancien_pass)) {
                $nouveau_pass = md5($nouveau_pass);
                $req_upd = $db->prepare("UPDATE membres SET membre_pass = :nv_pass WHERE id = :id;");
                $req_upd->bindValue(":id", $_SESSION["id"], PDO::PARAM_INT);
                $req_upd->bindValue(":nv_pass", $nouveau_pass, PDO::PARAM_STR);
                $req_upd->execute();
                header("Location: parametres.php");
            }
        }

}else{
    alert("Une erreur est survenue <a href='parametres.php'>< Retour</a>");
}