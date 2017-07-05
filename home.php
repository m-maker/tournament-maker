<?php
include('conf.php');
include "connect_api_fb.php";

$ip = $_SERVER['REMOTE_ADDR'];
$fichier_log = 'log/visites.txt';
$fichier_compte = 'log/visites_compte.txt';
$pointeur = fopen($fichier_log, 'a+');
$pointeur_compte = fopen($fichier_compte, 'w+');

$visites = file($fichier_log);
$ecrire = true;
foreach ($visites as $uneVisite){
    if ($ip."\r\n" == $uneVisite){
        $ecrire = false;
      }
}

if ($ecrire) {
    fwrite($pointeur, $ip . "\r\n");
    $compte_visites = count(file($fichier_log));
    fwrite($pointeur_compte, $compte_visites);
}

if (isset($_SESSION["id"]) && $_SESSION['membre_orga'] == 1){
    header('location:organisateur/index.php');
    exit();
}

?>
<html>

<head>
    <?php include('head.php'); ?>

    <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************                      -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/index_mobile.css">
    <link rel="stylesheet" type="text/css" href="css/home_mobile.css">
    <title>Matchs de foot en salle</title>
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php if (isset($_SESSION["id"])){
    include('header.php');
} ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- CONTENU DE LA PAGE -->
    <div id="corps">
        <div>
            <a href="organiser_match.php" class="btn btn-success">Organiser un match</a>
        </div>
        <div>
            <a href="trouver_match.php" class="btn btn-success">Rejoindre un match</a>
        </div>
    </div>


</div>
<!-- FOOTER -->
    <?php 
        if (isset($_SESSION["id"])){
            include('footer.php');
        }
    ?>
<script type="text/javascript">
    
            $(".show1").click(function() {
                $(".show1").removeClass("acti1");
                $(this).addClass("acti1");
                $(".cont1").hide();
                var id = $(this).attr("id");
                if (id == "show-prives")
                    $("#prives").show();
                else if (id == "show-publiques")
                    $("#publiques").show();
            });
</script>
</body>

</html>