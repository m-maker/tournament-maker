<?php
include 'conf.php';
?>

<html>
	
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/footer.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<title>Créer un compte </title>
	</head>

	<body style="background: gainsboro;">

		<!-- HEADER -->
		<?php include('header.php'); ?>

		<!-- CONTENU DE LA PAGE -->

		<!-- BARRE DE RECHERCHE -->
        <div class="container-fluid">
	    <div class="row space-top">
			<div class="form-grand">

				<div class="form-simple">

						<form class="form-horizontal form-grand" method="post" action="inscription_check.php">
						  	<fieldset>

						    	<legend class="center">Créez un compte :</legend>

						    	<div class="form-group">
						        	<input type="text" class="form-control" id="inputPseudo" name="pseudo" placeholder="Votre pseudo">
						    	</div>

						    	<div class="form-group">
						        	<input type="password" class="form-control" id="inputPass" name="pass" placeholder="*******">
						    	</div>

						    	<div class="form-group">
						        	<input type="tel" class="form-control" id="inputTel" name="tel" placeholder="Votre numéro de telephone" pattern="^[0-9]{10}$">
						    	</div>

						    	<div class="form-group">
						        	<input type="tel" class="form-control" id="inputEmail" name="mail" placeholder="Votre adresse-mail">
						    	</div>

						    	<div class="form-group center">
					          		<label style="margin-right: 2%;">
					            		<input type="radio" name="orga" id="orga" value="0" checked="">
					            		Joueur
					          		</label>
					          		<label>
					            		<input type="radio" name="orga" id="orga" value="1">
					            		Organisateur
					          		</label>	
							    </div>

						    	<div class="form-group center">
						    		<button type="submit" name="submit" class="btn btn-success btn-grand">S'inscrire</button>
							    </div>

						    </fieldset>
						</form>

				</div>

			</div>
		</div>
        </div>
		<!-- FOOTER -->
		<?php include('footer.php') ?>
	</body>

</html>