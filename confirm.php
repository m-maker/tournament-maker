<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 01/06/2017
 * Time: 17:32
 */

include "conf.php";

?>

    <html>

    <head>
        <?php include('head.php'); ?>

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Kumar+One" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Permanent+Marker" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Yellowtail" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/index.css">
        <style>
            h2 a {
                color: darkgreen;
            }
            h2 a:hover {
                color: darkslategrey;
            }
        </style>
        <title>Tournois de foot en salle</title>
        <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

    </head>

<body>

    <!-- HEADER -->
<?php if (isset($_SESSION["id"])){
    include('header.php');
} ?>

    <!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
<?php
if (isset($_SESSION['id']) && !empty($_SESSION['id'])){
    include('volet.php');
}?>


    <!-- CONTENU DE LA PAGE -->
    <div id="corps">
        <div id="titre_corps">Confirmation de votre adresse mail.</div>
<?php if (isset($_GET["code"])){

    $code = htmlspecialchars(trim($_GET["code"]));

    $req = $db->prepare("SELECT * FROM membres WHERE membre_code_validation = :code");
    $req->bindValue(":code", $code, PDO::PARAM_INT);
    $req->execute();
    $membre = $req->fetch();

    if ($req->rowCount() > 0){
        $req_upd = $db->prepare("UPDATE membres SET membre_validation = 1 WHERE membre_id = :id");
        $req_upd->bindValue(":id", $membre["membre_id"], PDO::PARAM_INT);
        $req_upd->execute();
        ?>
        <h2 class="vert center" style="margin-top: 200px;">
            Votre compte a bien été vérifié.<br />
            <a href="index.php">Revenir à l'accueil</a>
        </h2>

        <?php
    }else{?>
        <h2 class="rouge center" style="margin-top: 200px;">Votre compte n'a pas pu être vérifié, êtes vous sur du code de confirmation ?</h2>
    <?php }

}
?>
        </div>
    </div>
</body>
    </html>

<?php include 'footer.php'; ?>
