<html>
	
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/footer.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<title>S'inscrire sur la plateforme</title>
	</head>

	<body>

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->

		<!-- BARRE DE RECHERCHE -->
		<div class="row space-top">
			<div class="col-lg-10">
				<form class="form-horizontal form-grand" method="post" action="inscription_check.php">
				  	<fieldset>
				    	<legend class="center">Saisissez les infos suivantes :</legend>

				    	<div class="form-group">
				      		<label for="inputEmail" class="col-lg-2 control-label">Pseudonyme</label>
				      		<div class="col-lg-10">
				        		<input type="text" class="form-control" id="inputPseudo" name="pseudo" placeholder="Votre adresse-mail">
				      		</div>
				    	</div>

				    	<div class="form-group">
				      		<label for="inputEmail" class="col-lg-2 control-label">Mot de passe</label>
				      		<div class="col-lg-10">
				        		<input type="password" class="form-control" id="inputPass" name="pass" placeholder="*******">
				      		</div>
				    	</div>

				    	<div class="form-group">
				      		<label for="inputEmail" class="col-lg-2 control-label">Confirmer le mot de passe</label>
				      		<div class="col-lg-10">
				        		<input type="password" class="form-control" id="inputConfirm" name="confirm_pass" placeholder="*******">
				      		</div>
				    	</div>

				    	<div class="form-group">
				      		<label for="inputEmail" class="col-lg-2 control-label">N° de telephone</label>
				      		<div class="col-lg-10">
				        		<input type="tel" class="form-control" id="inputEmail" name="tel" placeholder="Votre numéro de telephone" pattern="^[0-9]{10}$">
				      		</div>
				    	</div>

				    	<div class="form-group">
				      		<label for="inputEmail" class="col-lg-2 control-label">Email</label>
				      		<div class="col-lg-10">
				        		<input type="tel" class="form-control" id="inputEmail" name="mail" placeholder="Votre adresse-mail">
				      		</div>
				    	</div>

				    	<div class="form-group">
					      	<label class="col-lg-2 control-label">Vous êtes un :</label>
					      	<div class="col-lg-10">
					        	<div class="radio">
					          		<label>
					            		<input type="radio" name="orga" id="optionsRadios1" value="0" checked="">
					            		Joueur
					          		</label>
					        	</div>
					        	<div class="radio">
					          		<label>
					            		<input type="radio" name="orga" id="optionsRadios2" value="1">
					            		Organisateur
					          		</label>
					        	</div>
					      	</div>
					    </div>

				    	<div class="form-group center">
					      	<div class="col-lg-10 col-lg-offset-2">
					        	<button type="reset" class="btn btn-default btn-grand">Annuler</button>
					        	<button type="submit" name="submit" class="btn btn-primary btn-grand">S'inscrire</button>
					      	</div>
					    </div>

				    </fieldset>
				</form>
			</div>
		</div>

		<!-- FOOTER -->
		<?php include('footer.php') ?>
	</body>

</html>