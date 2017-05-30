<?php

include '../conf.php';

$upd = false;
if (!isset($_SESSION["id"]))
	header("Location: ../connexion.php");

if (isset($_GET["tournoi"]) && isset($_GET["team"])){
	$id_tournoi = htmlspecialchars(trim($_GET["tournoi"]));
	$id_team = htmlspecialchars(trim($_GET["team"]));
	$equipe = recupEquipeByID($id_team);
	$leTournoi = recupObjetTournoiByID($id_tournoi);
	if ($leTournoi->event_orga_2 != $_SESSION["id"] && $leTournoi->event_orga != $_SESSION["id"])
		header("Location: index.php");
}else{
	header("Location: index.php");
}

if (isset($_GET["id"])){
	$id_joueur = htmlspecialchars(trim($_GET["id"]));
	$joueur = recupJoueurByID($id_joueur, $id_team);
	$upd = true;
}
?>

<html>
	
	<head>
		<?php include('head.php'); ?>
		<title>Ajouter un joueur</title>
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
        <h1 id="titre_corps"><?php echo $leTournoi->event_titre; ?> > <?php if (!$upd) { echo 'Ajouter'; }else{echo 'Modifier';} ?> un joueur</h1>
        <!-- CADRE DU CONTENU -->

        <!--                     *********************************              ESPACE SPECIFIQUE A LA PAGE             **********************************              -->
		<div class="container center" id="container" style="margin: 5% auto;">

			<?php if (!$upd) { ?>
				<form method="post" action="gest_joueur_traitement.php?tournoi=<?php echo $id_tournoi; ?>&team=<?php echo $id_team; ?>">
					<legend>
						<span class="left">
							<a href="gestion_equipes.php?tournoi=<?php echo $id_tournoi; ?>"> 
								< 
							</a>
						</span> 
						Ajouter un joueur à l'equipe <?php echo $equipe['team_nom']; ?> :
					</legend>
					<input type="text" class="form-control espace-top" name="mail" placeholder="Adresse mail du joueur (valide)" <?php if ($upd){ echo 'value="'.$equipe["team_nom"].'"'; } ?>>
					Paiement de la place : 
					<label class="espace-left espace-top espace-bot">
						Payé
						<input type="radio" name="paye" id="p" class="paye" value="1">
					</label>
					<label class="espace-left">
						Non Payé
						<input type="radio" name="paye" id="np" class="paye" value="0" checked>
					</label>
					<br />
					<p class="underline bold">Veillez à rentrer une adresse mail valide à laquelle l'invité aura acces.<br />
					Un mail lui sera envoyé pour confirmer sa présence au tournoi dans cette équipe.</p>
					<button type="submit" name="submit" class="btn btn-success btn-grand" style="margin-top: 4%;"><span class="glyphicon glyphicon-ok-sign"></span> Ajouter le joueur</button>
				</form>
			<?php }else{ ?>
				<form method="post" action="gest_joueur_traitement.php?tournoi=<?php echo $id_tournoi; ?>&team=<?php echo $id_team; ?>&id=<?php echo $joueur['membre_id']; ?>">
					<legend>
						<span class="left">
							<a href="gestion_equipes.php?tournoi=<?php echo $id_tournoi; ?>"> 
								< 
							</a>
						</span> 
						Modifier les caractéristiques de <span class="bold"><?php echo $joueur["membre_pseudo"]; ?></span> dans l'équipe <span class="bold"><?php echo $equipe['team_nom']; ?></span>
					</legend>
					<select name="statut" class="form-control" placeholder="Statut du joueur">
						<?php foreach (recupStatuts() as $unStatut) { ?>
						<option value="<?php echo $unStatut['statut_id']; ?>" <?php if ($joueur['em_statut_joueur'] == $unStatut['statut_id']){echo 'selected';} ?> ><?php echo $unStatut['statut_nom']; ?></option>
						<?php } ?>
					</select>
					Paiement de la place : 
					<label class="espace-left espace-top espace-bot">
						Payé
						<input type="radio" name="paye" id="p" class="paye" value="1" <?php if ($joueur['em_membre_paye'] == 1){echo 'checked';} ?> >
					</label>
					<label class="espace-left">
						Non Payé
						<input type="radio" name="paye" id="np" class="paye" value="0" <?php if ($joueur['em_membre_paye'] == 0){echo 'checked';} ?> >
					</label>
					<button type="submit" name="submit" class="btn btn-primary btn-grand" style="margin-top: 3%;"><span class="glyphicon glyphicon-ok-sign"></span> Modifier</button>
				</form>
			<?php } ?>

		</div>
	</div>
</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>

		<script>

		</script>

	</body>

</html>