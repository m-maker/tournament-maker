<?php

include '../conf.php';

$upd = false;
if (!isset($_SESSION["id"]))
	header("Location: ../connexion.php");

if (isset($_GET["tournoi"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$leTournoi = recupObjetTournoiByID($id_tournoi);
    if ($leTournoi->event_orga_2 != $_SESSION["id"] && $leTournoi->event_orga != $_SESSION["id"])
        header("Location: ../index.php");
}else{
	header("Location: ../index.php");
}

if (isset($_GET["id"])){
	$id_team = htmlspecialchars(trim($_GET["id"]));
	$equipe = recupEquipeByID($id_team);
	$upd = true;
}
?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Administrer mes tournois</title>
		<link rel="stylesheet" type="text/css" href="../css/liste_tournois.css">
		<link rel="stylesheet" type="text/css" href="css/gest_team.css">
		<link href="https://fonts.googleapis.com/css?family=Baloo" rel="stylesheet">
    <!--                     *********************************              FIN DE L'ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

</head>

<body>

<!-- HEADER -->
<?php include('header.php'); ?>

<!-- CONTENU DE LA PAGE -->
<div id="page">

    <!-- VOLET -->
    <?php include('volet.php'); ?>

    <!-- CONTENU DE LA PAGE -->
    <div id="corps">
        <h1 id="titre_corps"><?php echo $leTournoi->event_titre; ?> - Créer une équipe</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->

            
		<div class="container center" id="container" style="margin: 5% auto;">
            <form method="post" action="gest_team_traitement.php?tournoi=<?php echo $id_tournoi; if ($upd){ echo '&id='. $id_team; } ?>">
                <legend>
                    <span class="left">
                        <a href="gestion_equipes.php?tournoi=<?php echo $id_tournoi; ?>">
                            <
                        </a>
                    </span>
                    <?php if ($upd){ echo 'Modifier'; }else{ echo 'Créer'; } ?> une équipe:
                </legend>
                <input type="text" class="form-control" name="nom" placeholder="Nom de l'équipe" <?php if ($upd){ echo 'value="'.$equipe["team_nom"].'"'; } ?>>
                Etat de l'équipe :
                <label class="espace-left espace-top espace-bot">
                    Privé
                    <input type="radio" name="etat-team" id="prv" class="etat-team" value="1" <?php if ($upd && $equipe["team_prive"] == 1){ echo 'checked'; } ?>>
                </label>
                <label class="espace-left">
                    Public
                    <input type="radio" name="etat-team" id="pub" class="etat-team" value="0" <?php if ($upd && $equipe["team_prive"] == 0 || !$upd){ echo 'checked'; } ?>>
                </label>
                <input type="text" name="mdp-team" class="form-control" <?php ?> id="mdp-team" <?php if ($upd && $equipe["team_prive"] == 0 || !$upd) { echo 'style="display:none;"'; } ?> placeholder="Mot de passe de l'équipe" <?php if ($upd){ echo 'value="'.$equipe["team_pass"].'"'; } ?>>
                <button type="submit" name="submit" class="btn <?php if($upd){echo'btn-primary';}else{echo'btn-success';} ?> btn-grand" style="margin-top: 4%;">
                    <span class="glyphicon glyphicon-ok-sign"></span> <?php if ($upd){ echo 'Modifier'; }else{ echo 'Créer'; } ?> l'équipe
                </button>
            </form>
		</div>
	</div>
</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

		<script>
			$(".etat-team").click(function() {
	    		var id = $(this).attr("id");
	    		var input_pass = $("#mdp-team");
	    		if (id == "pub"){
	    			input_pass.val("");
	    			input_pass.hide();
	    		}else
	    			input_pass.show();
	    	});
		</script>

	</body>

</html>