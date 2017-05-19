<?php

include "../conf.php";
$tab_dates = array("01" => "Janvier", "02" => "Fevrier", "03" => "Mars", "04" => "Avril", "05" => "Mai", "06" => "Juin", "07" => "Juillet", "08" => "Aout", "09" => "Septembre", "10" => "Octobre", "11" => "Novembre", "12" => "Décembre");



?>

<html>
	<head>
		<?php include ('head.php'); ?>
		<title>Tournoi</title>
		<style type="text/css">
			.form-mango {
				color: white;
				background: rgba(0, 0, 0, 0.5);
				padding: 1%;
				margin-top: 2%;
			}
			.align-select {
				width:33%; 
				display: inline-block;
			}
		</style>
	</head>

	<body>

	<div class="container">

		<div class="form-mango">
			<form class="form-horizontal" method="post" action="add_user.php">
				<fieldset>
    				<legend class="center">Créer votre compte MangoPay</legend>
    				<div class="form-group">
      					<label for="nom" class="col-lg-2 control-label">Nom</label>
      					<div class="col-lg-10">
							<input type="text" name="nom" class="form-control" placeholder="Nom" />
      					</div>
					</div>
					<div class="form-group">
      					<label for="prenom" class="col-lg-2 control-label">Prénom</label>
      					<div class="col-lg-10">
							<input type="text" name="prenom" class="form-control" placeholder="Prénom" />
      					</div>
					</div>
					<div class="form-group">
      					<label for="mail" class="col-lg-2 control-label">E-mail</label>
      					<div class="col-lg-10">
							<input type="email" name="mail" class="form-control" placeholder="Adresse de courrier électronique (ex: contact@adn-five.fr)">
      					</div>
					</div>
					<div class="form-group">
      					<label for="select" class="col-lg-2 control-label">Date de naissance</label>
      					<div class="col-lg-10">
      						<select class="form-control align-select" name="jour" placeholder="Jour">
								<?php for ($i = 1; $i < 31; $i++){ ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php } ?>
							</select>
							<select class="form-control align-select" name="mois" placeholder="Mois">
								<?php foreach ($tab_dates as $key => $value) { ?>
									<option value="<?php echo $key; ?>"><?php echo $value ?></option>
								<?php } ?>
							</select>
							<select class="form-control align-select" name="annee" placeholder="Année">
								<?php for ($i = date("Y"); $i > date("Y") - 100; $i--){ ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php } ?>
							</select>
      					</div>
    				</div>
    				<!-- <div class="form-group">
      					<label for="mail" class="col-lg-2 control-label">Adresse postale</label>
      					<div class="col-lg-10">
							<input type="text" name="adresse" class="form-control" placeholder="Adresse postale (ex: 2 rue du Petit Muguet)">
      					</div>
					</div>
					<div class="form-group">
      					<label for="mail" class="col-lg-2 control-label">Ville</label>
      					<div class="col-lg-10">
							<input type="text" name="ville" class="form-control" placeholder="Ville de résidence (ex: Bordeaux)">
      					</div>
					</div>
					<div class="form-group">
      					<label for="mail" class="col-lg-2 control-label">Code Postal</label>
      					<div class="col-lg-10">
							<input type="number" name="cp" class="form-control" placeholder="Code postal (ex: 33150)">
      					</div>
					</div> -->
    				<div class="form-group">
      					<label for="select" class="col-lg-2 control-label">Nationnalité</label>
      					<div class="col-lg-10">
							<select class="form-control" name="nat" placeholder="Année">
									<option value="FR">France</option>
							</select>
      					</div>
    				</div>
    				<div class="form-group">
      					<label for="select" class="col-lg-2 control-label">Pays de résidence</label>
      					<div class="col-lg-10">
							<select class="form-control" name="residence" placeholder="Année">
									<option value="FR">France</option>
							</select>
      					</div>
    				</div>
    				<div class="form-group">
      					<label for="select" class="col-lg-2 control-label">Devise (Monnaie) utilisée</label>
      					<div class="col-lg-10">
							<select class="form-control" name="devise" placeholder="Monnaie">
									<option value="EUR">Euros</option>
									<option value="DOL">Dollars</option>
							</select>
      					</div>
    				</div>
    				<div class="form-group" style="text-align: center;">     					
      					<button class="btn btn-default align-select" type="reset">Annuler</button>
      					<button class="btn btn-success align-select">Ajouter mon compte</button>
					</div>
				</fieldset>
			</form>
		</div>

	</div>

	</body>
</html>