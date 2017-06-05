<?php

include 'conf.php';

?>

<html>

<head>
    <?php include('head.php'); ?>
    <link rel="stylesheet" type="text/css" href="organisateur/css/orga.css">
    <title>Récuperer mon mot de passe</title>    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
    <style>
        a { color: black; }
        a:hover { color: dimgray; }
    </style>
</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- CONTENU DE LA PAGE -->
    <div id="corps">

        <h1 id="titre_corps">Récuperer mon mot de passe</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
        <div class="container" id="container" style="margin: 5% auto;">

            <div class="form-invite">

                <div style="margin-bottom: 1%; margin-top: 2%;">
                    <a href="index.php" style="color: black;">
                        <span style="text-align: left; font-size: 20px; background: white; padding: 1%; border-radius: 5px;">
                            < Retour à l'accueil
                        </span>
                    </a>
                </div>

                <?php if (isset($_POST["mail"])){
                    if (!empty($_POST["mail"])) {
                        $mail = htmlspecialchars(trim($_POST["mail"]));
                        $req = $db->prepare("SELECT * FROM membres WHERE membre_mail = :mail;");
                        $req->bindValue(":mail", $mail, PDO::PARAM_STR);
                        $req->execute();

                        if ($req->rowCount() > 0){

                            $membre = $req->fetch();

                            $pass = chaineRandom(20);
                            $nouveau_pass = md5($pass);

                            $req_upd = $db->prepare("UPDATE membres SET membre_pass = :nv_pass WHERE membre_mail = :mail;");
                            $req_upd->bindValue(":mail", $mail, PDO::PARAM_INT);
                            $req_upd->bindValue(":nv_pass", $nouveau_pass, PDO::PARAM_STR);
                            $req_upd->execute();

                            $message = "Bonjour, ".$membre['membre_mail'].", votre nouveau mot de passe est ".$pass.", vous pouvez désormais vous connecter avec !<br /><br />
                            <a href='".$param->url_site."'>Cliquer ici pour aller sur le site</a>";

                            $objet = $param->nom_site . ' - Votre nouveau mot de passe';
                            $nom_exp = $param->nom_site;

                            envoyerMail($param->mail_contact, $mail, $objet, $nom_exp, $message);
                            ?>

                            <div class="cont-info">Vous avez bien changé votre mot de passe par mail, vérifiez que vous l'avez recu et connectez vous !
                            <a href="index.php">Retournez à l'accueil pour vous connecter</a> </div>

                        <?php }
                    }
                }else{ ?>

                    <div class="cont-info">
                        Saisissez votre adresse mail, un nouveau mot de passe va vous être envoyé
                        L'adresse doit être celle reliée à votre compte sur le site.
                    </div>
                    <form method="post">
                        <input type="text" name="mail" class="form-control" placeholder="Saisir l'adresse mail liée a votre compte.." />
                        <button type="submit" class="btn btn-success btn-grand"><span class=""></span> Envoyer mon nouveau mot de passe</button>
                    </form>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?php include('footer.php') ?>

<script type="text/javascript">
</script>

</body>

</html>